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
        Schema::create('media_news', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('m_name',100);
            $table->string('email',100);
            $table->text('logo')->nullable();
            $table->text('address');
            $table->string('phone_no',25);
            $table->string('ref_code',10);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_news');
    }
};
