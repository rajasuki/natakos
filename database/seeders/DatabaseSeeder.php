<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\User;
use App\Support\RoomOccupancy;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $requiredTables = ['facilities', 'rooms', 'tenants', 'payments', 'users'];
        foreach ($requiredTables as $table) {
            if (! Schema::hasTable($table)) {
                return;
            }
        }

        $this->seedFacilities();
        $this->seedUsers();
        $this->seedAdmin();
        $this->seedRooms();
        $this->seedTenants();
        $this->seedPayments();

        RoomOccupancy::syncStatuses(Room::query()->pluck('id')->all());
    }

    private function seedFacilities(): void
    {
        $facilities = [
            ['name' => 'Kasur', 'type' => 'room', 'icon' => 'bed'],
            ['name' => 'Spring Bed', 'type' => 'room', 'icon' => 'bed'],
            ['name' => 'Lemari', 'type' => 'room', 'icon' => 'inventory_2'],
            ['name' => 'Meja Belajar', 'type' => 'room', 'icon' => 'table_restaurant'],
            ['name' => 'Kamar Mandi Dalam', 'type' => 'room', 'icon' => 'shower'],
            ['name' => 'AC', 'type' => 'room', 'icon' => 'ac_unit'],
            ['name' => 'TV', 'type' => 'room', 'icon' => 'tv'],
            ['name' => 'Kipas Angin', 'type' => 'room', 'icon' => 'mode_fan'],
            ['name' => 'WiFi', 'type' => 'public', 'icon' => 'wifi'],
            ['name' => 'Parkir Motor', 'type' => 'public', 'icon' => 'local_parking'],
            ['name' => 'Dapur Bersama', 'type' => 'public', 'icon' => 'countertops'],
            ['name' => 'CCTV 24 Jam', 'type' => 'public', 'icon' => 'videocam'],
        ];

        foreach ($facilities as $fac) {
            Facility::firstOrCreate(
                ['name' => $fac['name'], 'type' => $fac['type']],
                ['icon' => $fac['icon']],
            );
        }
    }

    private function seedUsers(): void
    {
        User::firstOrCreate(
            ['email' => 'andi@example.com'],
            [
                'name' => 'Andi Pratama',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'role' => 'tenant',
            ],
        );

        User::firstOrCreate(
            ['email' => 'budi@example.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'phone' => '081234567892',
                'role' => 'tenant',
            ],
        );

        User::firstOrCreate(
            ['email' => 'citra@example.com'],
            [
                'name' => 'Citra Dewi',
                'password' => Hash::make('password'),
                'phone' => '081234567893',
                'role' => 'tenant',
            ],
        );

        User::firstOrCreate(
            ['email' => 'dedi@example.com'],
            [
                'name' => 'Dedi Kurniawan',
                'password' => Hash::make('password'),
                'phone' => '081234567894',
                'role' => 'tenant',
            ],
        );

        User::firstOrCreate(
            ['email' => 'eka@example.com'],
            [
                'name' => 'Eka Putri',
                'password' => Hash::make('password'),
                'phone' => '081234567895',
                'role' => 'tenant',
            ],
        );
    }

    private function seedAdmin(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@natakos.test'],
            [
                'name' => 'Admin NATAKOS',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'role' => 'admin',
            ],
        );
    }

    private function seedRooms(): void
    {
        $today = Carbon::today();
        $getFacilityId = fn (string $name, string $type) => Facility::where('name', $name)->where('type', $type)->value('id');

        $roomData = [
            [
                'name' => 'Kamar A3',
                'slug' => 'kamar-a3',
                'price' => 850000,
                'size' => '3x4 meter',
                'floor' => '1',
                'description' => 'Kamar ekonomis dengan fasilitas lengkap untuk kebutuhan harian.',
                'status' => 'available',
                'facilities' => [
                    ['name' => 'Kasur', 'type' => 'room'],
                    ['name' => 'Meja Belajar', 'type' => 'room'],
                    ['name' => 'Kamar Mandi Dalam', 'type' => 'room'],
                    ['name' => 'AC', 'type' => 'room'],
                    ['name' => 'WiFi', 'type' => 'public'],
                ],
            ],
            [
                'name' => 'Kamar B1',
                'slug' => 'kamar-b1',
                'price' => 1200000,
                'size' => '4x4 meter',
                'floor' => '2',
                'description' => 'Kamar luas dengan pemandangan taman, cocok untuk pekerja kantoran.',
                'status' => 'occupied',
                'facilities' => [
                    ['name' => 'Kasur', 'type' => 'room'],
                    ['name' => 'Lemari', 'type' => 'room'],
                    ['name' => 'Meja Belajar', 'type' => 'room'],
                    ['name' => 'Kamar Mandi Dalam', 'type' => 'room'],
                    ['name' => 'AC', 'type' => 'room'],
                    ['name' => 'TV', 'type' => 'room'],
                    ['name' => 'WiFi', 'type' => 'public'],
                ],
            ],
            [
                'name' => 'Kamar B2',
                'slug' => 'kamar-b2',
                'price' => 1100000,
                'size' => '4x3 meter',
                'floor' => '2',
                'description' => 'Kamar medium dengan akses cepat ke tangga dan area parkir.',
                'status' => 'occupied',
                'facilities' => [
                    ['name' => 'Kasur', 'type' => 'room'],
                    ['name' => 'Kamar Mandi Dalam', 'type' => 'room'],
                    ['name' => 'AC', 'type' => 'room'],
                    ['name' => 'TV', 'type' => 'room'],
                    ['name' => 'WiFi', 'type' => 'public'],
                    ['name' => 'Parkir Motor', 'type' => 'public'],
                ],
            ],
            [
                'name' => 'Kamar C1',
                'slug' => 'kamar-c1',
                'price' => 950000,
                'size' => '3x4 meter',
                'floor' => '2',
                'description' => 'Kamar tenang di sudut, jauh dari kebisingan jalan raya.',
                'status' => 'available',
                'facilities' => [
                    ['name' => 'Kasur', 'type' => 'room'],
                    ['name' => 'Meja Belajar', 'type' => 'room'],
                    ['name' => 'Kamar Mandi Dalam', 'type' => 'room'],
                    ['name' => 'Kipas Angin', 'type' => 'room'],
                    ['name' => 'WiFi', 'type' => 'public'],
                ],
            ],
            [
                'name' => 'Kamar C2',
                'slug' => 'kamar-c2',
                'price' => 1000000,
                'size' => '4x3 meter',
                'floor' => '2',
                'description' => 'Kamar favorit dengan sirkulasi udara yang baik.',
                'status' => 'occupied',
                'facilities' => [
                    ['name' => 'Spring Bed', 'type' => 'room'],
                    ['name' => 'Lemari', 'type' => 'room'],
                    ['name' => 'Kamar Mandi Dalam', 'type' => 'room'],
                    ['name' => 'AC', 'type' => 'room'],
                    ['name' => 'WiFi', 'type' => 'public'],
                    ['name' => 'Dapur Bersama', 'type' => 'public'],
                ],
            ],
            [
                'name' => 'Kamar VIP',
                'slug' => 'kamar-vip',
                'price' => 2500000,
                'size' => '5x5 meter',
                'floor' => '3',
                'description' => 'Kamar premium dengan fasilitas lengkap dan pemandangan kota.',
                'status' => 'occupied',
                'facilities' => [
                    ['name' => 'Spring Bed', 'type' => 'room'],
                    ['name' => 'Lemari', 'type' => 'room'],
                    ['name' => 'Meja Belajar', 'type' => 'room'],
                    ['name' => 'Kamar Mandi Dalam', 'type' => 'room'],
                    ['name' => 'AC', 'type' => 'room'],
                    ['name' => 'TV', 'type' => 'room'],
                    ['name' => 'Kipas Angin', 'type' => 'room'],
                    ['name' => 'CCTV 24 Jam', 'type' => 'public'],
                    ['name' => 'WiFi', 'type' => 'public'],
                    ['name' => 'Parkir Motor', 'type' => 'public'],
                ],
            ],
            [
                'name' => 'Kamar Akses',
                'slug' => 'kamar-akses',
                'price' => 650000,
                'size' => '2x3 meter',
                'floor' => '1',
                'description' => 'Kamar sederhana dengan harga terjangkau, dalam perbaikan.',
                'status' => 'maintenance',
                'facilities' => [
                    ['name' => 'Kasur', 'type' => 'room'],
                    ['name' => 'Kamar Mandi Dalam', 'type' => 'room'],
                ],
            ],
            [
                'name' => 'Kamar D1',
                'slug' => 'kamar-d1',
                'price' => 700000,
                'size' => '3x3 meter',
                'floor' => '1',
                'description' => 'Kamar kecil yang nyaman untuk mahasiswa atau pekerja single.',
                'status' => 'available',
                'facilities' => [
                    ['name' => 'Kasur', 'type' => 'room'],
                    ['name' => 'Lemari', 'type' => 'room'],
                    ['name' => 'Meja Belajar', 'type' => 'room'],
                    ['name' => 'WiFi', 'type' => 'public'],
                ],
            ],
        ];

        foreach ($roomData as $data) {
            $room = Room::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'price' => $data['price'],
                    'size' => $data['size'],
                    'floor' => $data['floor'],
                    'description' => $data['description'],
                    'status' => $data['status'],
                ],
            );

            if (! $room->wasRecentlyCreated) {
                continue;
            }

            $facilityIds = [];
            foreach ($data['facilities'] as $fac) {
                $id = Facility::where('name', $fac['name'])->where('type', $fac['type'])->value('id');
                if ($id !== null) {
                    $facilityIds[] = $id;
                }
            }
            $room->facilities()->sync($facilityIds);
        }
    }

    private function seedTenants(): void
    {
        $today = Carbon::today();

        $tenantData = [
            // Andi → B2, no end date (no_end_date)
            [
                'user_email' => 'andi@example.com',
                'room_slug' => 'kamar-b2',
                'start_date' => (clone $today)->subDays(30),
                'end_date' => null,
                'status' => 'active',
            ],
            // Budi → B1, end +10 days (safe)
            [
                'user_email' => 'budi@example.com',
                'room_slug' => 'kamar-b1',
                'start_date' => (clone $today)->subDays(15),
                'end_date' => (clone $today)->addDays(10),
                'status' => 'active',
            ],
            // Citra → C2, end +3 days (ending_soon)
            [
                'user_email' => 'citra@example.com',
                'room_slug' => 'kamar-c2',
                'start_date' => (clone $today)->subDays(30),
                'end_date' => (clone $today)->addDays(3),
                'status' => 'active',
            ],
            // Dedi → VIP, end today (ends_today)
            [
                'user_email' => 'dedi@example.com',
                'room_slug' => 'kamar-vip',
                'start_date' => (clone $today)->subDays(10),
                'end_date' => (clone $today),
                'status' => 'active',
            ],
            // Eka → A3, end yesterday (ended) — still active
            [
                'user_email' => 'eka@example.com',
                'room_slug' => 'kamar-a3',
                'start_date' => (clone $today)->subDays(60),
                'end_date' => (clone $today)->subDay(),
                'status' => 'active',
            ],
        ];

        foreach ($tenantData as $data) {
            $user = User::where('email', $data['user_email'])->first();
            $room = Room::where('slug', $data['room_slug'])->first();

            if ($user === null || $room === null) {
                continue;
            }

            Tenant::firstOrCreate(
                ['user_id' => $user->id, 'room_id' => $room->id],
                [
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'status' => $data['status'],
                ],
            );
        }
    }

    private function seedPayments(): void
    {
        $today = Carbon::today();

        $paymentData = [
            // Andi — unpaid, due +10 (safe deadline)
            [
                'tenant_user' => 'andi@example.com',
                'amount' => 1100000,
                'period_start' => '2026-06-01',
                'period_end' => '2026-06-30',
                'due_date' => (clone $today)->addDays(10),
                'status' => 'unpaid',
            ],
            // Budi — paid
            [
                'tenant_user' => 'budi@example.com',
                'amount' => 1200000,
                'period_start' => '2026-05-01',
                'period_end' => '2026-05-31',
                'due_date' => '2026-05-15',
                'status' => 'paid',
                'paid_at' => '2026-05-14 10:00:00',
                'verified_at' => '2026-05-14 10:30:00',
                'verified_by_email' => 'admin@natakos.test',
            ],
            // Budi — unpaid, due +3 (due_soon)
            [
                'tenant_user' => 'budi@example.com',
                'amount' => 1200000,
                'period_start' => '2026-06-01',
                'period_end' => '2026-06-30',
                'due_date' => (clone $today)->addDays(3),
                'status' => 'unpaid',
            ],
            // Citra — rejected
            [
                'tenant_user' => 'citra@example.com',
                'amount' => 1000000,
                'period_start' => '2026-04-01',
                'period_end' => '2026-04-30',
                'due_date' => '2026-04-25',
                'status' => 'rejected',
                'rejection_reason' => 'Bukti transfer tidak terbaca, harap upload ulang.',
                'verified_at' => '2026-04-28 14:00:00',
                'verified_by_email' => 'admin@natakos.test',
            ],
            // Citra — pending_verification, due -1 (overdue)
            [
                'tenant_user' => 'citra@example.com',
                'amount' => 1000000,
                'period_start' => '2026-05-01',
                'period_end' => '2026-05-31',
                'due_date' => (clone $today)->subDay(),
                'status' => 'pending_verification',
            ],
            // Dedi — unpaid, due today (due_today)
            [
                'tenant_user' => 'dedi@example.com',
                'amount' => 2500000,
                'period_start' => '2026-06-01',
                'period_end' => '2026-06-30',
                'due_date' => (clone $today),
                'status' => 'unpaid',
            ],
            // Eka — paid (previous period)
            [
                'tenant_user' => 'eka@example.com',
                'amount' => 850000,
                'period_start' => '2026-04-01',
                'period_end' => '2026-04-30',
                'due_date' => '2026-04-15',
                'status' => 'paid',
                'paid_at' => '2026-04-14 09:00:00',
                'verified_at' => '2026-04-14 09:30:00',
                'verified_by_email' => 'admin@natakos.test',
            ],
            // Eka — unpaid, due -2 (overdue)
            [
                'tenant_user' => 'eka@example.com',
                'amount' => 850000,
                'period_start' => '2026-05-01',
                'period_end' => '2026-05-31',
                'due_date' => (clone $today)->subDays(2),
                'status' => 'unpaid',
            ],
        ];

        $admin = User::where('email', 'admin@natakos.test')->first();

        foreach ($paymentData as $data) {
            $user = User::where('email', $data['tenant_user'])->first();
            if ($user === null) {
                continue;
            }

            $tenant = Tenant::where('user_id', $user->id)->first();
            if ($tenant === null) {
                continue;
            }

            Payment::firstOrCreate(
                ['tenant_id' => $tenant->id, 'period_start' => $data['period_start'], 'period_end' => $data['period_end']],
                [
                    'amount' => $data['amount'],
                    'due_date' => $data['due_date'],
                    'status' => $data['status'],
                    'paid_at' => $data['paid_at'] ?? null,
                    'verified_at' => $data['verified_at'] ?? null,
                    'verified_by' => ($data['verified_by_email'] ?? null) !== null && $admin !== null ? $admin->id : null,
                    'rejection_reason' => $data['rejection_reason'] ?? null,
                ],
            );
        }
    }
}
