<?php

namespace Tests\Unit;

use App\Support\PaymentReminder;
use Tests\TestCase;

class PaymentReminderTest extends TestCase
{
    public function test_it_builds_payment_reminder_whatsapp_link(): void
    {
        $link = PaymentReminder::link(
            '085212345678',
            'Budi',
            'Kamar Mawar',
            750000,
            '2026-05-01',
            '2026-05-31',
            '2026-05-20'
        );

        $this->assertNotNull($link);
        $this->assertStringContainsString('wa.me/6285212345678', $link);
        $this->assertStringContainsString('Budi', urldecode($link));
        $this->assertStringContainsString('Kamar Mawar', urldecode($link));
        $this->assertStringContainsString('Rp750.000', urldecode($link));
    }

    public function test_it_returns_null_without_phone_number(): void
    {
        $this->assertNull(PaymentReminder::link(null, 'Budi', 'Kamar Mawar', 750000, '2026-05-01', '2026-05-31', '2026-05-20'));
    }
}
