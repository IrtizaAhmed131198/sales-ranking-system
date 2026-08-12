<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Just in case it blocks the index drop
        });

        Schema::table('targets', function (Blueprint $table) {
            $table->dropUnique('targets_user_id_unique');
        });

        Schema::table('targets', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unique(['user_id', 'role_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // Automatically assign a role to existing targets
        $targets = DB::table('targets')->get();
        foreach ($targets as $target) {
            $roleUser = DB::table('role_user')->where('user_id', $target->user_id)->first();
            if ($roleUser) {
                DB::table('targets')->where('id', $target->id)->update(['role_id' => $roleUser->role_id]);
            } else {
                $firstRole = DB::table('roles')->first();
                if ($firstRole) {
                    DB::table('targets')->where('id', $target->id)->update(['role_id' => $firstRole->id]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'role_id']);
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            $table->unique('user_id');
        });
    }
};
