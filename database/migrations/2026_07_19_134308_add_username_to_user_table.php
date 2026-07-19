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
            $table->string('username')->nullable()->after('name');
        });

        // Backfill existing users
        $users = \Illuminate\Support\Facades\DB::table('User')->get();
        foreach ($users as $user) {
            // Generate a simple slug from name + id to ensure uniqueness
            $baseSlug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '', str_replace(' ', '', $user->name)));
            if (empty($baseSlug)) {
                $baseSlug = 'user';
            }
            $username = $baseSlug . $user->user_id;
            
            \Illuminate\Support\Facades\DB::table('User')
                ->where('user_id', $user->user_id)
                ->update(['username' => $username]);
        }

        // Now make it unique
        Schema::table('User', function (Blueprint $table) {
            $table->unique('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('User', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
