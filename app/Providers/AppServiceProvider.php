<?php

namespace App\Providers;

use App\Models\KosProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'id', 'Indonesian');

        View::composer('*', function ($view) {
            try {
                $kos = KosProfile::query()->first();
                $view->with('kosName', $kos?->name ?: 'Ichikos');
            } catch (\Throwable) {
                $view->with('kosName', 'Ichikos');
            }
        });
    }
}
