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
        Schema::create('ksh', function (Blueprint $table) {
            $table->id();
            $table->string('latitude');
            $table->string('longitude');
            $table->date('ksh_date');
            $table->string('ksh_time');
            $table->char('regency_id', 4)->nullable();
            $table->char('district_id', 7)->nullable();
            $table->char('village_id', 10)->nullable();
            $table->string('house_name');
            $table->string('house_owner');
            $table->foreignId('tpa_type_id')->nullable()->constrained('tpa_types');
            $table->integer('larva_positive');
            $table->integer('larva_negative');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('regency_id')->references('id')->on('regencies');
            $table->foreign('district_id')->references('id')->on('districts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ksh');
    }
};
