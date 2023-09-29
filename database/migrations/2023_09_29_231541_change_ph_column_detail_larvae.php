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
        Schema::table('detail_larvae', function (Blueprint $table) {
            $table->double('ph', 8, 2)->nullable()->change();
            $table->text('detail_tpa')->nullable()->after('ph');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_larvae', function (Blueprint $table) {
            $table->integer('ph')->nullable()->change();
            $table->dropColumn('detail_tpa');
        });
    }
};
