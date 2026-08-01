<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('benchmarks', function (Blueprint $table) {

            $table->text('front_sale_text')->nullable()->after('front_sale_value');
            $table->text('upsell_text')->nullable()->after('upsell_value');

            $table->string('front_sale_logo')->nullable()->after('front_sale_text');
            $table->string('front_sale_background')->nullable()->after('front_sale_logo');

            $table->string('upsell_logo')->nullable()->after('upsell_text');
            $table->string('upsell_background')->nullable()->after('upsell_logo');

        });
    }

    public function down(): void
    {
        Schema::table('benchmarks', function (Blueprint $table) {

            $table->dropColumn([
                'front_sale_text',
                'upsell_text',
                'front_sale_logo',
                'front_sale_background',
                'upsell_logo',
                'upsell_background',
            ]);

        });
    }
};
