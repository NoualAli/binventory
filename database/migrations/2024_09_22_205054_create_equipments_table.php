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
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number');
            $table->string('description')->nullable();
            $table->boolean('install_ad')->default(false);
            $table->boolean('repair')->default(true);
            $table->foreignId('created_by_id')->nullable();
            $table->foreignId('updated_by_id')->nullable();
            $table->foreignId('deleted_by_id')->nullable();
            $table->foreignId('agency_id')->nullable();
            $table->foreignId('category_id')->nullable();
            $table->timestamp('entered_at');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by_id')->on('users')->references('id')->onDelete('SET NULL')->onUpdate('cascade');
            $table->foreign('updated_by_id')->on('users')->references('id')->onDelete('SET NULL')->onUpdate('cascade');
            $table->foreign('deleted_by_id')->on('users')->references('id')->onDelete('SET NULL')->onUpdate('cascade');
            $table->foreign('agency_id')->on('agencies')->references('id')->onDelete('SET NULL')->onUpdate('cascade');
            $table->foreign('category_id')->on('categories')->references('id')->onDelete('SET NULL')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};