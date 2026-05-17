<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['tenant_id', 'amount', 'period_start', 'period_end', 'due_date', 'paid_at', 'status', 'proof_image', 'verified_at', 'verified_by', 'notes'])]
class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'period_start' => 'date',
            'period_end' => 'date',
            'due_date' => 'date',
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }
}
