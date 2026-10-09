<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\MediaAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminMediaController extends Controller
{
    public function index(Request $request)
    {
        $query = MediaAsset::with('uploader');

        if ($request->filled('type')) {
            $query->where('file_type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('filename', 'like', '%' . $request->search . '%')
                  ->orWhere('title', 'like', '%' . $request->search . '%');
        }

        $assets = $query->latest()->paginate(24)->withQueryString();

        return view('admin.media.index', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480|mimes:jpeg,jpg,png,webp,gif,mp4,webm,mp3,wav,ogg,pdf', // 20 MB max, safe extensions (SVG disallowed to prevent XSS)
            'title' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $rawOriginalName = basename($file->getClientOriginalName());
        $originalName = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $rawOriginalName);
        $mime = $file->getMimeType();
        $size = $file->getSize();

        // Detect type
        $type = 'document';
        if (str_starts_with($mime, 'image/')) {
            $type = 'image';
        } elseif (str_starts_with($mime, 'video/')) {
            $type = 'video';
        } elseif (str_starts_with($mime, 'audio/')) {
            $type = 'audio';
        }

        $path = $file->store('media', 'public');

        // Detect dimensions if image
        $dimensions = null;
        if ($type === 'image') {
            $imageInfo = @getimagesize($file->getRealPath());
            if ($imageInfo) {
                $dimensions = "{$imageInfo[0]}x{$imageInfo[1]}";
            }
        }

        $asset = MediaAsset::create([
            'filename' => $originalName,
            'title' => $request->title ?? pathinfo($originalName, PATHINFO_FILENAME),
            'caption' => $request->caption,
            'alt_text' => $request->alt_text,
            'file_path' => $path,
            'file_type' => $type,
            'mime_type' => $mime,
            'file_size' => $size,
            'dimensions' => $dimensions,
            'uploaded_by' => Auth::id(),
        ]);

        AuditLog::record('media_uploaded', 'MediaAsset', $asset->id, "Uploaded {$originalName}");

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'asset' => $asset,
                'url' => asset('storage/' . $asset->file_path),
                'message' => "File {$originalName} uploaded successfully.",
            ]);
        }

        return back()->with('success', "File {$originalName} uploaded successfully.");
    }

    public function pickerList(Request $request)
    {
        $query = MediaAsset::with('uploader')->latest();

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('file_type', $request->type);
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('filename', 'like', "%{$term}%")
                  ->orWhere('title', 'like', "%{$term}%")
                  ->orWhere('alt_text', 'like', "%{$term}%")
                  ->orWhere('caption', 'like', "%{$term}%");
            });
        }

        $assets = $query->paginate(24);

        $items = collect($assets->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'filename' => $item->filename,
                'title' => $item->title,
                'caption' => $item->caption,
                'alt_text' => $item->alt_text,
                'file_path' => $item->file_path,
                'file_type' => $item->file_type,
                'url' => asset('storage/' . $item->file_path),
                'dimensions' => $item->dimensions,
                'created_at' => $item->created_at->format('M d, Y'),
            ];
        });

        return response()->json([
            'data' => $items,
            'current_page' => $assets->currentPage(),
            'last_page' => $assets->lastPage(),
            'total' => $assets->total(),
        ]);
    }

    public function destroy(MediaAsset $medium)
    {
        Storage::disk('public')->delete($medium->file_path);
        AuditLog::record('media_deleted', 'MediaAsset', $medium->id, "Deleted asset {$medium->filename}");
        $medium->delete();

        return back()->with('success', 'Media asset removed.');
    }
}
