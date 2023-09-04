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
        Schema::create('latest_news_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('latest_news_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 2)->index();
            $table->string('title');
            $table->string('introduction')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('latest_news_translations');
    }
};
