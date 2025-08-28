<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boda_bodas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // path to image
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boda_bodas');
    }
};
