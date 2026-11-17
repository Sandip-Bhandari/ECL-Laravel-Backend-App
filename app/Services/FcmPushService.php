<?php

namespace App\Services;

use App\Models\DeviceToken;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Throwable;

class FcmPushService
{
    public function sendToUser(int $userId, string $title, string $body, array $data = []): void
    {
        $tokens = DeviceToken::where('user_id', $userId)->pluck('device_token');
        if ($tokens->isEmpty()) {
            return;
        }

        try {
            $messaging = app('firebase.messaging');
            $stringData = array_map('strval', $data);

            foreach ($tokens as $token) {
                $message = CloudMessage::withTarget('token', $token)
                    ->withNotification(['title' => $title, 'body' => $body])
                    ->withData($stringData);

                $messaging->send($message);
            }
        } catch (Throwable $exception) {
            Log::error('FCM push delivery failed.', [
                'user_id' => $userId,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
