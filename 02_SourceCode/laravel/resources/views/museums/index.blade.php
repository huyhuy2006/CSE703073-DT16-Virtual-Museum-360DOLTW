
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $locale === 'vi' ? 'Bảo tàng ảo 360°' : 'Virtual Museum 360°' }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            color: #172033;
        }
        header {
            padding: 28px 6%;
            background: #142b4a;
            color: white;
        }
        header a { color: white; margin-right: 14px; }
        main { max-width: 1100px; margin: 32px auto; padding: 0 20px; }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }
        .card {
            padding: 22px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 14px #0000000d;
        }
        .card h2 { margin-top: 0; }
        .card a { color: #1456a0; font-weight: bold; }
        .muted { color: #5d6878; line-height: 1.6; }
        .empty {
            padding: 22px;
            background: white;
            border-radius: 12px;
        }
    </style>
</head>
<body>
<header>
    <h1>{{ $locale === 'vi' ? 'Bảo tàng ảo 360°' : 'Virtual Museum 360°' }}</h1>
    <p>
        {{ $locale === 'vi'
            ? 'Khám phá không gian và câu chuyện hiện vật.'
            : 'Explore museum spaces and artifact stories.' }}
    </p>
    <nav>
        <a href="{{ route('museums.index', ['lang' => 'vi']) }}">Tiếng Việt</a>
        <a href="{{ route('museums.index', ['lang' => 'en']) }}">English</a>
    </nav>
</header>

<main>
    <h2>{{ $locale === 'vi' ? 'Danh sách bảo tàng' : 'Museums' }}</h2>

    @if ($museums->isEmpty())
        <div class="empty">
            {{ $locale === 'vi'
                ? 'Chưa có bảo tàng được xuất bản. Hãy kiểm tra dữ liệu và bản dịch trong cơ sở dữ liệu.'
                : 'No published museums yet. Please check the database records and translations.' }}
        </div>
    @else
        <div class="grid">
            @foreach ($museums as $museum)
                @php
                    $translation = $museum->translations->first();
                @endphp

                <article class="card">
                    <h2>
                        {{ $translation?->name ?? $museum->slug }}
                    </h2>

                    @if ($translation?->tagline)
                        <p class="muted">{{ $translation->tagline }}</p>
                    @endif

                    @if ($museum->city)
                        <p class="muted">
                            {{ $museum->city }}
                            @if ($museum->country_code)
                                · {{ $museum->country_code }}
                            @endif
                        </p>
                    @endif

                    <p class="muted">
                        {{ $locale === 'vi' ? 'Không gian tham quan' : 'Published scenes' }}:
                        {{ $museum->scenes_count }}
                    </p>

                    <a href="{{ route('museums.show', [
                        'slug' => $museum->slug,
                        'lang' => $locale
                    ]) }}">
                        {{ $locale === 'vi' ? 'Xem chi tiết →' : 'View details →' }}
                    </a>
                </article>
            @endforeach
        </div>
    @endif
</main>
</body>
</html>
