@extends('layouts.museum')

@section('title', $locale === 'vi' ? 'Chi tiết bảo tàng | DT-16' : 'Museum details | DT-16')

@section('styles')
<style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f8;
            color: #172033;
        }
        header {
            padding: 22px 6%;
            background: #142b4a;
            color: white;
        }
        header a { color: white; text-decoration: none; }
        main {
            max-width: 950px;
            margin: 32px auto;
            padding: 0 20px;
        }
        section {
            margin-bottom: 22px;
            padding: 24px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 14px rgba(15, 23, 42, .05);
        }
        .muted { color: #5d6878; line-height: 1.7; }
        .scene { padding: 18px 0; border-bottom: 1px solid #e3e7ed; }
        .scene:last-child { border-bottom: 0; }
        .button {
            display: inline-block;
            margin-top: 8px;
            padding: 11px 16px;
            background: #1456a0;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .button:hover { background: #0d3f78; }
        .language { margin-top: 12px; }
        .language a { margin-right: 14px; }
        footer {
            padding: 20px;
            color: #5d6878;
            text-align: center;
            font-size: 14px;
        }

</style>
@endsection

@section('content')

<header>
    <a href="{{ route('museums.index', ['lang' => $locale]) }}">
        ← {{ $locale === 'vi' ? 'Trở về danh sách bảo tàng' : 'Back to museums' }}
    </a>
    <div class="language">
        <a href="{{ route('museums.show', ['slug' => $museum->slug, 'lang' => 'vi']) }}">Tiếng Việt</a>
        <a href="{{ route('museums.show', ['slug' => $museum->slug, 'lang' => 'en']) }}">English</a>
    </div>
</header>

<main>
    <section>
        <h1>{{ $museum->translations->first()?->name ?? $museum->slug }}</h1>
        <p class="muted">
            {{ $museum->city }}@if ($museum->country_code) · {{ $museum->country_code }}@endif
        </p>
        <p class="muted">
            {{ $locale === 'vi' ? 'Khám phá bảo tàng ảo trên nền tảng web.' : 'Explore a virtual museum on the web.' }}
        </p>
    </section>

    <section>
        <h2>{{ $locale === 'vi' ? 'Các cảnh tham quan' : 'Museum scenes' }}</h2>

        @forelse ($museum->scenes as $scene)
            @php
                $sceneTranslation = $scene->translations->first();
            @endphp
            <article class="scene">
                <h3>{{ $sceneTranslation?->title ?? $scene->slug }}</h3>

                @if ($scene->floor_label)
                    <p class="muted">
                        {{ $locale === 'vi' ? 'Tầng' : 'Floor' }} {{ $scene->floor_label }}
                    </p>
                @endif

                @if ($sceneTranslation?->description)
                    <p class="muted">{{ $sceneTranslation->description }}</p>
                @endif

                <p class="muted">
                    {{ $locale === 'vi' ? 'Loại panorama:' : 'Panorama type:' }}
                    {{ $scene->panorama_type }}
                </p>

                <a class="button"
                   href="{{ route('scenes.viewer', [
                       'museumSlug' => $museum->slug,
                       'sceneSlug' => $scene->slug,
                       'lang' => $locale,
                   ]) }}">
                    {{ $locale === 'vi' ? 'Tham quan 360°' : 'Explore in 360°' }} →
                </a>
            </article>
        @empty
            <p class="muted">
                {{ $locale === 'vi' ? 'Bảo tàng chưa có cảnh tham quan được xuất bản.' : 'There are no published scenes in this museum yet.' }}
            </p>
        @endforelse
    </section>

    @if ($museum->license)
        <section>
            <h2>{{ $locale === 'vi' ? 'Thông tin giấy phép' : 'License information' }}</h2>
            <p class="muted">
                {{ $museum->license->name }}
                @if ($museum->license->url)
                    · <a href="{{ $museum->license->url }}" target="_blank" rel="noopener noreferrer">
                        {{ $locale === 'vi' ? 'Xem giấy phép' : 'View license' }}
                    </a>
                @endif
            </p>

            @if ($museum->author_credit)
                <p class="muted">
                    {{ $locale === 'vi' ? 'Ghi công tư liệu:' : 'Media attribution:' }}
                    {{ $museum->author_credit }}
                </p>
            @endif
        </section>
    @endif
</main>

<footer>
    DT-16 — {{ $locale === 'vi' ? 'Bảo tàng ảo và tour tham quan 360°' : 'Virtual Museum and 360° Tours' }}
</footer>

@endsection
