<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Museum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MuseumApiController extends Controller
{
    private function validatedQuery(Request $request): array|JsonResponse
    {
        $validator = Validator::make($request->query(), [
            'lang' => ['sometimes', 'string', 'in:vi,en'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:24'],
            'page' => ['sometimes', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Tham so truy van khong hop le.',
                'errors' => $validator->errors(),
            ], 422);
        }

        return [
            'lang' => $request->query('lang', 'vi'),
            'per_page' => (int) $request->query('per_page', 6),
            'page' => (int) $request->query('page', 1),
        ];
    }

    public function index(Request $request): JsonResponse
    {
        $query = $this->validatedQuery($request);

        if ($query instanceof JsonResponse) {
            return $query;
        }

        $locale = $query['lang'];

        $museums = Museum::query()
            ->where('is_published', true)
            ->with([
                'translations' => fn ($translationQuery) =>
                    $translationQuery->where('locale', $locale),
            ])
            ->withCount([
                'scenes' => fn ($sceneQuery) =>
                    $sceneQuery->where('is_published', true),
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate($query['per_page']);

        $data = $museums->getCollection()->map(function (Museum $museum) {
            $translation = $museum->translations->first();

            return [
                'slug' => $museum->slug,
                'country_code' => $museum->country_code,
                'city' => $museum->city,
                'name' => $translation?->name,
                'tagline' => $translation?->tagline,
                'description' => $translation?->description,
                'published_scenes_count' => $museum->scenes_count,
            ];
        })->values();

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $museums->currentPage(),
                'per_page' => $museums->perPage(),
                'last_page' => $museums->lastPage(),
                'total' => $museums->total(),
            ],
        ]);
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $query = $this->validatedQuery($request);

        if ($query instanceof JsonResponse) {
            return $query;
        }

        $locale = $query['lang'];

        $museum = Museum::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->with([
                'translations' => fn ($translationQuery) =>
                    $translationQuery->where('locale', $locale),
                'scenes' => fn ($sceneQuery) =>
                    $sceneQuery
                        ->where('is_published', true)
                        ->with([
                            'translations' => fn ($translationQuery) =>
                                $translationQuery->where('locale', $locale),
                        ])
                        ->orderBy('sort_order')
                        ->orderBy('id'),
                'license',
            ])
            ->first();

        if (! $museum) {
            return response()->json([
                'message' => 'Khong tim thay bao tang.',
            ], 404);
        }

        $translation = $museum->translations->first();

        return response()->json([
            'data' => [
                'slug' => $museum->slug,
                'country_code' => $museum->country_code,
                'city' => $museum->city,
                'lat' => $museum->lat,
                'lng' => $museum->lng,
                'name' => $translation?->name,
                'tagline' => $translation?->tagline,
                'description' => $translation?->description,
                'license' => $museum->license ? [
                    'code' => $museum->license->code,
                    'name' => $museum->license->name,
                    'url' => $museum->license->url,
                    'requires_attribution' => $museum->license->requires_attribution,
                    'share_alike' => $museum->license->share_alike,
                ] : null,
                'scenes' => $museum->scenes->map(function ($scene) {
                    $sceneTranslation = $scene->translations->first();

                    return [
                        'slug' => $scene->slug,
                        'title' => $sceneTranslation?->title,
                        'description' => $sceneTranslation?->description,
                        'panorama_type' => $scene->panorama_type,
                        'preview_url' => $scene->preview_path
                            ? asset($scene->preview_path)
                            : null,
                        'initial_yaw' => $scene->initial_yaw,
                        'initial_pitch' => $scene->initial_pitch,
                        'initial_hfov' => $scene->initial_hfov,
                    ];
                })->values(),
            ],
        ]);
    }
}
