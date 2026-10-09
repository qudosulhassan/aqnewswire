<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Bookmark;
use App\Models\Follow;
use App\Models\ReadingHistory;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $savedCount = $user->bookmarks()->count();
        $historyCount = $user->readingHistories()->count();
        $followsCount = $user->follows()->count();
        $unreadNotifications = $user->notifications()->unread()->count();

        // Recent bookmarks
        $recentBookmarks = Bookmark::where('user_id', $user->id)
            ->with(['article.category', 'article.author'])
            ->latest()
            ->take(4)
            ->get();

        // Recent reading history
        $recentHistory = ReadingHistory::where('user_id', $user->id)
            ->with(['article.category', 'article.author'])
            ->orderByDesc('last_read_at')
            ->take(5)
            ->get();

        // Algorithmic personalized recommendations
        $recommendationService = app(\App\Services\RecommendationService::class);
        $recommendations = $recommendationService->forUser($user, 4);

        return view('account.dashboard', compact(
            'user',
            'savedCount',
            'historyCount',
            'followsCount',
            'unreadNotifications',
            'recentBookmarks',
            'recentHistory',
            'recommendations'
        ));
    }

    public function bookmarks(Request $request)
    {
        $user = Auth::user();

        $query = Bookmark::where('user_id', $user->id)
            ->with(['article.category', 'article.author']);

        if ($request->filled('q')) {
            $query->whereHas('article', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%');
            });
        }

        $bookmarks = $query->latest()->paginate(10)->withQueryString();

        return view('account.bookmarks', compact('bookmarks'));
    }

    public function readingHistory()
    {
        $user = Auth::user();
        $history = ReadingHistory::where('user_id', $user->id)
            ->with(['article.category', 'article.author'])
            ->orderByDesc('last_read_at')
            ->paginate(15);

        return view('account.history', compact('history'));
    }

    public function clearReadingHistory()
    {
        Auth::user()->readingHistories()->delete();
        return back()->with('success', 'Your reading history has been completely cleared.');
    }

    public function following()
    {
        $user = Auth::user();
        $follows = Follow::where('user_id', $user->id)
            ->with('followable')
            ->latest()
            ->paginate(15);

        return view('account.following', compact('follows'));
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(20);

        return view('account.notifications', compact('notifications'));
    }

    public function markNotificationRead(\App\Models\UserNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['read_at' => now()]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllNotificationsRead()
    {
        Auth::user()->notifications()->unread()->update(['read_at' => now()]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    public function settings()
    {
        $user = Auth::user();
        return view('account.settings', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'twitter' => 'nullable|string|max:100',
            'linkedin' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->bio = $validated['bio'] ?? null;
        $user->website = $validated['website'] ?? null;
        $user->twitter = $validated['twitter'] ?? null;
        $user->linkedin = $validated['linkedin'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profile and preferences updated successfully.');
    }
}
