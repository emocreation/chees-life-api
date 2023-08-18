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
        Schema::table('customer_histories', function (Blueprint $table) {
            $table->enum('report', ['email', 'whatsapp', 'post'])->change();
            $table->enum('id_type', ['hkid', 'passport', 'other'])->after('birthday');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_histories', function (Blueprint $table) {
            $table->dropColumn('id_type');
        });
    }
};
