<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'effect', 'requirement_type', 'requirement_value', 'description', 'is_active'])]
class Badge extends Model
{
    protected $table = 'badges';

    protected function casts(): array
    {
        return [
            'requirement_value' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badge')
            ->withPivot('is_selected', 'unlocked_at')
            ->withTimestamps();
    }

    public function isUnlockedFor(User $user): bool
    {
        if ($this->requirement_type === null) {
            return true;
        }

        $actual = match ($this->requirement_type) {
            'chat_messages' => ChatMessage::query()->where('user_id', $user->id)->count(),
            'payments_count' => Payment::query()
                ->whereHas('tenant', fn ($q) => $q->where('user_id', $user->id))
                ->where('status', 'paid')
                ->count(),
            'stay_days' => Tenant::query()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->value('end_date')
                ? now()->diffInDays(
                    Tenant::query()
                        ->where('user_id', $user->id)
                        ->where('status', 'active')
                        ->value('start_date')
                )
                : 0,
            default => 0,
        };

        return $actual >= ($this->requirement_value ?? 0);
    }

    public static function syncUnlockedFor(User $user): void
    {
        $badgeIds = self::query()
            ->where('is_active', true)
            ->get()
            ->filter(fn (Badge $badge) => $badge->isUnlockedFor($user))
            ->pluck('id');

        $existing = $user->badges()->pluck('badge_id');

        $newIds = $badgeIds->diff($existing);

        foreach ($newIds as $badgeId) {
            $user->badges()->attach($badgeId, [
                'is_selected' => false,
                'unlocked_at' => now(),
            ]);
        }
    }

    public static function effectOptions(): array
    {
        return [
            'none' => 'Tidak ada',
            'gold' => 'Emas',
            'rainbow' => 'Pelangi',
            'glow' => 'Cahaya',
            'fire' => 'Api',
            'neon' => 'Neon',
            'ocean' => 'Lautan',
            'sunset' => 'Senja',
            'galaxy' => 'Galaksi',
            'shadow' => 'Bayangan',
            'thunder' => 'Petir',
            'rose' => 'Mawar',
            'ice' => 'Es',
            'royal' => 'Kerajaan',
            'cyber' => 'Cyber',
        ];
    }
}
