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
        Schema::create('customers', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name', 255);
                $table->text('address');
                $table->string('no_hp', 255);
                $table->integer('points', 20);
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
