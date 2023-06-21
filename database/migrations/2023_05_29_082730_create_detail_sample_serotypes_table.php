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
        Schema::create('detail_sample_serotypes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_id')->constrained('samples');
            $table->foreignId('serotype_id')->constrained('serotypes');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_sample_serotypes');
    }
};
