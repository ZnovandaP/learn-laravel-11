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
    Schema::create('histories', function (Blueprint $table) {
      $table->id();
      $table->morphs('historable'); // automatic generates historable_id and historable_type columns
      $table->json('attribute')->nullable();
      $table->string('description')->nullable();
      $table->string('action')->nullable();
      $table->unsignedBigInteger('created_by')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('histories');
  }
};
