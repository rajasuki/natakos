<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;

class PaymentProofController extends Controller
{
    public function store(Request $request, Payment $payment): RedirectResponse
    {
        $tenant = $this->currentTenant($request);
        $payment = $tenant->payments()->whereKey($payment->getKey())->firstOrFail();

        if ($payment->status === 'paid') {
            return $this->redirectWithError($payment, 'Pembayaran yang sudah lunas tidak bisa upload ulang bukti bayar.');
        }

        if ($payment->status === 'pending_verification') {
            return $this->redirectWithError($payment, 'Bukti bayar untuk tagihan ini sedang menunggu verifikasi admin.');
        }

        if (! in_array($payment->status, ['unpaid', 'rejected'], true)) {
            abort(404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'proof_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            ],
            [
                'proof_image.required' => 'Bukti pembayaran wajib diunggah.',
                'proof_image.image' => 'File bukti pembayaran harus berupa gambar.',
                'proof_image.mimes' => 'Bukti pembayaran harus berformat JPG, JPEG, PNG, atau WEBP.',
                'proof_image.max' => 'Ukuran bukti pembayaran maksimal 2MB.',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->to($this->dashboardUrl($payment))
                ->withErrors($validator)
                ->withInput($request->except('proof_image'));
        }

        $oldImage = $payment->proof_image;
        $newImage = $request->file('proof_image')->store('payments');

        try {
            $payment->update([
                'proof_image' => $newImage,
                'status' => 'pending_verification',
                'paid_at' => null,
                'verified_at' => null,
                'verified_by' => null,
                'rejection_reason' => null,
            ]);
        } catch (Throwable) {
            $this->deleteProofImage($newImage);

            return $this->redirectWithError($payment, 'Bukti pembayaran gagal diunggah. Silakan coba lagi.');
        }

        if ($oldImage !== $newImage) {
            $this->deleteProofImage($oldImage);
        }

        return redirect()
            ->to($this->dashboardUrl($payment))
            ->with('success', 'Bukti pembayaran berhasil diunggah dan sedang menunggu verifikasi admin.')
            ->with('payment_action_id', $payment->id);
    }

    private function currentTenant(Request $request): Tenant
    {
        return Tenant::query()
            ->where('user_id', $request->user()->id)
            ->where('status', 'active')
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->firstOrFail();
    }

    private function redirectWithError(Payment $payment, string $message): RedirectResponse
    {
        return redirect()
            ->to($this->dashboardUrl($payment))
            ->with('error', $message)
            ->with('payment_action_id', $payment->id);
    }

    private function dashboardUrl(Payment $payment): string
    {
        return route('tenant.dashboard').'#payment-'.$payment->id;
    }

    private function deleteProofImage(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        Storage::disk('local')->delete($path);
        Storage::disk('public')->delete($path);
    }
}
