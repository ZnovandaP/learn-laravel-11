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
    Schema::create('user_tests', function (Blueprint $table) {
      $table->id();
      $table->string('username', 100)->unique('user_tests_username_unique');
      $table->string('password', 100);
      $table->string('name', 100);
      $table->string('token', 100)->nullable()->unique('user_tests_token_unique');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('user_tests');
  }
};
