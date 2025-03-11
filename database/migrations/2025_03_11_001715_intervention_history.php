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
        Schema::create('intervention_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('intervention_id')->unsigned();
            $table->dateTime('dateintervention')->nullable();
            $table->string('society', 50)->nullable();
            $table->string('details', 255)->nullable();
            $table->foreign('intervention_id')->references('id')->on('interventions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intervention_histories', function (Blueprint $table) {
            $table->dropForeign(['intervention_id']);
        });
        Schema::dropIfExists('intervention_histories');
    }
};
