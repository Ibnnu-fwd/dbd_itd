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
        Schema::create('villages', function (Blueprint $table) {
            $table->char('id', 10)->primary();
            $table->char('district_id', 7)->nullable();
            $table->string('name', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('districts');
        });

        Schema::table('samples', function (Blueprint $table) {
            $table->foreign('village_id')->references('id')->on('villages');
        });

        Schema::table('broods', function (Blueprint $table) {
            $table->foreign('village_id')->references('id')->on('villages');
        });

        Schema::table('ksh', function (Blueprint $table) {
            $table->foreign('village_id')->references('id')->on('villages');
        });

        Schema::table('abj', function (Blueprint $table) {
            $table->foreign('village_id')->references('id')->on('villages');
        });

        Schema::table('cases', function (Blueprint $table) {
            $table->foreign('village_id')->references('id')->on('villages');
        });

        Schema::table('environment_variables', function (Blueprint $table) {
            $table->foreign('village_id')->references('id')->on('villages');
        });

        Schema::table('larvae', function (Blueprint $table) {
            $table->foreign('village_id')->references('id')->on('villages');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villages');

        Schema::table('samples', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
        });

        Schema::table('broods', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
        });

        Schema::table('ksh', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
        });

        Schema::table('abj', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
        });

        Schema::table('cases', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
        });

        Schema::table('environment_variables', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
        });

        Schema::table('larvae', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
        });
    }
};
