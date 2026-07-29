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
        Schema::table('benchmarks', function (Blueprint $table) {
            $table->dropUnique('benchmarks_name_unique');

            $table->enum('type', ['front_sale', 'upsell'])
                ->default('front_sale')
                ->after('name');

            $table->decimal('value', 12, 2)->default(0)->after('type');

            $table->unique(['name', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('benchmarks', function (Blueprint $table) {
            $table->dropUnique(['name', 'type']);
            $table->dropColumn(['type', 'value']);
            $table->unique('name');
        });
    }
};
