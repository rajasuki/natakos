<?php

use App\Models\Badge;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Badge::query()->count() > 0) {
            return;
        }

        Badge::query()->insert([
            [
                'name' => 'Warga Baru',
                'effect' => 'none',
                'requirement_type' => null,
                'requirement_value' => null,
                'description' => 'Badge awal untuk semua penghuni.',
                'is_active' => true,
            ],
            [
                'name' => 'Aktif',
                'effect' => 'glow',
                'requirement_type' => 'chat_messages',
                'requirement_value' => 10,
                'description' => 'Kirim 10 pesan di obrolan penghuni.',
                'is_active' => true,
            ],
            [
                'name' => 'Teladan',
                'effect' => 'gold',
                'requirement_type' => 'payments_count',
                'requirement_value' => 6,
                'description' => 'Bayar tagihan 6 kali tepat waktu.',
                'is_active' => true,
            ],
            [
                'name' => 'Senior',
                'effect' => 'rainbow',
                'requirement_type' => 'stay_days',
                'requirement_value' => 365,
                'description' => 'Menghuni lebih dari 1 tahun.',
                'is_active' => true,
            ],
            [
                'name' => 'Veteran',
                'effect' => 'fire',
                'requirement_type' => 'stay_days',
                'requirement_value' => 730,
                'description' => 'Menghuni lebih dari 2 tahun.',
                'is_active' => true,
            ],
        ]);
    }

    public function down(): void
    {
        Badge::query()->truncate();
    }
};
