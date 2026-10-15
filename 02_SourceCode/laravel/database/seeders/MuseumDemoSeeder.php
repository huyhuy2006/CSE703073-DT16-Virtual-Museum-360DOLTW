<?php

namespace Database\Seeders;

use App\Models\License;
use App\Models\Museum;
use App\Models\MuseumTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MuseumDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $license = License::query()->updateOrCreate(
                ['code' => 'CC-BY-4.0'],
                [
                    'name' => 'Creative Commons Attribution 4.0 International',
                    'url' => 'https://creativecommons.org/licenses/by/4.0/',
                    'requires_attribution' => true,
                    'share_alike' => false,
                ]
            );

            $museum = Museum::query()->updateOrCreate(
                ['slug' => 'dt16-demo-museum'],
                [
                    'country_code' => 'VN',
                    'city' => 'Hà Nội',
                    'license_id' => $license->id,
                    'author_credit' => 'Dữ liệu minh họa cho dự án DT-16',
                    'source_url' => null,
                    'source_note' => 'Bảo tàng mẫu phục vụ kiểm thử dự án DT-16.',
                    'is_published' => true,
                    'sort_order' => 1,
                ]
            );

            $translations = [
                'vi' => [
                    'name' => 'Bảo tàng ảo DT-16 (dữ liệu mẫu)',
                    'tagline' => 'Khám phá bảo tàng trên nền tảng web',
                    'description' =>
                        'Không gian bảo tàng minh họa phục vụ phát triển và kiểm thử dự án DT-16.',
                ],
                'en' => [
                    'name' => 'DT-16 Virtual Museum (Demo)',
                    'tagline' => 'Explore a museum on the web',
                    'description' =>
                        'A demonstration museum for developing and testing the DT-16 project.',
                ],
            ];

            foreach ($translations as $locale => $content) {
                MuseumTranslation::query()->updateOrCreate(
                    [
                        'museum_id' => $museum->id,
                        'locale' => $locale,
                    ],
                    [
                        'name' => $content['name'],
                        'tagline' => $content['tagline'],
                        'description' => $content['description'],
                    ]
                );
            }

            $this->command?->info(
                "Created/updated demo museum: {$museum->slug}"
            );
            $this->command?->info(
                'Created/updated Vietnamese and English museum translations.'
            );
        });
    }
}