<?php

namespace App\Support;

use App\Models\ActivityLog;

class ActivityLogger
{
    public static function log(string $action, ?string $entityType = null, ?int $entityId = null, ?string $description = null): void
    {
        ActivityLog::log($action, $entityType, $entityId, $description);
    }

    public static function created(string $entityType, int $entityId, string $label): void
    {
        self::log('created', $entityType, $entityId, "Membuat {$entityType}: {$label}");
    }

    public static function updated(string $entityType, int $entityId, string $label): void
    {
        self::log('updated', $entityType, $entityId, "Memperbarui {$entityType}: {$label}");
    }

    public static function deleted(string $entityType, int $entityId, string $label): void
    {
        self::log('deleted', $entityType, $entityId, "Menghapus {$entityType}: {$label}");
    }

    public static function approved(string $entityType, int $entityId, string $label): void
    {
        self::log('approved', $entityType, $entityId, "Menyetujui {$entityType}: {$label}");
    }

    public static function rejected(string $entityType, int $entityId, string $label): void
    {
        self::log('rejected', $entityType, $entityId, "Menolak {$entityType}: {$label}");
    }
}
