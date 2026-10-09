<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function toggle(Article $article)
    {
        if (!\App\Models\Setting::get('enable_bookmarks', true)) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Story bookmarking is currently disabled by site administration.',
                ], 403);
            }
            return back()->with('error', 'Story bookmarking is currently disabled by site administration.');
        }

        if (!Auth::check()) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'redirect' => route('account.login'),
                    'message' => 'Please sign in to bookmark articles.',
                ], 401);
            }
            return redirect()->route('account.login')->with('info', 'Please sign in to save articles to your personal reading list.');
        }


        $userId = Auth::id();
        $bookmark = Bookmark::where('user_id', $userId)->where('article_id', $article->id)->first();

        if ($bookmark) {
            $bookmark->delete();
            $status = 'removed';
            $message = 'Article removed from saved list.';
        } else {
            Bookmark::create([
                'user_id' => $userId,
                'article_id' => $article->id,
            ]);
            $status = 'saved';
            $message = 'Article saved to your personal reading list!';
        }

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
