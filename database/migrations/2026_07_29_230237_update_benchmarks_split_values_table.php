<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('benchmarks', function (Blueprint $table) {
            if (!Schema::hasColumn('benchmarks', 'front_sale_value')) {
                $table->decimal('front_sale_value', 12, 2)->default(0)->after('name');
            }
            if (!Schema::hasColumn('benchmarks', 'upsell_value')) {
                $table->decimal('upsell_value', 12, 2)->default(0)->after('front_sale_value');
            }
            if (Schema::hasColumn('benchmarks', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('benchmarks', 'value')) {
                $table->dropColumn('value');
            }
        });

        // Add unique index only if it doesn't already exist
        $indexExists = collect(DB::select("SHOW INDEX FROM benchmarks WHERE Key_name = 'benchmarks_name_unique'"))->isNotEmpty();

        if (!$indexExists) {
            Schema::table('benchmarks', function (Blueprint $table) {
                $table->unique('name');
            });
        }
    }

    public function down(): void
    {
        Schema::table('benchmarks', function (Blueprint $table) {
            if (Schema::hasColumn('benchmarks', 'front_sale_value') || Schema::hasColumn('benchmarks', 'upsell_value')) {
                $table->dropUnique('benchmarks_name_unique');
                $table->dropColumn(['front_sale_value', 'upsell_value']);
            }

            $table->enum('type', ['front_sale', 'upsell'])->default('front_sale')->after('name');
            $table->decimal('value', 12, 2)->default(0)->after('type');
        });
    }
};
