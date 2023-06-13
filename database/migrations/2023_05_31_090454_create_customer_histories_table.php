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
        Schema::create('customer_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('gender', ['F', 'M']);
            $table->date('birthday');
            $table->string('hkid', 32);
            $table->string('tel');
            $table->string('email');
            $table->text('medical_record');
            $table->boolean('covid_diagnosed');
            $table->boolean('covid_close_contacts');
            $table->date('covid_date')->nullable();
            $table->unsignedFloat('height', 5);
            $table->unsignedFloat('weight', 5);
            $table->date('blood_date');
            $table->string('blood_time');
            $table->text('address');
            $table->enum('report', ['email', 'doctor']);
            $table->text('remark')->nullable();
            $table->boolean('paid')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_histories');
    }
};
