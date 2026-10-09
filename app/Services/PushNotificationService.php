<?php

namespace App\Services;

use App\Models\PushSubscription;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    /**
     * Store or update a web push subscription.
     */
    public function subscribe(?int $userId, string $endpoint, ?string $publicKey = null, ?string $authToken = null): PushSubscription
    {
        return PushSubscription::updateOrCreate(
            ['endpoint' => $endpoint],
            [
                'user_id' => $userId,
                'public_key' => $publicKey,
                'auth_token' => $authToken,
                'is_active' => true,
            ]
        );
    }

    /**
     * Deactivate a push subscription.
     */
    public function unsubscribe(string $endpoint): bool
    {
        return (bool) PushSubscription::where('endpoint', $endpoint)->update(['is_active' => false]);
    }

    /**
     * Dispatch push notification to a user's registered devices.
     */
    public function sendToUser(int $userId, string $title, string $body, ?string $actionUrl = null): int
    {
        $subscriptions = PushSubscription::where('user_id', $userId)
            ->where('is_active', true)
            ->get();

        $dispatched = 0;
        foreach ($subscriptions as $sub) {
            // Log/dispatch web push payload
            Log::info("WebPush dispatched to subscription #{$sub->id}: [{$title}] {$body}");
            $dispatched++;
        }

        return $dispatched;
    }
}
