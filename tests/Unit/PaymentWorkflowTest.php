<?php

namespace Tests\Unit;

use App\Models\Payment;
use App\Support\PaymentWorkflow;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PaymentWorkflowTest extends TestCase
{
    public function test_it_marks_payment_as_paid_and_sets_verification_fields(): void
    {
        Carbon::setTestNow('2026-05-18 10:00:00');

        $payment = new Payment(['status' => 'pending_verification']);

        $result = PaymentWorkflow::prepare([
            'status' => 'paid',
            'paid_at' => null,
            'rejection_reason' => 'Akan dihapus',
        ], $payment, 7);

        $this->assertSame('paid', $result['status']);
        $this->assertSame(7, $result['verified_by']);
        $this->assertNull($result['rejection_reason']);
        $this->assertNotNull($result['paid_at']);
        $this->assertNotNull($result['verified_at']);

        Carbon::setTestNow();
    }

    public function test_it_requires_rejection_reason_when_payment_is_rejected(): void
    {
        $this->expectException(ValidationException::class);

        PaymentWorkflow::prepare([
            'status' => 'rejected',
            'rejection_reason' => '',
        ], new Payment(['status' => 'pending_verification']), 9);
    }

    public function test_it_clears_review_fields_when_payment_returns_to_pending(): void
    {
        $result = PaymentWorkflow::prepare([
            'status' => 'pending_verification',
            'paid_at' => now(),
            'verified_at' => now(),
            'verified_by' => 9,
            'rejection_reason' => 'Contoh alasan',
        ], new Payment(['status' => 'rejected']), 9);

        $this->assertNull($result['paid_at']);
        $this->assertNull($result['verified_at']);
        $this->assertNull($result['verified_by']);
        $this->assertNull($result['rejection_reason']);
    }
}
