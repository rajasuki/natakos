<?php

namespace App\Support;

class PaymentReminder
{
    public static function link(
        ?string $phone,
        string $tenantName,
        string $roomName,
        int|float|string|null $amount,
        mixed $periodStart,
        mixed $periodEnd,
        mixed $dueDate
    ): ?string {
        $normalized = preg_replace('/\D+/', '', $phone ?? '') ?? '';

        if ($normalized === '') {
            return null;
        }

        return WhatsappLink::build(
            WhatsappLink::normalizeNumber($phone),
            'Halo '.$tenantName.', ini pengingat pembayaran kos Anda untuk kamar '.$roomName
            .' sebesar '.UiFormatter::currency($amount)
            .' untuk periode '.UiFormatter::date($periodStart).' s/d '.UiFormatter::date($periodEnd)
            .'. Tenggat pembayaran: '.UiFormatter::date($dueDate)
            .'. Mohon konfirmasi pembayaran kepada pengelola NATAKOS. Terima kasih.'
        );
    }
}
