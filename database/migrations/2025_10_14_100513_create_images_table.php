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
    Schema::create('images', function (Blueprint $table) {
      $table->id();
      $table->morphs('imageable'); // automatic generates imageable_id and imageable_type columns
      $table->string('url');
      $table->timestamps();
      $table->unique(['imageable_id', 'imageable_type']); // to ensure one-to-one polymorphic relation
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('images');
  }
};
