<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('contacts', function (Blueprint $table) {
      $table->id();
      $table->string('firstname', 100);
      $table->string('lastname', 100)->nullable();
      $table->string('email', 100)->nullable();
      $table->string('phone', 20)->nullable();
      $table->unsignedBigInteger('user_test_id');
      $table->timestamps();

      $table->foreign('user_test_id')->references('id')->on('user_tests')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('contacts');
  }
};
