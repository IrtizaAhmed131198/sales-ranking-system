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
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['user_id', 'role_id']);
        });

        // Migrate existing data
        $usersWithRoles = DB::table('users')->whereNotNull('role_id')->get();
        foreach ($usersWithRoles as $user) {
            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $user->role_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Drop the role_id column from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
        });

        // Restore data
        $roleUsers = DB::table('role_user')->get();
        foreach ($roleUsers as $ru) {
            DB::table('users')->where('id', $ru->user_id)->update(['role_id' => $ru->role_id]);
        }

        Schema::dropIfExists('role_user');
    }
};
