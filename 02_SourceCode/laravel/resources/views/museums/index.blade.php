@extends('layouts.museum')

@section('title', $locale === 'vi' ? 'Bảo tàng ảo 360° | DT-16' : 'Virtual Museum 360° | DT-16')

@section('styles')
<style>
        :root {
            color-scheme: light;
            --navy: #10243a;
            --navy-light: #193651;
            --gold: #c5a46d;
            --gold-dark: #9c793f;
            --paper: #f5f3ee;
            --white: #fff;
            --ink: #1c2b3b;
            --muted: #697584;
            --line: #e8e3d9;
            --shadow: 0 16px 42px rgba(16, 36, 58, .10);
        }

        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            background: var(--paper);
            color: var(--ink);
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; }

        .site-header {
            position: relative;
            z-index: 2;
            background: var(--navy);
            color: var(--white);
        }

        .nav-wrap {
            width: min(1240px, calc(100% - 48px));
            min-height: 82px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 13px;
            text-decoration: none;
        }

        .brand-mark {
            display: grid;
            place-items: center;
            width: 43px;
            height: 43px;
            border: 1px solid rgba(197, 164, 109, .8);
            border-radius: 50%;
            color: var(--gold);
            font-family: Georgia, serif;
            font-size: 20px;
        }

        .brand-name {
            display: block;
            font-size: 14px;
            font-weight: 750;
            letter-spacing: .13em;
        }

        .brand-subtitle {
            display: block;
            color: #c7d0d8;
            font-size: 11px;
            letter-spacing: .12em;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 22px;
        }

        .nav-link {
            color: #f4f1eb;
            font-size: 14px;
            text-decoration: none;
            transition: color .2s ease;
        }

        .nav-link:hover,
        .nav-link:focus-visible { color: var(--gold); }

        .languages {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 5px;
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 999px;
        }

        .language-link {
            padding: 5px 10px;
            border-radius: 999px;
            color: #dce3e9;
            font-size: 12px;
            text-decoration: none;
        }

        .language-link.active {
            background: var(--gold);
            color: var(--navy);
            font-weight: 750;
        }

        .hero {
            position: relative;
            isolation: isolate;
            display: flex;
            align-items: center;
            min-height: 510px;
            overflow: hidden;
            background: var(--navy);
            color: var(--white);
        }

        .hero::before {
            position: absolute;
            z-index: -2;
            inset: 0;
            content: "";
            background-image: url("/panoramas/dt16-demo-museum/sanh-chinh.png");
            background-position: center 47%;
            background-size: cover;
            transform: scale(1.015);
        }

        .hero::after {
            position: absolute;
            z-index: -1;
            inset: 0;
            content: "";
            background:
                linear-gradient(90deg, rgba(8, 22, 37, .94) 0%,
                    rgba(8, 22, 37, .78) 43%,
                    rgba(8, 22, 37, .28) 100%),
                linear-gradient(0deg, rgba(8, 22, 37, .42), transparent 48%);
        }

        .hero-inner {
            width: min(1240px, calc(100% - 48px));
            margin: 0 auto;
            padding: 76px 0 84px;
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0 0 22px;
            color: #e2c995;
            font-size: 11px;
            font-weight: 750;
            letter-spacing: .2em;
            text-transform: uppercase;
        }

        .eyebrow::before {
            width: 34px;
            height: 1px;
            background: var(--gold);
            content: "";
        }

        .hero h1 {
            max-width: 760px;
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(40px, 5.6vw, 72px);
            font-weight: 500;
            letter-spacing: -.035em;
            line-height: 1.1;
            text-wrap: balance;
        }

        .hero-copy {
            max-width: 560px;
            margin: 22px 0 0;
            color: #e0e6eb;
            font-size: 17px;
            line-height: 1.85;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 32px;
        }

        .button {
            display: inline-flex;
            min-height: 49px;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 20px;
            border: 1px solid transparent;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 750;
            text-decoration: none;
            transition: transform .2s ease, background .2s ease, border-color .2s ease;
        }

        .button:hover { transform: translateY(-2px); }

        .button-primary {
            background: var(--gold);
            color: var(--navy);
        }

        .button-primary:hover { background: #d6b982; }

        .button-secondary {
            border-color: rgba(255, 255, 255, .48);
            color: var(--white);
        }

        .button-secondary:hover { border-color: var(--gold); }

        .hero-note {
            margin: 24px 0 0;
            color: #d2d9df;
            font-size: 12px;
            letter-spacing: .04em;
        }

        .hero-note span { color: var(--gold); }

        .content {
            width: min(1240px, calc(100% - 48px));
            margin: 0 auto;
            padding: 76px 0 90px;
        }

        .section-heading {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 30px;
        }

        .section-kicker {
            margin: 0 0 8px;
            color: var(--gold-dark);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .18em;
            text-transform: uppercase;
        }

        .section-heading h2 {
            margin: 0;
            color: var(--navy);
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(30px, 4vw, 42px);
            font-weight: 500;
            letter-spacing: -.025em;
            line-height: 1.2;
        }

        .section-description {
            max-width: 390px;
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        .museum-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 320px), 1fr));
            gap: 26px;
        }

        .museum-card {
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 9px;
            background: var(--white);
            box-shadow: 0 7px 24px rgba(16, 36, 58, .045);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .museum-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow);
        }

        .museum-image {
            position: relative;
            min-height: 250px;
            overflow: hidden;
            background: var(--navy-light);
        }

        .museum-image img {
            display: block;
            width: 100%;
            height: 280px;
            object-fit: cover;
            object-position: center;
            transition: transform .5s ease;
        }

        .museum-card:hover .museum-image img { transform: scale(1.035); }

        .image-label {
            position: absolute;
            bottom: 16px;
            left: 16px;
            padding: 7px 11px;
            border: 1px solid rgba(255, 255, 255, .4);
            border-radius: 3px;
            background: rgba(16, 36, 58, .8);
            color: white;
            font-size: 11px;
            letter-spacing: .07em;
        }

        .museum-body { padding: 25px 25px 27px; }

        .museum-location {
            margin: 0 0 10px;
            color: var(--gold-dark);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .museum-title {
            margin: 0;
            color: var(--navy);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 27px;
            font-weight: 500;
            line-height: 1.3;
        }

        .museum-tagline {
            min-height: 48px;
            margin: 13px 0 20px;
            color: var(--muted);
            font-size: 14px;
        }

        .museum-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding-top: 16px;
            border-top: 1px solid var(--line);
            color: var(--muted);
            font-size: 12px;
        }

        .museum-link {
            color: var(--gold-dark);
            font-weight: 800;
            text-decoration: none;
        }

        .museum-link:hover { color: var(--navy); }

        .empty-state {
            padding: 34px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: white;
            color: var(--muted);
        }

        .experience-strip {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            gap: 24px;
            margin-top: 54px;
            padding: 32px;
            border-radius: 8px;
            background: var(--navy);
            color: white;
        }

        .experience-strip h3 {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 27px;
            font-weight: 500;
        }

        .experience-strip p {
            margin: 7px 0 0;
            color: #d2dbe3;
            font-size: 14px;
        }

        .site-footer {
            padding: 24px;
            border-top: 1px solid var(--line);
            background: #eeece6;
            color: var(--muted);
            text-align: center;
            font-size: 12px;
        }

        .site-footer strong {
            color: var(--navy);
            letter-spacing: .08em;
        }

        a:focus-visible {
            outline: 3px solid var(--gold);
            outline-offset: 4px;
        }

        @media (max-width: 700px) {
            .nav-wrap {
                width: min(100% - 32px, 1240px);
                min-height: 72px;
                gap: 12px;
            }

            .brand-name { font-size: 11px; }
            .brand-subtitle { font-size: 9px; }
            .brand-mark { width: 37px; height: 37px; }
            .nav-right { gap: 8px; }
            .nav-link { display: none; }
            .language-link { padding: 5px 8px; }

            .hero { min-height: 475px; }

            .hero-inner,
            .content {
                width: calc(100% - 32px);
            }

            .hero-inner { padding: 60px 0 66px; }
            .hero-copy { font-size: 15px; }
            .hero-actions { align-items: stretch; }
            .button { width: 100%; }

            .content { padding: 52px 0 60px; }

            .section-heading {
                align-items: start;
                flex-direction: column;
                gap: 12px;
                margin-bottom: 22px;
            }

            .museum-image img { height: 230px; }
            .museum-body { padding: 21px; }
            .experience-strip { grid-template-columns: 1fr; padding: 24px; }
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }

