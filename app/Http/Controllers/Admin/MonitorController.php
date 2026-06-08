<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class MonitorController extends Controller
{
    public function index(): View
    {
        $onlineUsers = User::query()
            ->whereRaw('last_seen_at >= NOW() - INTERVAL 5 MINUTE')
            ->orderByDesc('last_seen_at')
            ->get();

        $allUsers = User::query()
            ->orderByDesc('last_seen_at')
            ->paginate(30);

        return view('admin.monitor.index', [
            'onlineUsers' => $onlineUsers,
            'allUsers' => $allUsers,
            'onlineCount' => $onlineUsers->count(),
            'tenantCount' => User::query()->where('role', 'tenant')->count(),
            'adminCount' => User::query()->where('role', 'admin')->count(),
        ]);
    }
}
