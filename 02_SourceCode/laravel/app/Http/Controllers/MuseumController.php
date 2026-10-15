<?php

namespace App\Http\Controllers;

use App\Models\Museum;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MuseumController extends Controller
{
    /**
     * Hiển thị danh sách bảo tàng đã được xuất bản.
     */
    public function index(Request $request): View
    {
        $locale = $this->getLocale($request);

        $museums = Museum::query()
            ->where('is_published', true)
            ->with([
                'translations' => function ($query) use ($locale) {
                    $query->where('locale', $locale);
                },
            ])
            ->withCount([
                'scenes' => function ($query) {
                    $query->where('is_published', true);
                },
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('museums.index', [
            'museums' => $museums,
            'locale' => $locale,
        ]);
    }

    /**
     * Hiển thị thông tin chi tiết một bảo tàng.
     */
    public function show(Request $request, string $slug): View
    {
        $locale = $this->getLocale($request);

        $museum = Museum::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->with([
                'translations' => function ($query) use ($locale) {
                    $query->where('locale', $locale);
                },
                'scenes' => function ($query) use ($locale) {
                    $query->where('is_published', true)
                        ->with([
                            'translations' => function ($translationQuery) use ($locale) {
                                $translationQuery->where('locale', $locale);
                            },
                        ])
                        ->orderBy('sort_order')
                        ->orderBy('id');
                },
                'license',
            ])
            ->firstOrFail();

        return view('museums.show', [
            'museum' => $museum,
            'locale' => $locale,
        ]);
    }

    /**
     * Chỉ chấp nhận hai ngôn ngữ được hỗ trợ.
     */
    private function getLocale(Request $request): string
    {
        return in_array($request->query('lang'), ['vi', 'en'], true)
            ? $request->query('lang')
            : 'vi';
    }
}
