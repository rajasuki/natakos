<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'address', 'whatsapp_number', 'google_maps_url', 'logo'])]
class KosProfile extends Model
{
    use HasFactory;

    protected $table = 'kos_profiles';
}
