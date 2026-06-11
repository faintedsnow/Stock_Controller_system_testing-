<?php

namespace App\Support;

use App\Models\User;

class SimpleApiToken
{
    private const TTL_SECONDS = 604800;

    public static function issue(User $user): string
    {
        $issuedAt = time();
        $payload = $user->id.'|'.$issuedAt;
        $signature = hash_hmac('sha256', $payload, self::secret());

        return self::encode($payload.'|'.$signature);
    }

    public static function userFromToken(?string $token): ?User
    {
        if (! $token) {
            return null;
        }

        $decoded = self::decode($token);
        if (! $decoded) {
            return null;
        }

        $parts = explode('|', $decoded);
        if (count($parts) !== 3) {
            return null;
        }

        [$userId, $issuedAt, $signature] = $parts;
        if (! ctype_digit($userId) || ! ctype_digit($issuedAt)) {
            return null;
        }

        if ((time() - (int) $issuedAt) > self::TTL_SECONDS) {
            return null;
        }

        $payload = $userId.'|'.$issuedAt;
        $expected = hash_hmac('sha256', $payload, self::secret());
        if (! hash_equals($expected, $signature)) {
            return null;
        }

        return User::find((int) $userId);
    }

    private static function secret(): string
    {
        $key = config('app.key') ?: env('APP_KEY') ?: 'stock-controller-assignment-secret';

        if (str_starts_with($key, 'base64:')) {
            return base64_decode(substr($key, 7), true) ?: $key;
        }

        return $key;
    }

    private static function encode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private static function decode(string $value): ?string
    {
        $decoded = base64_decode(strtr($value, '-_', '+/'), true);

        return $decoded === false ? null : $decoded;
    }
}
