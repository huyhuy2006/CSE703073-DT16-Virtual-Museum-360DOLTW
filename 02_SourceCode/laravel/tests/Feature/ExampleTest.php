<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_homepage_redirects_to_vietnamese_museum_list(): void
    {
        $response = $this->get('/');

        $response
            ->assertStatus(302)
            ->assertRedirect(route('museums.index', ['lang' => 'vi']));
    }

    public function test_museum_list_page_returns_successful_response(): void
    {
        $response = $this->get('/museums?lang=vi');

        $response->assertOk();
    }
}
