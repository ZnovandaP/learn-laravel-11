<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_custom_config(): void
    {
        $authorName = config('contoh.author.first_name') . " " . config('contoh.author.last_name');
        self::assertEquals('John Doe', $authorName);
    }
}