</style>
@endsection

@section('content')

<header class="site-header">
    <div class="nav-wrap">
        <a class="brand" href="{{ route('museums.index', ['lang' => $locale]) }}" aria-label="DT-16 Virtual Museum">
            <span class="brand-mark" aria-hidden="true">M</span>
            <span>
                <span class="brand-name">DT-16 VIRTUAL MUSEUM</span>
                <span class="brand-subtitle">DIGITAL HERITAGE EXPERIENCE</span>
            </span>
        </a>

        <nav class="nav-right" aria-label="{{ $locale === 'vi' ? 'Điều hướng chính' : 'Main navigation' }}">
            <a class="nav-link" href="#museums">
                {{ $locale === 'vi' ? 'Khám phá' : 'Discover' }}
            </a>
            <div class="languages" aria-label="{{ $locale === 'vi' ? 'Chọn ngôn ngữ' : 'Choose language' }}">
                <a class="language-link {{ $locale === 'vi' ? 'active' : '' }}"
                   href="{{ route('museums.index', ['lang' => 'vi']) }}"
                   lang="vi" hreflang="vi" {{ $locale === 'vi' ? 'aria-current=page' : '' }}>VI</a>
                <a class="language-link {{ $locale === 'en' ? 'active' : '' }}"
                   href="{{ route('museums.index', ['lang' => 'en']) }}"
                   lang="en" hreflang="en" {{ $locale === 'en' ? 'aria-current=page' : '' }}>EN</a>
            </div>
        </nav>
    </div>
