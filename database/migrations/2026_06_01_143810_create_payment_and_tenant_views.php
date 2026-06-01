<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payments') && Schema::hasTable('tenants')) {
            DB::statement("
                CREATE OR REPLACE VIEW payment_deadline_view AS
                SELECT
                    payments.id AS id,
                    payments.tenant_id AS tenant_id,
                    tenants.user_id AS user_id,
                    tenants.room_id AS room_id,
                    payments.amount AS amount,
                    payments.period_start AS period_start,
                    payments.period_end AS period_end,
                    payments.due_date AS due_date,
                    payments.paid_at AS paid_at,
                    payments.status AS status,
                    payments.proof_image AS proof_image,
                    payments.verified_at AS verified_at,
                    payments.verified_by AS verified_by,
                    TO_DAYS(payments.due_date) - TO_DAYS(CURDATE()) AS days_remaining,
                    CASE
                        WHEN payments.status = 'paid' THEN 'paid'
                        WHEN payments.due_date < CURDATE() THEN 'overdue'
                        WHEN payments.due_date = CURDATE() THEN 'due_today'
                        WHEN TO_DAYS(payments.due_date) - TO_DAYS(CURDATE()) BETWEEN 1 AND 5 THEN 'due_soon'
                        ELSE 'safe'
                    END AS deadline_status
                FROM payments
                JOIN tenants ON payments.tenant_id = tenants.id
            ");
        }

        if (Schema::hasTable('tenants')) {
            DB::statement("
                CREATE OR REPLACE VIEW tenant_end_date_view AS
                SELECT
                    tenants.id AS tenant_id,
                    tenants.user_id AS user_id,
                    tenants.room_id AS room_id,
                    tenants.start_date AS start_date,
                    tenants.end_date AS end_date,
                    tenants.status AS status,
                    TO_DAYS(tenants.end_date) - TO_DAYS(CURDATE()) AS days_until_end,
                    CASE
                        WHEN tenants.status != 'active' THEN 'inactive'
                        WHEN tenants.end_date IS NULL THEN 'no_end_date'
                        WHEN tenants.end_date < CURDATE() THEN 'ended'
                        WHEN tenants.end_date = CURDATE() THEN 'ends_today'
                        WHEN TO_DAYS(tenants.end_date) - TO_DAYS(CURDATE()) BETWEEN 1 AND 5 THEN 'ending_soon'
                        ELSE 'safe'
                    END AS rent_period_status
                FROM tenants
            ");
        }
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS payment_deadline_view');
        DB::statement('DROP VIEW IF EXISTS tenant_end_date_view');
    }
};
