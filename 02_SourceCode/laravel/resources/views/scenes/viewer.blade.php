<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $sceneTranslation?->title ?? $scene->slug }} - {{ $museumTranslation?->name ?? $museum->slug }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css">

    <style>
        :root {
            color-scheme: dark;
            --bg: #0b1220;
            --panel: #121e30;
            --line: #2d4058;
            --text: #f8fafc;
            --muted: #b7c5d8;
            --gold: #f5bd54;
            --blue: #60a5fa;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
        }

        a { color: #93c5fd; text-decoration: none; }

        button, a { -webkit-tap-highlight-color: transparent; }

        button {
            font: inherit;
            cursor: pointer;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding: 15px clamp(14px, 3vw, 32px);
            background: #111c2d;
            border-bottom: 1px solid var(--line);
        }

        .brand { font-weight: 800; line-height: 1.5; }
        .brand small { display: block; color: var(--muted); font-weight: 400; }
        .top-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

        .btn, .lang-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 9px 13px;
            border: 1px solid #425773;
            border-radius: 10px;
            background: #1c2b41;
            color: white;
            gap: 8px;
        }

        .btn:hover, .lang-link:hover { border-color: var(--gold); }
        .lang-link.active { border-color: var(--gold); color: var(--gold); }

        .container {
            width: min(1500px, 96%);
            margin: 22px auto 40px;
        }

        .heading { margin: 0 0 16px; }
        .heading h1 { margin: 0 0 8px; font-size: clamp(22px, 3vw, 32px); }
        .heading p { margin: 5px 0; color: var(--muted); line-height: 1.6; }

        .viewer-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 340px;
            gap: 16px;
            align-items: start;
        }

        .viewer-card, .detail-card, .list-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--panel);
        }

        .viewer-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            padding: 12px 15px;
            border-bottom: 1px solid var(--line);
        }

        .viewer-title strong { font-size: 15px; }
        .viewer-title span { color: var(--muted); font-size: 13px; }

        #panorama {
            width: 100%;
            height: min(68vh, 740px);
            min-height: 380px;
            background: #020617;
        }

        .viewer-controls {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            padding: 12px;
            border-top: 1px solid var(--line);
        }

        .viewer-controls .btn { flex: 1; }

        .side-column { display: grid; gap: 14px; }

        .section-heading {
            margin: 0;
            padding: 15px 16px;
            font-size: 17px;
            border-bottom: 1px solid var(--line);
        }

        .detail-content { padding: 16px; }

        .artifact-image {
            display: block;
            width: 100%;
            max-height: 230px;
            aspect-ratio: 4 / 3;
            object-fit: contain;
            border: 1px solid #35475e;
            border-radius: 12px;
            background: #09111e;
        }

        .artifact-placeholder {
            display: grid;
            place-items: center;
            min-height: 150px;
            padding: 20px;
            color: var(--muted);
            border: 1px dashed #52657d;
            border-radius: 12px;
            text-align: center;
        }

        .artifact-name { margin: 15px 0 8px; font-size: 22px; line-height: 1.35; }
        .artifact-summary { color: #e2e8f0; line-height: 1.65; white-space: pre-line; }
        .artifact-detail { color: var(--muted); line-height: 1.75; white-space: pre-line; }

        .metadata {
            display: grid;
            gap: 9px;
            margin: 14px 0;
        }

        .metadata-row {
            padding: 10px 11px;
            border-radius: 9px;
            background: #0b1525;
            line-height: 1.5;
        }

        .metadata-row strong {
            display: block;
            margin-bottom: 3px;
            color: var(--gold);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .detail-empty { color: var(--muted); line-height: 1.7; }

        .list-content { padding: 10px; display: grid; gap: 8px; max-height: 330px; overflow-y: auto; }

        .artifact-option {
            display: grid;
            grid-template-columns: 52px minmax(0, 1fr);
            align-items: center;
            gap: 11px;
            width: 100%;
            padding: 9px;
            color: var(--text);
            text-align: left;
            background: #0b1525;
            border: 1px solid #293c54;
            border-radius: 10px;
        }

        .artifact-option:hover, .artifact-option.active {
            border-color: var(--gold);
            background: #1b2a3e;
        }

        .option-image {
            width: 52px;
            height: 52px;
            object-fit: cover;
            border-radius: 8px;
            background: #172033;
        }

        .option-title { font-weight: 700; line-height: 1.4; overflow-wrap: anywhere; }
        .option-subtitle { margin-top: 4px; color: var(--muted); font-size: 12px; }

        .notice {
            margin: 14px 0;
            padding: 14px;
            color: #fde68a;
            background: #422006;
            border: 1px solid #854d0e;
            border-radius: 10px;
            line-height: 1.7;
        }

        .footer { margin-top: 18px; color: var(--muted); font-size: 13px; line-height: 1.7; }

        /* Hotspot do ung dung tao: nut lon, de bam, doc lap voi anh nen */
        .pnlm-hotspot.custom-hotspot {
            width: 48px !important;
            height: 48px !important;
            margin-left: -24px !important;
            margin-top: -24px !important;
            border: 0 !important;
            border-radius: 50% !important;
            background: transparent !important;
        }

        .hotspot-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            padding: 0;
            border: 3px solid white;
            border-radius: 50%;
            background: #dc2626;
            color: white;
            font-size: 17px;
            font-weight: 800;
            box-shadow: 0 0 0 4px #dc262655, 0 4px 15px #0009;
            transition: transform .15s, background .15s;
        }

        .hotspot-button:hover, .hotspot-button:focus-visible {
            transform: scale(1.15);
            background: #b91c1c;
            outline: 3px solid var(--gold);
            outline-offset: 2px;
        }

        .hotspot-button.selected { background: #b7791f; }

        .pnlm-tooltip span { font-size: 14px; }

        /* Fullscreen bao gom ca panorama, thong tin va danh sach */
        .viewer-layout:fullscreen {
            box-sizing: border-box;
            width: 100vw;
            height: 100vh;
            padding: 14px;
            margin: 0;
            overflow: auto;
            background: var(--bg);
            grid-template-columns: minmax(0, 1fr) minmax(280px, 340px);
            align-items: stretch;
        }

        .viewer-layout:fullscreen .viewer-card,
        .viewer-layout:fullscreen .side-column {
            min-height: 0;
        }

        .viewer-layout:fullscreen .viewer-card {
            display: flex;
            flex-direction: column;
        }

        .viewer-layout:fullscreen #panorama {
            height: auto;
            min-height: 0;
            flex: 1;
        }

        .viewer-layout:fullscreen .side-column {
            grid-template-columns: 1fr;
            grid-template-rows: minmax(0, 1fr) minmax(0, 0.8fr);
            overflow: auto;
        }

        .viewer-layout:fullscreen .detail-card,
        .viewer-layout:fullscreen .list-card {
            min-height: 0;
            overflow: auto;
        }

        .viewer-layout:fullscreen .detail-content,
        .viewer-layout:fullscreen .list-content {
            overflow-y: auto;
            max-height: 42vh;
        }

        @media (max-width: 950px) {
            .viewer-layout:fullscreen {
                grid-template-columns: minmax(0, 1fr);
                grid-template-rows: minmax(300px, 1fr) auto;
            }

            .viewer-layout:fullscreen #panorama {
                min-height: 300px;
            }

            .viewer-layout:fullscreen .side-column {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: auto;
            }
        }

        @media (max-width: 600px) {
            .viewer-layout:fullscreen {
                padding: 6px;
                gap: 8px;
            }

            .viewer-layout:fullscreen .side-column {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 950px) {
            .viewer-layout { grid-template-columns: 1fr; }
            .side-column { grid-template-columns: 1fr 1fr; }
            #panorama { height: 58vh; min-height: 340px; }
        }

        @media (max-width: 600px) {
            .container { width: 94%; margin-top: 14px; }
            .side-column { grid-template-columns: 1fr; }
            #panorama { height: 52vh; min-height: 300px; }
            .topbar { padding: 12px; }
            .hotspot-button { width: 42px; height: 42px; font-size: 15px; }
            .pnlm-hotspot.custom-hotspot { width: 44px !important; height: 44px !important; margin-left: -22px !important; margin-top: -22px !important; }
        }
    </style>
</head>
<body>
<header class="topbar">
    <div class="brand">
        {{ $museumTranslation?->name ?? $museum->slug }}
        <small>{{ $sceneTranslation?->title ?? $scene->slug }}</small>
    </div>
    <div class="top-actions">
        <a class="lang-link {{ $lang === 'vi' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['lang' => 'vi']) }}">Tiếng Việt</a>
        <a class="lang-link {{ $lang === 'en' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}">English</a>
        <a class="btn" href="{{ route('museums.index', ['lang' => $lang]) }}">← {{ $lang === 'vi' ? 'Danh sách bảo tàng' : 'Museums' }}</a>
    </div>
</header>

<main class="container">
    <section class="heading">
        <h1>{{ $sceneTranslation?->title ?? $scene->slug }}</h1>
        <p>{{ $museumTranslation?->name ?? $museum->slug }}
            @if ($scene->floor_label) · {{ $scene->floor_label }} @endif
        </p>
        @if ($sceneTranslation?->description)
            <p>{{ $sceneTranslation->description }}</p>
        @endif
    </section>

    @php
        $viewerPanoramaUrl = $panoramaUrl ?? (
            $scene->preview_path
                ? (\Illuminate\Support\Str::startsWith($scene->preview_path, ['http://', 'https://'])
                    ? $scene->preview_path
                    : asset(ltrim($scene->preview_path, '/')))
                : null
        );

        $viewerItems = $hotspots->values()->map(function ($item) {
            $image = $item['image'] ?? null;

            if ($image && !\Illuminate\Support\Str::startsWith($image, ['http://', 'https://', '/'])) {
                $image = asset($image);
            } elseif ($image && \Illuminate\Support\Str::startsWith($image, '/')) {
                $image = asset(ltrim($image, '/'));
            }

            return [
                'id' => (int) $item['id'],
                'yaw' => (float) $item['yaw'],
                'pitch' => (float) $item['pitch'],
                'name' => (string) ($item['name'] ?? ''),
                'summary' => (string) ($item['summary'] ?? ''),
                'detail' => (string) ($item['detail'] ?? ''),
                'image' => $image,
                'period' => (string) ($item['period'] ?? ''),
                'origin' => (string) ($item['origin'] ?? ''),
            ];
        })->values();
    @endphp

    @if (!$viewerPanoramaUrl)
        <div class="notice">
            {{ $lang === 'vi' ? 'Cảnh này chưa có ảnh panorama. Hãy cấu hình preview_path trước khi tham quan.' : 'This scene has no panorama image. Configure preview_path before starting the tour.' }}
        </div>
    @elseif ($scene->panorama_type !== 'equirectangular')
        <div class="notice">
            {{ $lang === 'vi' ? 'Cảnh này chưa được cấu hình ở định dạng equirectangular. Hãy kiểm tra panorama_type và ảnh panorama trước khi sử dụng chế độ 360°.' : 'This scene is not configured as equirectangular. Check panorama_type and the panorama image before using the 360° viewer.' }}
        </div>
    @else
        <div class="viewer-layout">
            <section class="viewer-card">
                <div class="viewer-title">
                    <strong>{{ $lang === 'vi' ? 'Không gian tham quan 360°' : '360° Virtual Tour' }}</strong>
                    <span>{{ $lang === 'vi' ? 'Kéo để xoay · Cuộn để phóng to' : 'Drag to look around · Scroll to zoom' }}</span>
                </div>

                <div id="panorama" aria-label="{{ $sceneTranslation?->title ?? $scene->slug }}"></div>

                <div class="viewer-controls">
                    <button class="btn" id="zoomOut" type="button">− {{ $lang === 'vi' ? 'Thu nhỏ' : 'Zoom out' }}</button>
                    <button class="btn" id="zoomIn" type="button">＋ {{ $lang === 'vi' ? 'Phóng to' : 'Zoom in' }}</button>
                    <button class="btn" id="resetView" type="button">{{ $lang === 'vi' ? 'Góc nhìn ban đầu' : 'Reset view' }}</button>
                    <button class="btn" id="fullscreen" type="button">{{ $lang === 'vi' ? 'Toàn màn hình' : 'Fullscreen' }}</button>
                </div>
            </section>

            <aside class="side-column">
                <section class="detail-card" aria-live="polite">
                    <h2 class="section-heading">{{ $lang === 'vi' ? 'Thông tin hiện vật' : 'Artifact information' }}</h2>
                    <div class="detail-content" id="artifactDetail">
                        <p class="detail-empty">
                            {{ $lang === 'vi' ? 'Chọn một điểm hotspot trên ảnh hoặc chọn hiện vật trong danh sách để xem thông tin.' : 'Select a hotspot in the panorama or an item in the list to view its information.' }}
                        </p>
                    </div>
                </section>

                <section class="list-card">
                    <h2 class="section-heading">{{ $lang === 'vi' ? 'Danh sách hiện vật' : 'Artifact list' }} ({{ $viewerItems->count() }})</h2>
                    <div class="list-content" id="artifactList">
                        @forelse ($viewerItems as $index => $item)
                            <button class="artifact-option" type="button" data-artifact-id="{{ $item['id'] }}">
                                @if ($item['image'])
                                    <img class="option-image" src="{{ $item['image'] }}" alt="" loading="lazy">
                                @else
                                    <span class="option-image" aria-hidden="true"></span>
                                @endif
                                <span>
                                    <span class="option-title">{{ $item['name'] ?: (($lang === 'vi' ? 'Hiện vật' : 'Artifact') . ' ' . ($index + 1)) }}</span>
                                    <span class="option-subtitle">{{ $item['period'] ?: ($lang === 'vi' ? 'Chưa có niên đại' : 'Period unavailable') }}</span>
                                </span>
                            </button>
                        @empty
                            <p class="detail-empty">{{ $lang === 'vi' ? 'Chưa có hotspot hợp lệ gắn với hiện vật đã xuất bản.' : 'No published artifacts are linked to hotspots yet.' }}</p>
                        @endforelse
                    </div>
                </section>
            </aside>
        </div>
    @endif

    <p class="footer">
        {{ $lang === 'vi' ? 'Thông tin hiện vật được lấy từ dữ liệu của hệ thống. Nội dung chưa xác minh cần được bổ sung nguồn chính thức trước khi công bố.' : 'Artifact information comes from the system database. Unverified entries should be supplemented with official sources before publication.' }}
    </p>
</main>

@if (isset($viewerPanoramaUrl) && $viewerPanoramaUrl && $scene->panorama_type === 'equirectangular')
    <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
    <script>
    (() => {
        'use strict';

        const items = @json($viewerItems);
        const panoramaUrl = @json($viewerPanoramaUrl);
        const lang = @json($lang);
        const detail = document.getElementById('artifactDetail');
        const list = document.getElementById('artifactList');
        const selectedLabel = lang === 'vi' ? 'Đang xem' : 'Selected';
        let viewer = null;
        let selectedId = null;

        const esc = (value) => String(value ?? '').replace(/[&<>"']/g, (ch) => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        })[ch]);

        function selectArtifact(id) {
            const item = items.find((entry) => Number(entry.id) === Number(id));
            if (!item) return;

            selectedId = Number(item.id);
            const image = item.image
                ? `<img class="artifact-image" src="${esc(item.image)}" alt="${esc(item.name)}" onerror="this.style.display='none';this.nextElementSibling.hidden=false"><div class="artifact-placeholder" hidden>${lang === 'vi' ? 'Không tải được ảnh hiện vật.' : 'Artifact image could not be loaded.'}</div>`
                : `<div class="artifact-placeholder">${lang === 'vi' ? 'Chưa có ảnh riêng cho hiện vật này.' : 'No separate image is available for this artifact.'}</div>`;

            detail.innerHTML = `
                ${image}
                <h3 class="artifact-name">${esc(item.name || (lang === 'vi' ? 'Chưa có tên hiện vật' : 'Untitled artifact'))}</h3>
                ${item.summary ? `<p class="artifact-summary">${esc(item.summary)}</p>` : ''}
                <div class="metadata">
                    <div class="metadata-row"><strong>${lang === 'vi' ? 'Niên đại' : 'Period'}</strong>${esc(item.period || (lang === 'vi' ? 'Chưa cập nhật' : 'Not provided'))}</div>
                    <div class="metadata-row"><strong>${lang === 'vi' ? 'Xuất xứ' : 'Origin'}</strong>${esc(item.origin || (lang === 'vi' ? 'Chưa cập nhật' : 'Not provided'))}</div>
                </div>
                ${item.detail ? `<h4>${lang === 'vi' ? 'Thông tin chi tiết' : 'Details'}</h4><p class="artifact-detail">${esc(item.detail)}</p>` : `<p class="detail-empty">${lang === 'vi' ? 'Chưa có mô tả chi tiết.' : 'No detailed description is available.'}</p>`}
            `;

            list.querySelectorAll('[data-artifact-id]').forEach((button) => {
                const active = Number(button.dataset.artifactId) === selectedId;
                button.classList.toggle('active', active);
                button.setAttribute('aria-pressed', active ? 'true' : 'false');
                const old = button.querySelector('.option-subtitle');
                if (old) {
                    const sourceText = old.dataset.original || old.textContent;
                    old.dataset.original = sourceText;
                    old.textContent = active ? `${selectedLabel} · ${sourceText}` : sourceText.replace(/^.*? · /, '');
                }
            });

            document.querySelectorAll('.hotspot-button').forEach((button) => {
                button.classList.toggle('selected', Number(button.dataset.artifactId) === selectedId);
                button.setAttribute('aria-pressed', Number(button.dataset.artifactId) === selectedId ? 'true' : 'false');
            });
        }

        function createHotspot(hotspotDiv, args) {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'hotspot-button';
            button.dataset.artifactId = String(args.id);
            button.textContent = String(args.number);
            button.title = args.name || (lang === 'vi' ? 'Xem hiện vật' : 'View artifact');
            button.setAttribute('aria-label', button.title);
            button.addEventListener('click', (event) => {
                event.stopPropagation();
                selectArtifact(args.id);
            });
            hotspotDiv.appendChild(button);
        }

        try {
            viewer = pannellum.viewer('panorama', {
                type: 'equirectangular',
                panorama: panoramaUrl,
                autoLoad: true,
                showZoomCtrl: false,
                showFullscreenCtrl: false,
                compass: false,
                mouseZoom: true,
                draggable: true,
                keyboardZoom: true,
                hfov: {{ (float) ($scene->initial_hfov ?: 100) }},
                yaw: {{ (float) ($scene->initial_yaw ?? 0) }},
                pitch: {{ (float) ($scene->initial_pitch ?? 0) }},
                hotSpots: items.map((item, index) => ({
                    pitch: Number(item.pitch),
                    yaw: Number(item.yaw),
                    type: 'custom',
                    cssClass: 'custom-hotspot',
                    createTooltipFunc: createHotspot,
                    createTooltipArgs: {
                        id: item.id,
                        number: index + 1,
                        name: item.name
                    }
                }))
            });

            document.getElementById('zoomIn')?.addEventListener('click', () => {
                if (viewer) viewer.setHfov(Math.max(40, viewer.getHfov() - 10));
            });
            document.getElementById('zoomOut')?.addEventListener('click', () => {
                if (viewer) viewer.setHfov(Math.min(120, viewer.getHfov() + 10));
            });
            document.getElementById('resetView')?.addEventListener('click', () => {
                if (!viewer) return;
                viewer.setYaw({{ (float) ($scene->initial_yaw ?? 0) }});
                viewer.setPitch({{ (float) ($scene->initial_pitch ?? 0) }});
                viewer.setHfov({{ (float) ($scene->initial_hfov ?: 100) }});
            });
            document.getElementById('fullscreen')?.addEventListener('click', async () => {
                const layout = document.querySelector('.viewer-layout');
                if (!layout) return;

                try {
                    if (document.fullscreenElement) {
                        await document.exitFullscreen();
                    } else if (layout.requestFullscreen) {
                        await layout.requestFullscreen();
                    } else {
                        alert(lang === 'vi'
                            ? 'Trình duyệt không hỗ trợ chế độ toàn màn hình.'
                            : 'Fullscreen is not supported by this browser.');
                    }
                } catch (error) {
                    console.error('Fullscreen error:', error);
                }
            });

            list.querySelectorAll('[data-artifact-id]').forEach((button) => {
                button.addEventListener('click', () => selectArtifact(button.dataset.artifactId));
            });

            viewer.on('error', (error) => {
                console.error('Pannellum error:', error);
                detail.innerHTML = `<p class="detail-empty">${lang === 'vi' ? 'Không tải được ảnh panorama. Hãy kiểm tra đường dẫn preview_path và định dạng ảnh.' : 'Could not load the panorama. Check preview_path and the image format.'}</p>`;
            });
        } catch (error) {
            console.error('Could not initialize panorama viewer:', error);
            detail.innerHTML = `<p class="detail-empty">${lang === 'vi' ? 'Không thể khởi tạo trình xem panorama. Kiểm tra kết nối và ảnh panorama.' : 'Could not initialize the panorama viewer. Check the connection and panorama image.'}</p>`;
        }
    })();
    </script>
@endif
</body>
</html>
