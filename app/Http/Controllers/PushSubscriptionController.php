<?php

namespace App\Http\Controllers;

use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushSubscriptionController extends Controller
{
    public function subscribe(Request $request, PushNotificationService $pushService)
    {
        $validated = $request->validate([
            'endpoint' => 'required|url|max:500',
            'public_key' => 'nullable|string',
            'auth_token' => 'nullable|string',
        ]);

        $subscription = $pushService->subscribe(
            Auth::id(),
            $validated['endpoint'],
            $validated['public_key'] ?? null,
            $validated['auth_token'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Web push subscription successfully registered.',
            'id' => $subscription->id,
        ]);
    }

    public function unsubscribe(Request $request, PushNotificationService $pushService)
    {
        $validated = $request->validate([
            'endpoint' => 'required|url|max:500',
        ]);

        $pushService->unsubscribe($validated['endpoint']);

        return response()->json([
            'success' => true,
            'message' => 'Web push subscription deactivated.',
        ]);
    }
}
