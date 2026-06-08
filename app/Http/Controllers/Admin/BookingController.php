<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use App\Models\Payment;
use App\Models\Tenant;
use App\Support\ActivityLogger;
use App\Support\RoomOccupancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        return view('admin.booking-requests.index', [
            'bookingRequests' => BookingRequest::query()
                ->with(['user', 'room', 'processedBy'])
                ->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'approved' THEN 1 WHEN 'rejected' THEN 2 ELSE 3 END")
                ->orderByDesc('id')
                ->paginate(20),
            'statusLabels' => $this->statusLabels(),
            'counts' => [
                'pending' => BookingRequest::query()->where('status', 'pending')->count(),
                'approved' => BookingRequest::query()->where('status', 'approved')->count(),
                'rejected' => BookingRequest::query()->where('status', 'rejected')->count(),
            ],
        ]);
    }

    public function approve(Request $request, BookingRequest $booking): Redirector|RedirectResponse
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.bookings.index');
        }

        abort_if($booking->status !== 'pending', 404);

        $room = $booking->room;

        if ($room->status === 'maintenance') {
            return back()->with('error', 'Kamar sedang dalam perbaikan, tidak bisa menyetujui pengajuan ini.');
        }

        $activeTenantCount = Tenant::query()
            ->where('room_id', $room->id)
            ->where('status', 'active')
            ->count();

        $capacity = $room->capacity ?? 1;

        if ($activeTenantCount >= $capacity) {
            return back()->with('error', 'Kamar sudah mencapai kapasitas maksimal ('.$capacity.' orang).');
        }

        $tenant = Tenant::create([
            'user_id' => $booking->user_id,
            'room_id' => $booking->room_id,
            'start_date' => $booking->start_date,
            'end_date' => $booking->end_date,
            'status' => 'active',
            'notes' => $booking->notes,
        ]);

        Payment::create([
            'tenant_id' => $tenant->id,
            'amount' => $room->price,
            'period_start' => $booking->start_date,
            'period_end' => $booking->start_date->copy()->addMonth()->subDay(),
            'due_date' => $booking->start_date,
            'status' => 'unpaid',
        ]);

        $booking->update([
            'status' => 'approved',
            'approved_at' => now(),
            'processed_by' => request()->user()->id,
        ]);

        ActivityLogger::approved('pengajuan_sewa', $booking->id, 'Pengajuan dari '.($booking->user?->name ?? '#'));

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Pengajuan sewa disetujui. Penghuni dan tagihan pertama sudah dibuat.');
    }

    public function reject(Request $request, BookingRequest $booking): Redirector|RedirectResponse
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.bookings.index');
        }

        abort_if($booking->status !== 'pending', 404);

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $booking->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'processed_by' => $request->user()->id,
        ]);

        ActivityLogger::rejected('pengajuan_sewa', $booking->id, 'Pengajuan dari '.($booking->user?->name ?? '#'));

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Pengajuan sewa ditolak.');
    }

    public function destroy(BookingRequest $booking): RedirectResponse
    {
        abort_if($booking->status === 'approved', 403, 'Tidak bisa menghapus pengajuan yang sudah disetujui.');

        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Pengajuan sewa berhasil dihapus.');
    }

    /**
     * @return array<string, string>
     */
    private function statusLabels(): array
    {
        return [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ];
    }
}
