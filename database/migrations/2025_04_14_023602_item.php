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
        Schema::create('items', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name', 255);
                $table->decimal('price', 8,2);
                $table->integer('stock', 11);
                $table->timestamp('created_at');
                $table->timestamp('updated_at');
                $table->text('image');
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
