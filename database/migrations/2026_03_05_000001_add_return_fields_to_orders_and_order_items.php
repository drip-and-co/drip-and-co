<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('return_reason')->nullable()->after('rstatus');
            $table->date('return_date')->nullable()->after('return_reason');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->date('return_date')->nullable()->after('canceled_date');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['return_reason', 'return_date']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('return_date');
        });
    }
};
