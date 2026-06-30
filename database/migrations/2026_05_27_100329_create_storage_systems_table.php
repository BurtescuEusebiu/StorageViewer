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
            Schema::create('storage_systems', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type')->default('rack');

                $table->float('pos_x')->default(0);
                $table->float('pos_y')->default(0); 
                $table->float('pos_z')->default(0);
                
                $table->integer('columns')->default(5);
                $table->integer('rows')->default(1);
                $table->integer('levels')->default(3);
                $table->float('spacing')->default(0.1);
                
                $table->float('size_x')->default(1.0);
                $table->float('size_y')->default(1.0);
                $table->float('size_z')->default(1.0);

                $table->integer('orientation')->default(0);
                
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_systems');
    }
};
