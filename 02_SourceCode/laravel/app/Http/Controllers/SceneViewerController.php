<?php

namespace App\Http\Controllers;

use App\Models\Museum;
use App\Models\Scene;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SceneViewerController extends Controller
{
    public function show(
        Request $request,
        string $museumSlug,
        string $sceneSlug
    ): View {
        $lang = $request->query('lang', 'vi');

        if (! in_array($lang, ['vi', 'en'], true)) {
            $lang = 'vi';
        }

        $museum = Museum::query()
            ->where('slug', $museumSlug)
            ->where('is_published', true)
            ->with([
                'translations' => function ($query) use ($lang) {
                    $query->where('locale', $lang);
                },
                'license',
            ])
            ->firstOrFail();

        $scene = Scene::query()
            ->where('museum_id', $museum->id)
            ->where('slug', $sceneSlug)
            ->where('is_published', true)
            ->with([
                'translations' => function ($query) use ($lang) {
                    $query->where('locale', $lang);
                },
                'hotspots' => function ($query) use ($lang) {
                    $query
                        ->whereHas('artifact', function ($artifactQuery) {
                            $artifactQuery->where('is_published', true);
                        })
                        ->with([
                            'artifact.translations' => function ($translationQuery) use ($lang) {
                                $translationQuery->where('locale', $lang);
                            },
                        ])
                        ->orderBy('sort_order');
                },
            ])
            ->firstOrFail();

        $sceneTranslation = $scene->translations->first();
        $museumTranslation = $museum->translations->first();

        $hotspots = $scene->hotspots
            ->map(function ($hotspot) {
                $artifact = $hotspot->artifact;
                $translation = $artifact?->translations->first();

                if (! $artifact || ! $translation) {
                    return null;
                }

                return [
                    'id' => $hotspot->id,
                    'yaw' => (float) $hotspot->yaw,
                    'pitch' => (float) $hotspot->pitch,
                    'icon' => $hotspot->icon,
                    'name' => $translation->name,
                    'summary' => $translation->summary,
                    'detail' => $translation->detail,
                    'image' => $artifact->image_path,
                    'period' => $artifact->period_label,
                    'origin' => $artifact->origin,
                ];
            })
            ->filter()
            ->values();

        $panoramaUrl = $scene->preview_path
            ? asset($scene->preview_path)
            : null;

        return view('scenes.viewer', [
            'museum' => $museum,
            'museumTranslation' => $museumTranslation,
            'scene' => $scene,
            'sceneTranslation' => $sceneTranslation,
            'lang' => $lang,
            'hotspots' => $hotspots,
            'panoramaUrl' => $panoramaUrl,
        ]);
    }
}
