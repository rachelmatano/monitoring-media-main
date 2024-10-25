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
        Schema::create('reporter', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email');
            $table->string('name');
            $table->string('code',10);
            $table->enum('gender',['L','P'])->default('L');
            $table->string('phone_no',25);
            $table->text('photo')->nullable();
            $table->text('password');
            $table->date('dob');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reporter');
    }
};
