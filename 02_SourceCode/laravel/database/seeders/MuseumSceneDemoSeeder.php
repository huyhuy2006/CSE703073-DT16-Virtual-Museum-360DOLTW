<?php

namespace Database\Seeders;

use App\Models\Museum;
use App\Models\Scene;
use App\Models\SceneTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MuseumSceneDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $museum = Museum::query()
                ->where('slug', 'dt16-demo-museum')
                ->firstOrFail();

            $scene = Scene::query()->updateOrCreate(
                [
                    'museum_id' => $museum->id,
                    'slug' => 'sanh-chinh',
                ],
                [
                    'preview_path' => null,
                    'tile_path' => null,
                    'panorama_type' => 'equirectangular',
                    'width_px' => null,
                    'height_px' => null,
                    'file_size_kb' => null,
                    'initial_yaw' => 0,
                    'initial_pitch' => 0,
                    'initial_hfov' => 100,
                    'floor_label' => 'Tầng 1',
                    'map_x' => 50,
                    'map_y' => 50,
                    'sort_order' => 1,
                    'is_published' => true,
                ]
            );

            $translations = [
                'vi' => [
                    'title' => 'Sảnh chính',
                    'description' =>
                        'Cảnh tham quan mẫu của dự án DT-16. Ảnh panorama 360 độ sẽ được bổ sung sau.',
                ],
                'en' => [
                    'title' => 'Main Hall',
                    'description' =>
                        'A sample scene for the DT-16 project. The 360-degree panorama will be added later.',
                ],
            ];

            foreach ($translations as $locale => $content) {
                SceneTranslation::query()->updateOrCreate(
                    [
                        'scene_id' => $scene->id,
                        'locale' => $locale,
                    ],
                    [
                        'title' => $content['title'],
                        'description' => $content['description'],
                    ]
                );
            }

            $this->command?->info(
                "Created/updated scene: {$scene->slug} (ID: {$scene->id})"
            );
            $this->command?->info(
                'Created/updated Vietnamese and English scene translations.'
            );
        });
    }
}