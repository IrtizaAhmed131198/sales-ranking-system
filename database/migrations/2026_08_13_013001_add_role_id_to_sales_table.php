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
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained()->cascadeOnDelete();
        });

        // Automatically assign a role to existing sales
        $sales = DB::table('sales')->get();
        foreach ($sales as $sale) {
            $roleUser = DB::table('role_user')->where('user_id', $sale->user_id)->first();
            if ($roleUser) {
                DB::table('sales')->where('id', $sale->id)->update(['role_id' => $roleUser->role_id]);
            } else {
                $firstRole = DB::table('roles')->first();
                if ($firstRole) {
                    DB::table('sales')->where('id', $sale->id)->update(['role_id' => $firstRole->id]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
