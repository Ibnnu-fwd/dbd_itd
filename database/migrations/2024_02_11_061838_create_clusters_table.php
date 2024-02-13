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
        Schema::create('clusters', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('province')->nullable();
            $table->string('regency')->nullable();
            $table->string('district')->nullable();
            $table->string('village')->nullable();
            $table->string('location_type')->nullable();
            $table->string('location_name')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->integer('aedes_aegypti')->nullable();
            $table->integer('aedes_albopictus')->nullable();
            $table->integer('culex')->nullable();
            $table->integer('morphotype_1')->nullable();
            $table->integer('morphotype_2')->nullable();
            $table->integer('morphotype_3')->nullable();
            $table->integer('morphotype_4')->nullable();
            $table->integer('morphotype_5')->nullable();
            $table->integer('morphotype_6')->nullable();
            $table->integer('morphotype_7')->nullable();
            $table->integer('denv_1')->nullable();
            $table->integer('denv_2')->nullable();
            $table->integer('denv_3')->nullable();
            $table->integer('denv_4')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clusters');
    }
};
