<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function addCheck(
        string $table,
        string $name,
        string $expression
    ): void {
        DB::statement(
            "ALTER TABLE {$table} ADD CONSTRAINT {$name} CHECK ({$expression})"
        );
    }

    public function up(): void
    {
        // Gói vé tham quan
        Schema::create('ticket_plans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('museum_id')
                ->constrained('museums')
                ->cascadeOnDelete();

            $table->string('code', 60)->unique();
            $table->enum('tier', ['standard', 'premium']);
            $table->unsignedInteger('price_vnd');

            $table->boolean('includes_audio')->default(false);
            $table->boolean('includes_guided_tour')->default(false);
            $table->unsignedSmallInteger('valid_days')->default(1);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->unique(['museum_id', 'tier']);
        });

        // Đơn hàng
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->char('code', 12)->unique();

            $table->foreignId('user_id')
                ->constrained('users');

            $table->enum('status', [
                'cart',
                'pending_payment',
                'paid',
                'cancelled',
                'expired',
            ])->default('cart');

            $table->decimal('total_amount', 14, 2)->default(0);
            $table->char('currency', 3)->default('VND');
            $table->date('visit_date')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
        });

        // Các vé trong đơn hàng
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('ticket_plan_id')
                ->constrained('ticket_plans');

            $table->unsignedSmallInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->date('visit_date');

            $table->index(['ticket_plan_id', 'visit_date']);
        });

        $this->addCheck(
            'order_items',
            'chk_oi_qty',
            'quantity >= 1'
        );

        // Giao dịch thanh toán
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders');

            $table->string('gateway', 40)->default('sandbox');
            $table->string('txn_ref', 80)->unique();
            $table->decimal('amount', 14, 2);

            $table->enum('status', [
                'initiated',
                'paid',
                'failed',
                'refunded',
            ])->default('initiated');

            $table->dateTime('paid_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'paid_at']);
        });

        $this->addCheck(
            'payments',
            'chk_pay_amount',
            'amount >= 0'
        );
    }

    public function down(): void
    {
        foreach ([
            'payments',
            'order_items',
            'orders',
            'ticket_plans',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
