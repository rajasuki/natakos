<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        $logs = ActivityLog::query()
            ->with('user')
            ->latest('id')
            ->paginate(50);

        return view('admin.logs.index', [
            'logs' => $logs,
        ]);
    }
}
