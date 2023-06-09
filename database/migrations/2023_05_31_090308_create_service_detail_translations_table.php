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
        Schema::create('service_detail_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_detail_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 2)->index();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_detail_translations');
    }
};
