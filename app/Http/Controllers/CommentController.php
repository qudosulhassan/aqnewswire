<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        if (!\App\Models\Setting::get('enable_comments', true)) {
            return back()->with('error', 'Public comments are currently closed by site administration.');
        }

        if (!Auth::check()) {
            return redirect()->route('account.login')->with('info', 'Please sign in to join the executive discussion.');
        }

        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1500',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        // Sanitize content against XSS
        $sanitizedContent = htmlspecialchars(strip_tags($validated['content']), ENT_QUOTES, 'UTF-8');

        if (!empty($validated['parent_id'])) {
            $parent = Comment::find($validated['parent_id']);
            if (!$parent || $parent->article_id !== $article->id) {
                return back()->withErrors(['content' => 'Invalid reply thread.']);
            }
        }

        // Check for spam indicators (e.g., excessive links)
        $isSpam = preg_match_all('/https?:\/\//i', $validated['content']) > 2;

        $comment = Comment::create([
            'article_id' => $article->id,
            'user_id' => Auth::id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $sanitizedContent,
            'status' => $isSpam ? 'spam' : 'approved',
        ]);

        if ($isSpam) {
            return back()->with('info', 'Your commentary is under editorial moderation due to embedded links.');
        }

        // Send notification to article author if commenter is not author
        if ($article->user_id !== Auth::id()) {
            UserNotification::create([
                'user_id' => $article->user_id,
                'type' => 'comment',
                'title' => 'New Comment on Your Story',
                'message' => Auth::user()->name . ' commented on "' . $article->title . '".',
                'action_url' => route('articles.show', $article->slug) . '#comment-' . $comment->id,
            ]);
        }

        return back()->with('success', 'Your commentary has been published.');
    }

    public function destroy(Comment $comment)
    {
        if (!Auth::check() || (Auth::id() !== $comment->user_id && !Auth::user()->canAccessAdmin())) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();
        return back()->with('success', 'Comment deleted successfully.');
    }

    public function like(Comment $comment)
    {
        $comment->increment('likes_count');

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'likes' => $comment->likes_count,
            ]);
        }

        return back();
    }

    public function report(Comment $comment)
    {
        $comment->increment('reports_count');
        if ($comment->reports_count >= 3) {
            $comment->update(['status' => 'reported']);
        }

        return back()->with('success', 'Thank you. This comment has been flagged for editorial review.');
    }
}
