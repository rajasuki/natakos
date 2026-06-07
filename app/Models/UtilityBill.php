<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['tenant_id', 'type', 'amount', 'period', 'due_date', 'status', 'paid_at', 'notes'])]
class UtilityBill extends Model
{
    protected $table = 'utility_bills';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'due_date' => 'date',
            'paid_at' => 'datetime',
        ];
    }
}
