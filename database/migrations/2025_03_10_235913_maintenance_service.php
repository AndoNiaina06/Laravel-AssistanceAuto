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
        Schema::create('maintenance_services', function (Blueprint $table) {
            $table->id();
            $table->integer('car_id')->unsigned();
            $table->string('typeservice', 100);
            $table->dateTime('dateservice');
            $table->integer('cout')->unsigned()->default(5000);
            $table->string('society', 100)->nullable()->default('Carglass');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_services', function (Blueprint $table) {
            $table->dropForeign(['car_id']);
        });
        Schema::dropIfExists('maintenance_services');
    }
};
