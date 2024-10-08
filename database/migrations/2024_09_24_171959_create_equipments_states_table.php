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
        Schema::create('equipments_states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id');
            $table->foreignId('user_id');
            $table->tinyInteger('state');
            $table->boolean('is_current');
            $table->timestamps();
            $table->foreign('equipment_id')->on('equipments')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments_states');
    }
};
