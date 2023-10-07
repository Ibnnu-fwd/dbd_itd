<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_cases', function (Blueprint $table) {
            $table->id();
            $table->integer('date');
            $table->string('vector_type'); // Ubah jenis data sesuai kebutuhan, misalnya enum atau varchar
            $table->integer('cases_total');
            $table->char('regency_id')->nullable();
            $table->char('district_id')->nullable();
            $table->char('village_id')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_cases');
    }
}
