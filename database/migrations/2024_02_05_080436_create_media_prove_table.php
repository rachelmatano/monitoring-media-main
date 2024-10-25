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
        Schema::create('media_prove', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title',100);
            $table->text('link');
            $table->enum('tipe',['Online','Printed','Both'])->default('Both');
            $table->uuid('project_id');
            $table->uuid('media_id');
            $table->uuid('reporter_id');
            $table->datetime('date_posted');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_prove');
    }
};
