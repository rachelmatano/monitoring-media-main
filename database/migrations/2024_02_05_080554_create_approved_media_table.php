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
        Schema::create('approved_media', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('period',50);
            $table->uuid('media_id');
            $table->integer('printed_by_project')->default(0);
            $table->integer('printed_general')->default(0);
            $table->integer('online_by_project')->default(0);
            $table->integer('online_general')->default(0);
            $table->integer('printed_total')->default(0);
            $table->integer('online_total')->default(0);
            $table->string('created_by',100)->nullable();
            $table->string('updated_by',100)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approved_media');
    }
};
