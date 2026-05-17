<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'price', 'size', 'floor', 'description', 'status', 'main_image'])]
class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'facility_room')->withTimestamps();
    }

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'floor' => 'integer',
        ];
    }
}
