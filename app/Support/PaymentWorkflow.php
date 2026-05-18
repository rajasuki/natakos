<?php

namespace App\Support;

use App\Models\Payment;
use Illuminate\Validation\ValidationException;

class PaymentWorkflow
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function prepare(array $data, ?Payment $payment, int $adminId): array
    {
        $wasPaid = $payment?->status === 'paid';

        return match ($data['status'] ?? null) {
            'paid' => self::preparePaid($data, $payment, $adminId, $wasPaid),
            'rejected' => self::prepareRejected($data, $adminId),
            'pending_verification', 'unpaid' => self::prepareUnverified($data),
            default => $data,
        };
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function preparePaid(array $data, ?Payment $payment, int $adminId, bool $wasPaid): array
    {
        if (empty($data['paid_at'])) {
            $data['paid_at'] = $payment?->paid_at ?? now();
        }

        if (! $wasPaid || $payment?->verified_at === null || $payment?->verified_by === null) {
            $data['verified_at'] = now();
            $data['verified_by'] = $adminId;
        }

        $data['rejection_reason'] = null;

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function prepareRejected(array $data, int $adminId): array
    {
        if (trim((string) ($data['rejection_reason'] ?? '')) === '') {
            throw ValidationException::withMessages([
                'rejection_reason' => 'Alasan penolakan wajib diisi jika pembayaran ditolak.',
            ]);
        }

        $data['paid_at'] = null;
        $data['verified_at'] = now();
        $data['verified_by'] = $adminId;

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function prepareUnverified(array $data): array
    {
        $data['paid_at'] = null;
        $data['verified_at'] = null;
        $data['verified_by'] = null;
        $data['rejection_reason'] = null;

        return $data;
    }
}
