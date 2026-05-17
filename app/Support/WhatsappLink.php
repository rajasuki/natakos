<?php

namespace App\Support;

class WhatsappLink
{
    public static function normalizeNumber(?string $number, string $fallback = '6285217430009'): string
    {
        $normalized = preg_replace('/\D+/', '', $number ?? '') ?? '';

        if ($normalized === '') {
            return $fallback;
        }

        if (str_starts_with($normalized, '0')) {
            return '62'.substr($normalized, 1);
        }

        return $normalized;
    }

    public static function build(string $number, string $message): string
    {
        return 'https://wa.me/'.$number.'?text='.rawurlencode($message);
    }
}
