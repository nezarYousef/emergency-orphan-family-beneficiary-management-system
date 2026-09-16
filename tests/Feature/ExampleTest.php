<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_public_home_is_available_without_authentication(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Turn urgent needs into');
        $response->assertSee('Open the workspace');
    }
}
