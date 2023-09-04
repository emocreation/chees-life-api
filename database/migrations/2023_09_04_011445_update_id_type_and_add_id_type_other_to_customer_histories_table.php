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
            $table->enum('id_type', ['hkid', 'cnid', 'passport', 'other'])->change();
            $table->enum('report', ['email', 'whatsapp', 'post', 'wechat'])->change();
            $table->enum('report_explanation', ['na', 'by_phone', 'by_appointment'])->default('na')->after('report');
            $table->string('id_type_other')->nullable()->after('id_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_histories', function (Blueprint $table) {
            $table->dropColumn(['id_type_other', 'report_explanation']);
        });
    }
};
