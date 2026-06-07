<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->with('tenant.room')
            ->withCount('tenant')
            ->orderBy('role')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.users.index', [
            'users' => $users,
            'roleLabels' => $this->roleLabels(),
            'counts' => [
                'total' => User::query()->count(),
                'admin' => User::query()->where('role', 'admin')->count(),
                'tenant' => User::query()->where('role', 'tenant')->count(),
            ],
        ]);
    }

    private function roleLabels(): array
    {
        return [
            'admin' => 'Admin',
            'tenant' => 'Penghuni',
        ];
    }
}
