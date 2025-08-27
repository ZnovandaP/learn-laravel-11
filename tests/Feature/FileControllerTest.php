<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileControllerTest extends TestCase
{
  /**
   * A basic feature test example.
   */
  public function testStoreFile(): void
  {
    Storage::put('test.txt', 'Hello, World!');
    $this->assertTrue(Storage::exists('test.txt'));
    $content = Storage::get('test.txt');
    $this->assertEquals('Hello, World!', $content);
  }

  public function testUploadFile(): void
  {
    $mockFile = UploadedFile::fake()->image('test.jpg');
    $response = $this->postJson(route('file.upload'), [
      'file' => $mockFile,
    ]);

    $response->assertStatus(200);
    $response->assertSeeText('File uploaded successfully: uploads/' . $mockFile->getClientOriginalName());
  }
}
