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
    Schema::create('storages', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('code');
        
        $table->foreignId('storage_system_id') 
              ->constrained('storage_systems')
              ->onDelete('cascade');
        
        $table->integer('row')->default(0);
        $table->integer('column')->default(0);
        $table->integer('level')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storages');
    }
};
