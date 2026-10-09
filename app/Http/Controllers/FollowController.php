<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Follow;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('account.login')->with('info', 'Please sign in to follow authors, channels, and topics.');
        }

        $validated = $request->validate([
            'type' => 'required|in:user,author,category,topic,tag',
            'id' => 'required|integer',
        ]);

        if (in_array($validated['type'], ['user', 'author'])) {
            $modelClass = User::class;
        } elseif ($validated['type'] === 'category') {
            $modelClass = Category::class;
        } else {
            $modelClass = Tag::class;
        }

        $entity = $modelClass::findOrFail($validated['id']);

        $follow = Follow::where('user_id', Auth::id())
            ->where('followable_type', $modelClass)
            ->where('followable_id', $entity->id)
            ->first();

        if ($follow) {
            $follow->delete();
            $status = 'unfollowed';
            $message = 'Unfollowed ' . ($entity->name ?? 'channel') . '.';
        } else {
            Follow::create([
                'user_id' => Auth::id(),
                'followable_type' => $modelClass,
                'followable_id' => $entity->id,
            ]);
            $status = 'following';
            $message = 'Now following ' . ($entity->name ?? 'channel') . '!';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
