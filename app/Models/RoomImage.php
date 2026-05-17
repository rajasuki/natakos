<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['room_id', 'image_path', 'caption', 'sort_order'])]
class RoomImage extends Model
{
    use HasFactory;

    protected $table = 'room_images';

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }
}
