<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class Dt16ExtraArtifactsSeeder extends Seeder
{
    public function run(): void
    {
        $museum = DB::table('museums')
            ->where('slug', 'dt16-demo-museum')
            ->first();

        if (!$museum) {
            throw new RuntimeException('Khong tim thay bao tang dt16-demo-museum.');
        }

        $scene = DB::table('scenes')
            ->where('museum_id', $museum->id)
            ->where('slug', 'sanh-chinh')
            ->first();

        if (!$scene) {
            throw new RuntimeException('Khong tim thay scene sanh-chinh.');
        }

        $artifactColumns = Schema::getColumnListing('artifacts');
        $translationColumns = Schema::getColumnListing('artifact_translations');
        $hotspotColumns = Schema::getColumnListing('hotspots');

        foreach (['museum_id', 'code', 'is_published'] as $column) {
            if (!in_array($column, $artifactColumns, true)) {
                throw new RuntimeException("Bang artifacts thieu cot: {$column}");
            }
        }

        foreach (['artifact_id', 'locale', 'name', 'summary'] as $column) {
            if (!in_array($column, $translationColumns, true)) {
                throw new RuntimeException("Bang artifact_translations thieu cot: {$column}");
            }
        }

        foreach (['scene_id', 'artifact_id', 'yaw', 'pitch'] as $column) {
            if (!in_array($column, $hotspotColumns, true)) {
                throw new RuntimeException("Bang hotspots thieu cot: {$column}");
            }
        }

        $items = [
            ['02','Hiện vật minh họa 02','Demo Artifact 02','Mẫu hiện vật kiểm thử số 02.','Sample artifact for testing 02.'],
            ['03','Hiện vật minh họa 03','Demo Artifact 03','Mẫu hiện vật kiểm thử số 03.','Sample artifact for testing 03.'],
            ['04','Hiện vật minh họa 04','Demo Artifact 04','Mẫu hiện vật kiểm thử số 04.','Sample artifact for testing 04.'],
            ['05','Hiện vật minh họa 05','Demo Artifact 05','Mẫu hiện vật kiểm thử số 05.','Sample artifact for testing 05.'],
            ['06','Hiện vật minh họa 06','Demo Artifact 06','Mẫu hiện vật kiểm thử số 06.','Sample artifact for testing 06.'],
            ['07','Hiện vật minh họa 07','Demo Artifact 07','Mẫu hiện vật kiểm thử số 07.','Sample artifact for testing 07.'],
            ['08','Hiện vật minh họa 08','Demo Artifact 08','Mẫu hiện vật kiểm thử số 08.','Sample artifact for testing 08.'],
            ['09','Hiện vật minh họa 09','Demo Artifact 09','Mẫu hiện vật kiểm thử số 09.','Sample artifact for testing 09.'],
            ['10','Hiện vật minh họa 10','Demo Artifact 10','Mẫu hiện vật kiểm thử số 10.','Sample artifact for testing 10.'],
            ['11','Hiện vật minh họa 11','Demo Artifact 11','Mẫu hiện vật kiểm thử số 11.','Sample artifact for testing 11.'],
            ['12','Hiện vật minh họa 12','Demo Artifact 12','Mẫu hiện vật kiểm thử số 12.','Sample artifact for testing 12.'],
            ['13','Hiện vật minh họa 13','Demo Artifact 13','Mẫu hiện vật kiểm thử số 13.','Sample artifact for testing 13.'],
            ['14','Hiện vật minh họa 14','Demo Artifact 14','Mẫu hiện vật kiểm thử số 14.','Sample artifact for testing 14.'],
            ['15','Hiện vật minh họa 15','Demo Artifact 15','Mẫu hiện vật kiểm thử số 15.','Sample artifact for testing 15.'],
        ];

        $positions = [
            [-150,10],[-120,5],[-90,12],[-60,4],[-30,8],
            [0,15],[30,6],[60,12],[90,5],[120,10],
            [150,4],[180,8],[-75,-10],[45,-12],
        ];

        DB::transaction(function () use (
            $items, $positions, $museum, $scene,
            $artifactColumns, $translationColumns, $hotspotColumns
        ) {
            foreach ($items as $index => $item) {
                $code = 'DT16-EXTRA-' . $item[0];

                $artifact = DB::table('artifacts')
                    ->where('code', $code)
                    ->first();

                $artifactData = [
                    'museum_id' => $museum->id,
                    'code' => $code,
                    'is_published' => true,
                ];

                if (in_array('category', $artifactColumns, true)) {
                    $artifactData['category'] = 'demo';
                }
                if (in_array('period_label', $artifactColumns, true)) {
                    $artifactData['period_label'] = 'Dữ liệu minh họa';
                }
                if (in_array('origin', $artifactColumns, true)) {
                    $artifactData['origin'] = 'Chưa xác minh';
                }
                if (in_array('source_note', $artifactColumns, true)) {
                    $artifactData['source_note'] = 'Dữ liệu kiểm thử DT-16; chưa phải hồ sơ hiện vật đã xác minh.';
                }
                if (in_array('updated_at', $artifactColumns, true)) {
                    $artifactData['updated_at'] = now();
                }

                if ($artifact) {
                    DB::table('artifacts')
                        ->where('id', $artifact->id)
                        ->update($artifactData);

                    $artifactId = $artifact->id;
                } else {
                    if (in_array('created_at', $artifactColumns, true)) {
                        $artifactData['created_at'] = now();
                    }

                    $artifactId = DB::table('artifacts')
                        ->insertGetId($artifactData);
                }

                foreach ([
                    'vi' => ['name' => $item[1], 'summary' => $item[3]],
                    'en' => ['name' => $item[2], 'summary' => $item[4]],
                ] as $locale => $translation) {
                    $data = [
                        'artifact_id' => $artifactId,
                        'locale' => $locale,
                        'name' => $translation['name'],
                        'summary' => $translation['summary'],
                    ];

                    if (in_array('detail', $translationColumns, true)) {
                        $data['detail'] = $translation['summary']
                            . ' Noi dung minh hoa, can thay bang thong tin co nguon xac thuc.';
                    }
                    if (in_array('updated_at', $translationColumns, true)) {
                        $data['updated_at'] = now();
                    }

                    $oldTranslation = DB::table('artifact_translations')
                        ->where('artifact_id', $artifactId)
                        ->where('locale', $locale)
                        ->first();

                    if ($oldTranslation) {
                        DB::table('artifact_translations')
                            ->where('artifact_id', $artifactId)
                            ->where('locale', $locale)
                            ->update($data);
                    } else {
                        if (in_array('created_at', $translationColumns, true)) {
                            $data['created_at'] = now();
                        }

                        DB::table('artifact_translations')->insert($data);
                    }
                }

                $hotspotData = [
                    'scene_id' => $scene->id,
                    'artifact_id' => $artifactId,
                    'yaw' => $positions[$index][0],
                    'pitch' => $positions[$index][1],
                ];

                if (in_array('icon', $hotspotColumns, true)) {
                    $hotspotData['icon'] = 'artifact';
                }
                if (in_array('sort_order', $hotspotColumns, true)) {
                    $hotspotData['sort_order'] = $index + 2;
                }
                if (in_array('updated_at', $hotspotColumns, true)) {
                    $hotspotData['updated_at'] = now();
                }

                $oldHotspot = DB::table('hotspots')
                    ->where('scene_id', $scene->id)
                    ->where('artifact_id', $artifactId)
                    ->first();

                if ($oldHotspot) {
                    DB::table('hotspots')
                        ->where('id', $oldHotspot->id)
                        ->update($hotspotData);
                } else {
                    if (in_array('created_at', $hotspotColumns, true)) {
                        $hotspotData['created_at'] = now();
                    }

                    DB::table('hotspots')->insert($hotspotData);
                }
            }
        });

        $this->command->info('Da tao/cap nhat 14 hien vat minh hoa va hotspot.');
        $this->command->warn('Toa do hotspot la vi tri kiem thu, chua duoc can chinh theo anh panorama.');
    }
}