</header>

<section class="hero" aria-labelledby="hero-title">
    <div class="hero-inner">
        <p class="eyebrow">
            {{ $locale === 'vi' ? 'Di sản · Nghệ thuật · Công nghệ' : 'Heritage · Art · Technology' }}
        </p>

        <h1 id="hero-title">
            {{ $locale === 'vi'
                ? 'Bước vào thế giới bảo tàng.'
                : 'Step into a world of discovery.' }}
        </h1>

        <p class="hero-copy">
            {{ $locale === 'vi'
                ? 'Khám phá không gian trưng bày và những câu chuyện đằng sau mỗi hiện vật qua trải nghiệm bảo tàng số.'
                : 'Explore exhibition spaces and the stories behind every artifact through a digital museum experience.' }}
        </p>

        <div class="hero-actions">
            <a class="button button-primary" href="#museums">
                {{ $locale === 'vi' ? 'Khám phá bảo tàng' : 'Explore museums' }}
                <span aria-hidden="true">→</span>
            </a>
            <a class="button button-secondary" href="#experience">
                {{ $locale === 'vi' ? 'Trải nghiệm 360°' : 'The 360° experience' }}
            </a>
        </div>

        <p class="hero-note">
            <span>360°</span>
            {{ $locale === 'vi' ? 'Khám phá không gian theo góc nhìn của bạn' : 'Discover spaces from your own perspective' }}
        </p>
    </div>
</section>

