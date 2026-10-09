<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\AuditLog;
use App\Models\EditorialNote;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminEditorialNoteController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $validated = $request->validate([
            'note_type' => 'required|in:editorial,revision_request,fact_check,seo',
            'content' => 'required|string|max:2000',
        ]);

        $note = EditorialNote::create([
            'article_id' => $article->id,
            'user_id' => Auth::id(),
            'note_type' => $validated['note_type'],
            'content' => $validated['content'],
            'is_resolved' => false,
        ]);

        // If revision requested, notify author
        if ($validated['note_type'] === 'revision_request') {
            $article->update(['status' => 'submitted']); // or revision requested
            UserNotification::create([
                'user_id' => $article->user_id,
                'type' => 'revision_request',
                'title' => 'Revision Requested: ' . $article->title,
                'message' => 'Editorial feedback has been added by ' . Auth::user()->name . ': "' . Str::limit($validated['content'], 100) . '"',
                'action_url' => route('contributor.articles.edit', $article),
            ]);
        }

        AuditLog::record('editorial_note_added', 'Article', $article->id, "Added note ({$validated['note_type']})");

        return back()->with('success', 'Internal editorial note saved.');
    }

    public function toggleResolve(Article $article, EditorialNote $note)
    {
        abort_if($note->article_id !== $article->id, 404);
        $note->update(['is_resolved' => !$note->is_resolved]);
        AuditLog::record('editorial_note_toggled', 'Article', $article->id, "Note {$note->id} status: " . ($note->is_resolved ? 'resolved' : 'open'));

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'is_resolved' => $note->is_resolved]);
        }
        return back()->with('success', 'Editorial note status updated.');
    }

    public function destroy(Article $article, EditorialNote $note)
    {
        abort_if($note->article_id !== $article->id, 404);
        $note->delete();
        AuditLog::record('editorial_note_deleted', 'Article', $article->id, "Deleted editorial note {$note->id}");

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Editorial note removed.');
    }
}
