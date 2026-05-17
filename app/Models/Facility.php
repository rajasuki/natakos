<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'type', 'icon'])]
class Facility extends Model
{
    use HasFactory;

    protected $table = 'facilities';

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'facility_room')->withTimestamps();
    }
}