<main class="content">
    <section id="museums" aria-labelledby="museum-heading">
        <div class="section-heading">
            <div>
                <p class="section-kicker">
                    {{ $locale === 'vi' ? 'Bộ sưu tập số' : 'Digital collection' }}
                </p>
                <h2 id="museum-heading">
                    {{ $locale === 'vi' ? 'Khám phá bảo tàng' : 'Explore museums' }}
                </h2>
            </div>
            <p class="section-description">
                {{ $locale === 'vi'
                    ? 'Mỗi không gian mở ra một góc nhìn mới về lịch sử, nghệ thuật và những câu chuyện được lưu giữ.'
                    : 'Every space offers a new perspective on history, art, and the stories preserved within.' }}
            </p>
        </div>

        @if ($museums->isEmpty())
            <div class="empty-state">
                {{ $locale === 'vi'
                    ? 'Hiện chưa có bảo tàng được xuất bản. Vui lòng kiểm tra dữ liệu và bản dịch trong cơ sở dữ liệu.'
                    : 'There are no published museums yet. Please check the database records and translations.' }}
            </div>
        @else
            <div class="museum-grid">
                @foreach ($museums as $museum)
                    @php
                        $translation = $museum->translations->first();
                        $museumName = $translation?->name ?? $museum->slug;
                        $museumTagline = $translation?->tagline
                            ?? ($locale === 'vi'
                                ? 'Khám phá không gian bảo tàng qua trải nghiệm số.'
                                : 'Discover museum spaces through a digital experience.');
                    @endphp

                    <article class="museum-card">
                        <div class="museum-image">
                            <img
                                src="{{ asset('panoramas/dt16-demo-museum/sanh-chinh.png') }}"
                                alt="{{ $locale === 'vi'
                                    ? 'Không gian trưng bày bảo tàng ảo'
                                    : 'Virtual museum exhibition space' }}"
                                loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                {{ $loop->first ? 'fetchpriority=high' : '' }}
                            >
                            <span class="image-label">
                                {{ $locale === 'vi' ? 'TRẢI NGHIỆM SỐ' : 'DIGITAL EXPERIENCE' }}
                            </span>
                        </div>

                        <div class="museum-body">
                            @if ($museum->city || $museum->country_code)
                                <p class="museum-location">
                                    {{ $museum->city }}
                                    @if ($museum->city && $museum->country_code)
                                        ·
                                    @endif
                                    {{ $museum->country_code }}
                                </p>
                            @else
                                <p class="museum-location">DT-16 · VIRTUAL COLLECTION</p>
                            @endif

                            <h3 class="museum-title">{{ $museumName }}</h3>
                            <p class="museum-tagline">{{ $museumTagline }}</p>

                            <div class="museum-meta">
                                <span>
                                    {{ $locale === 'vi' ? 'Không gian đã xuất bản' : 'Published scenes' }}:
                                    {{ $museum->scenes_count }}
                                </span>
                                <a class="museum-link"
                                   href="{{ route('museums.show', ['slug' => $museum->slug, 'lang' => $locale]) }}">
                                    {{ $locale === 'vi' ? 'Khám phá →' : 'Discover →' }}
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

        @if ($museums->hasPages())
            <nav class="pagination" aria-label="{{ $locale === 'vi' ? 'Phân trang bảo tàng' : 'Museum pagination' }}">
                {{ $museums->links() }}
            </nav>
        @endif
    </section>

    <section id="experience" class="experience-strip" aria-labelledby="experience-heading">
        <div>
            <h3 id="experience-heading">
                {{ $locale === 'vi' ? 'Một không gian. Nhiều góc nhìn.' : 'One space. Many perspectives.' }}
            </h3>
            <p>
                {{ $locale === 'vi'
                    ? 'Bắt đầu từ bộ sưu tập hiện có và mở một hành trình khám phá của riêng bạn.'
                    : 'Start with the available collection and discover at your own pace.' }}
            </p>
        </div>

        @if ($museums->isNotEmpty())
            <a class="button button-primary"
               href="{{ route('museums.show', ['slug' => $museums->first()->slug, 'lang' => $locale]) }}">
                {{ $locale === 'vi' ? 'Bắt đầu khám phá' : 'Start exploring' }}
                <span aria-hidden="true">→</span>
            </a>
        @else
            <a class="button button-primary" href="#museums">
                {{ $locale === 'vi' ? 'Xem bộ sưu tập' : 'View collection' }}
            </a>
        @endif
    </section>
</main>

<footer class="site-footer">
    <strong>DT-16 VIRTUAL MUSEUM 360°</strong>
    <span> · </span>
    {{ $locale === 'vi' ? 'Không gian di sản trong trải nghiệm số.' : 'Heritage spaces, reimagined digitally.' }}
</footer>

@endsection
