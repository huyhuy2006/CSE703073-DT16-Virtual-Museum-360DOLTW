<?php

namespace Tests\Feature;

use App\Models\Museum;
use Tests\TestCase;

class MuseumApiTest extends TestCase
{
    public function test_museum_index_returns_paginated_json(): void
    {
        $response = $this->getJson('/api/v1/museums');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'meta' => [
                    'current_page',
                    'per_page',
                    'last_page',
                    'total',
                ],
            ]);

        $this->assertIsArray($response->json('data'));
        $this->assertSame(6, $response->json('meta.per_page'));
    }

    public function test_museum_index_accepts_supported_locale_and_page_size(): void
    {
        $response = $this->getJson('/api/v1/museums?lang=en&per_page=3');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'meta' => [
                    'current_page',
                    'per_page',
                    'last_page',
                    'total',
                ],
            ]);

        $this->assertSame(3, $response->json('meta.per_page'));
    }

    public function test_museum_index_rejects_invalid_query_parameters(): void
    {
        $this->getJson('/api/v1/museums?lang=fr')
            ->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);

        $this->getJson('/api/v1/museums?per_page=25')
            ->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);

        $this->getJson('/api/v1/museums?page=0')
            ->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);
    }

    public function test_published_museum_detail_returns_json_and_published_scenes(): void
    {
        // Doc du lieu hien co; chi doc du lieu, khong tao ban ghi moi.
        $museum = Museum::query()
            ->where('is_published', true)
            ->orderBy('id')
            ->first();

        if (! $museum) {
            $this->markTestSkipped(
                'Chua co bao tang duoc xuat ban trong co so du lieu.'
            );
        }

        $response = $this->getJson(
            '/api/v1/museums/' . $museum->slug . '?lang=vi'
        );

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'slug',
                    'country_code',
                    'city',
                    'name',
                    'tagline',
                    'description',
                    'license',
                    'scenes',
                ],
            ]);

        $this->assertSame($museum->slug, $response->json('data.slug'));

        $returnedScenes = $response->json('data.scenes');
        $this->assertIsArray($returnedScenes);

        foreach ($returnedScenes as $scene) {
            $this->assertArrayHasKey('slug', $scene);
            $this->assertArrayHasKey('panorama_type', $scene);
        }
    }

    public function test_missing_museum_returns_json_404(): void
    {
        $response = $this->getJson(
            '/api/v1/museums/dt16-museum-that-does-not-exist'
        );

        $response
            ->assertNotFound()
            ->assertJsonStructure(['message']);
    }
}