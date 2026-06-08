<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['title', 'content', 'is_active', 'scroll_speed', 'announcement_sound_id', 'has_sound'])]
class Announcement extends Model
{
    protected $table = 'announcements';

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'scroll_speed' => 'integer',
            'has_sound' => 'boolean',
        ];
    }

    public function sound(): BelongsTo
    {
        return $this->belongsTo(AnnouncementSound::class, 'announcement_sound_id');
    }
}
