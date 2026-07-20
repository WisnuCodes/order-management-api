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
        Schema::table('User', function (Blueprint $table) {
            if (!Schema::hasColumn('User', 'otp_code')) {
                $table->string('otp_code', 6)->nullable()->after('email');
            }
            if (!Schema::hasColumn('User', 'otp_expires_at')) {
                $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            }
            if (!Schema::hasColumn('User', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('otp_expires_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('User', function (Blueprint $table) {
            if (Schema::hasColumn('User', 'otp_code')) {
                $table->dropColumn('otp_code');
            }
            if (Schema::hasColumn('User', 'otp_expires_at')) {
                $table->dropColumn('otp_expires_at');
            }
            if (Schema::hasColumn('User', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
        });
    }
};
