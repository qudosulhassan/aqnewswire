<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Comment;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['article', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $comments = $query->latest()->paginate(20)->withQueryString();

        return view('admin.comments.index', compact('comments'));
    }

    public function updateStatus(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,pending,reported,rejected,spam',
        ]);

        $comment->update(['status' => $validated['status']]);
        AuditLog::record('comment_moderated', 'Comment', $comment->id, "Status set to {$validated['status']}");

        return back()->with('success', "Comment marked as {$validated['status']}.");
    }

    public function destroy(Comment $comment)
    {
        AuditLog::record('comment_deleted', 'Comment', $comment->id, "Deleted comment ID {$comment->id}");
        $comment->delete();

        return back()->with('success', 'Comment deleted permanently.');
    }
}
