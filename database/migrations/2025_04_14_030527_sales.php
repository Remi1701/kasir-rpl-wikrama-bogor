<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('invoice_number')->unique();
                $table->uuid('user_id');
                $table->uuid('customers_id')->nullable();
                $table->string('customer_name')->nullable();
                $table->jsonb('items_data');
                $table->decimal('total_amount', 20, 2);
                $table->decimal('payment_amount', 20, 2);
                $table->decimal('change_amount', 20, 2);
                $table->text('notes')->nullable();
                $table->timestamp('created_at');
                $table->timestamp('updated_at');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
