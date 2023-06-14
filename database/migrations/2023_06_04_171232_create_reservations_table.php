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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            // owner or admin or hakam
            
            // $table->foreignId('owner_id')
            // ->nullable()
            // ->constrained('owners')
            // ->nullOnDelete();

            $table->foreignId('cafe_id')
            ->nullable()
            ->constrained('cafes')
            ->nullOnDelete();

            $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();


            $table->string('start_date');
            $table->string('end_date');
            $table->string('per_day');
            $table->string('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
