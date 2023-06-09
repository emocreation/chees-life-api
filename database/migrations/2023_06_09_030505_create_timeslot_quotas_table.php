<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('timeslot_quotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('timeslot_id')->constrained()->cascadeOnDelete();
            $table->time('from');
            $table->time('to');
            $table->unsignedInteger('quota');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timeslot_quotas');
    }
};
