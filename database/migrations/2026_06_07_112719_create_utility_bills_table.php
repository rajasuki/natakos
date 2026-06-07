<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utility_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('type', 50); // water, electricity, internet
            $table->integer('amount');
            $table->string('period', 7); // YYYY-MM
            $table->date('due_date');
            $table->string('status', 30)->default('unpaid'); // unpaid, paid
            $table->datetime('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utility_bills');
    }
};
