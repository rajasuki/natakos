<?php

namespace App\Support;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class UiFormatter
{
    public static function currency(int|float|string|null $amount): string
    {
        return 'Rp'.number_format((int) ($amount ?? 0), 0, ',', '.');
    }

    public static function date(mixed $value, string $format = 'd M Y', string $fallback = '-'): string
    {
        if ($value === null || $value === '') {
            return $fallback;
        }

        $date = $value instanceof CarbonInterface ? $value : Carbon::parse($value);

        return $date->translatedFormat($format);
    }

    public static function chatDateLabel(\Carbon\CarbonInterface $date): string
    {
        $today = now()->startOfDay();
        $target = $date->copy()->startOfDay();

        if ($target->equalTo($today)) {
            return 'Hari Ini';
        }

        if ($target->equalTo($today->copy()->subDay())) {
            return 'Kemarin';
        }

        return ucfirst($date->locale('id')->isoFormat('dddd, D MMMM Y'));
    }
}
