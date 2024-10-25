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
        Schema::create('prove_gallery', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('prove_id');
            $table->text('link_path');
            $table->enum('tipe',['Video','Image'])->default('Image');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prove_gallery');
    }
};
