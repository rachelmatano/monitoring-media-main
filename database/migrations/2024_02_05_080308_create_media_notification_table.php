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
        Schema::create('media_notification', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->datetime('notif_time');
            $table->string('title','100');
            $table->text('content');
            $table->enum('category',['project','information'])->default('project');
            $table->enum('status',['read','unread'])->default('unread');
            $table->enum('tipe',['private','public'])->default('public');
            $table->uuid('media_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_notification');
    }
};
