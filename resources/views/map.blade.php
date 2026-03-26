<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Berita Dalam Peta — Indonesia Live Map</title>
    <meta name="description" content="Peta interaktif berita Indonesia secara real-time. Pantau bencana alam, kecelakaan, kemacetan, dan event terbaru di seluruh Indonesia.">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg-dark: #0f0f14;
            --bg-panel: rgba(15, 15, 22, 0.92);
            --bg-card: rgba(30, 30, 42, 0.85);
            --border: rgba(255, 255, 255, 0.08);
            --text-primary: #e8e8ed;
            --text-secondary: #8b8b9e;
            --text-muted: #5b5b6e;
            --accent: #6366f1;
        }
        html, body { height: 100%; overflow: hidden; font-family: 'Inter', sans-serif; }

        /* Map */
        #map { width: 100%; height: 100vh; z-index: 1; }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: 380px;
            height: 100vh;
            background: var(--bg-panel);
            backdrop-filter: blur(20px);
            border-left: 1px solid var(--border);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar.collapsed { transform: translateX(100%); }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-header h1 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }
        .sidebar-header p {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Date Navigation */
        .date-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-bottom: 1px solid var(--border);
        }
        .date-nav input[type="date"] {
            flex: 1;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 8px 12px;
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
        }
        .date-nav input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(0.7); }
        .date-nav button {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--text-primary);
            padding: 8px 12px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }
        .date-nav button:hover { border-color: var(--accent); }

        /* Category Filters */
        .category-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            padding: 12px 20px;
            border-bottom: 1px solid var(--border);
        }
        .cat-filter {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid;
            transition: all 0.2s;
            user-select: none;
        }
        .cat-filter.active { opacity: 1; }
        .cat-filter:not(.active) { opacity: 0.35; }

        /* Event Feed */
        .event-feed {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
        }
        .event-feed::-webkit-scrollbar { width: 4px; }
        .event-feed::-webkit-scrollbar-track { background: transparent; }
        .event-feed::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

        .event-item {
            padding: 14px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 4px;
            border: 1px solid transparent;
        }
        .event-item:hover { background: var(--bg-card); border-color: var(--border); }
        .event-item.active { background: rgba(99, 102, 241, 0.1); border-color: rgba(99, 102, 241, 0.3); }

        .event-item .event-category {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .event-item .event-title {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
            line-height: 1.4;
            margin-bottom: 6px;
        }
        .event-item .event-meta {
            display: flex;
            gap: 12px;
            font-size: 11px;
            color: var(--text-muted);
        }
        .event-item .event-meta i { font-size: 12px; }

        .event-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        /* Sidebar Toggle */
        .sidebar-toggle {
            position: fixed;
            top: 16px;
            right: 396px;
            z-index: 1001;
            background: var(--bg-panel);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            color: var(--text-primary);
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s;
        }
        .sidebar-toggle.shifted { right: 16px; }

        /* Map Controls */
        .map-controls {
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .map-control-btn {
            background: var(--bg-panel);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px;
            color: var(--text-primary);
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s;
        }
        .map-control-btn:hover { border-color: var(--accent); }

        /* Tile Switch Dropdown */
        .tile-dropdown {
            position: fixed;
            top: 60px;
            left: 52px;
            z-index: 1001;
            background: var(--bg-panel);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px;
            display: none;
            min-width: 180px;
        }
        .tile-dropdown.show { display: block; }
        .tile-option {
            display: block;
            width: 100%;
            padding: 8px 12px;
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 13px;
            cursor: pointer;
            border-radius: 6px;
            text-align: left;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .tile-option:hover { background: var(--bg-card); color: var(--text-primary); }
        .tile-option.active { color: var(--accent); background: rgba(99, 102, 241, 0.1); }

        /* Status Indicator */
        .status-bar {
            padding: 8px 20px;
            border-top: 1px solid var(--border);
            font-size: 11px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* Admin Link */
        .admin-link {
            position: fixed;
            bottom: 16px;
            left: 16px;
            z-index: 1000;
            background: var(--bg-panel);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 14px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 12px;
            transition: all 0.2s;
        }
        .admin-link:hover { color: var(--accent); border-color: var(--accent); }

        /* Empty State */
        .empty-feed {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }
        .empty-feed i { font-size: 40px; margin-bottom: 12px; }

        /* Override Leaflet Controls */
        .leaflet-control-zoom { display: none; }

        /* Mobile */
        @media (max-width: 768px) {
            .sidebar { width: 100%; }
            .sidebar-toggle { right: 16px; }
            .sidebar-toggle:not(.shifted) { right: calc(100% - 60px); }
        }
    </style>
</head>
<body>
    <!-- Map -->
    <div id="map"></div>

    <!-- Map Controls -->
    <div class="map-controls">
        <button class="map-control-btn" onclick="map.zoomIn()" title="Zoom In">
            <i class="ri-add-line"></i>
        </button>
        <button class="map-control-btn" onclick="map.zoomOut()" title="Zoom Out">
            <i class="ri-subtract-line"></i>
        </button>
        <button class="map-control-btn" id="tile-toggle" onclick="toggleTileDropdown()" title="Map Style">
            <i class="ri-stack-line"></i>
        </button>
    </div>

    <!-- Tile Switcher -->
    <div class="tile-dropdown" id="tile-dropdown">
        <button class="tile-option active" data-tile="standard" onclick="switchTile('standard')">🗺️ Standard</button>
        <button class="tile-option" data-tile="dark" onclick="switchTile('dark')">🌙 Dark</button>
        <button class="tile-option" data-tile="satellite" onclick="switchTile('satellite')">🛰️ Satellite</button>
    </div>

    <!-- Sidebar Toggle -->
    <button class="sidebar-toggle" id="sidebar-toggle" onclick="toggleSidebar()">
        <i class="ri-layout-right-line"></i>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h1>📍 Berita Dalam Peta</h1>
            <p>Peta berita real-time Indonesia</p>
        </div>

        <!-- Date Navigation -->
        <div class="date-nav">
            <button onclick="changeDate(-1)" title="Hari sebelumnya"><i class="ri-arrow-left-s-line"></i></button>
            <input type="date" id="date-picker" onchange="loadEvents()">
            <button onclick="changeDate(1)" title="Hari berikutnya"><i class="ri-arrow-right-s-line"></i></button>
            <button onclick="resetDate()" title="Hari ini" style="font-size:12px;">Hari Ini</button>
        </div>

        <!-- Category Filters -->
        <div class="category-filters" id="category-filters">
            @foreach($categories as $cat)
            <span class="cat-filter active" data-slug="{{ $cat->slug }}"
                style="background:{{ $cat->color }}22;color:{{ $cat->color }};border-color:{{ $cat->color }}44;"
                onclick="toggleCategory(this)">
                {{ $cat->icon }} {{ $cat->name }}
            </span>
            @endforeach
        </div>

        <!-- Event Feed -->
        <div class="event-feed" id="event-feed">
            <div class="empty-feed">
                <i class="ri-loader-4-line"></i>
                <span>Memuat event...</span>
            </div>
        </div>

        <!-- Status Bar -->
        <div class="status-bar">
            <span class="status-dot"></span>
            <span id="status-text">Terhubung — Auto-refresh aktif</span>
        </div>
    </aside>

    <!-- Admin Link -->
    <a href="/admin" class="admin-link"><i class="ri-settings-3-line"></i> Admin</a>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Config from backend
        const mapConfig = @json($mapSettings);
        const categories = @json($categories);

        // Map init
        const map = L.map('map', {
            center: [mapConfig.center_lat, mapConfig.center_lng],
            zoom: mapConfig.zoom,
            zoomControl: false,
        });

        // Tile Layers
        const tileLayers = {
            standard: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }),
            dark: L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '© CARTO'
            }),
            satellite: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '© Esri'
            }),
        };

        let currentTile = 'dark';
        tileLayers.dark.addTo(map);

        function switchTile(name) {
            Object.values(tileLayers).forEach(l => map.removeLayer(l));
            tileLayers[name].addTo(map);
            currentTile = name;
            document.querySelectorAll('.tile-option').forEach(b => b.classList.remove('active'));
            document.querySelector(`[data-tile="${name}"]`).classList.add('active');
            document.getElementById('tile-dropdown').classList.remove('show');
        }

        function toggleTileDropdown() {
            document.getElementById('tile-dropdown').classList.toggle('show');
        }

        // Category colors/icons lookup
        const catLookup = {};
        categories.forEach(c => { catLookup[c.slug] = c; });

        // Create category pin icons
        function createPinIcon(category) {
            const cat = catLookup[category] || { color: '#95a5a6', icon: '📰' };
            return L.divIcon({
                className: 'custom-pin',
                html: `<div style="
                    width:32px;height:32px;border-radius:50%;
                    background:${cat.color};
                    display:flex;align-items:center;justify-content:center;
                    font-size:14px;
                    box-shadow:0 2px 8px ${cat.color}66, 0 0 0 3px ${cat.color}33;
                    border:2px solid rgba(255,255,255,0.3);
                    transition:transform 0.2s;
                    cursor:pointer;
                ">${cat.icon}</div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16],
                popupAnchor: [0, -20],
            });
        }

        // Events data
        let markers = L.layerGroup().addTo(map);
        let allEvents = [];
        let activeCategories = new Set(categories.map(c => c.slug));

        // Sidebar controls
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebar-toggle');
            sidebar.classList.toggle('collapsed');
            toggle.classList.toggle('shifted');
        }

        // Date controls
        const datePicker = document.getElementById('date-picker');
        datePicker.value = new Date().toISOString().split('T')[0];

        function changeDate(delta) {
            const d = new Date(datePicker.value);
            d.setDate(d.getDate() + delta);
            datePicker.value = d.toISOString().split('T')[0];
            loadEvents();
        }

        function resetDate() {
            datePicker.value = new Date().toISOString().split('T')[0];
            loadEvents();
        }

        // Category filter
        function toggleCategory(el) {
            const slug = el.dataset.slug;
            el.classList.toggle('active');
            if (activeCategories.has(slug)) {
                activeCategories.delete(slug);
            } else {
                activeCategories.add(slug);
            }
            renderEvents();
        }

        // Load events from API
        function loadEvents() {
            const date = datePicker.value;
            const url = `/api/events?date=${date}`;

            fetch(url)
                .then(r => r.json())
                .then(data => {
                    allEvents = data;
                    renderEvents();
                    document.getElementById('status-text').textContent =
                        `${data.length} event — Terakhir diperbarui ${new Date().toLocaleTimeString('id-ID')}`;
                })
                .catch(err => {
                    document.getElementById('status-text').textContent = 'Gagal memuat data';
                });
        }

        // Render events on map and sidebar
        function renderEvents() {
            markers.clearLayers();
            const feed = document.getElementById('event-feed');

            const filtered = allEvents.filter(e => activeCategories.has(e.category_slug || 'umum'));

            if (filtered.length === 0) {
                feed.innerHTML = `
                    <div class="empty-feed">
                        <i class="ri-map-pin-line"></i>
                        <span>Tidak ada event untuk tanggal dan filter yang dipilih</span>
                    </div>`;
                return;
            }

            feed.innerHTML = '';

            filtered.forEach(event => {
                const cat = catLookup[event.category_slug] || catLookup['umum'] || { name: 'Umum', icon: '📰', color: '#95a5a6' };
                const publishedAt = event.published_at ? timeAgo(new Date(event.published_at)) : '';

                // Add marker to map
                const marker = L.marker([event.latitude, event.longitude], {
                    icon: createPinIcon(event.category_slug)
                });

                let popupHtml = `
                    <div style="max-width:280px;font-family:Inter,sans-serif;">
                        ${event.image_url ? `<img src="${event.image_url}" style="width:100%;height:120px;object-fit:cover;border-radius:6px;margin-bottom:8px;" onerror="this.style.display='none'">` : ''}
                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                            <span style="background:${cat.color}22;color:${cat.color};padding:2px 8px;border-radius:12px;font-size:11px;font-weight:600;">
                                ${cat.icon} ${cat.name}
                            </span>
                            <span style="font-size:11px;color:#888;">${publishedAt}</span>
                        </div>
                        <div style="font-size:14px;font-weight:600;margin-bottom:6px;">${event.title}</div>
                        ${event.description ? `<div style="font-size:12px;color:#666;margin-bottom:8px;">${event.description.substring(0, 150)}${event.description.length > 150 ? '...' : ''}</div>` : ''}
                        ${event.source_url ? `<a href="${event.source_url}" target="_blank" style="font-size:12px;color:#6366f1;">Sumber: ${event.source_name || 'Link'} →</a>` : ''}
                    </div>
                `;

                marker.bindPopup(popupHtml, { maxWidth: 300 });
                markers.addLayer(marker);

                // Add to sidebar feed
                const item = document.createElement('div');
                item.className = 'event-item';
                item.innerHTML = `
                    ${event.image_url ? `<img src="${event.image_url}" class="event-image" onerror="this.style.display='none'">` : ''}
                    <span class="event-category" style="background:${cat.color}22;color:${cat.color};">
                        ${cat.icon} ${cat.name}
                    </span>
                    <div class="event-title">${event.title}</div>
                    <div class="event-meta">
                        <span><i class="ri-time-line"></i> ${publishedAt}</span>
                        ${event.source_name ? `<span><i class="ri-links-line"></i> ${event.source_name}</span>` : ''}
                    </div>
                `;

                item.addEventListener('click', () => {
                    map.setView([event.latitude, event.longitude], 12);
                    marker.openPopup();
                    document.querySelectorAll('.event-item').forEach(i => i.classList.remove('active'));
                    item.classList.add('active');
                });

                feed.appendChild(item);
            });
        }

        // Time ago helper
        function timeAgo(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            if (seconds < 60) return 'Baru saja';
            const minutes = Math.floor(seconds / 60);
            if (minutes < 60) return `${minutes} menit lalu`;
            const hours = Math.floor(minutes / 60);
            if (hours < 24) return `${hours} jam lalu`;
            const days = Math.floor(hours / 24);
            return `${days} hari lalu`;
        }

        // Close tile dropdown on outside click
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#tile-dropdown') && !e.target.closest('#tile-toggle')) {
                document.getElementById('tile-dropdown').classList.remove('show');
            }
        });

        // Initial load
        loadEvents();

        // Auto-polling
        setInterval(() => {
            const today = new Date().toISOString().split('T')[0];
            if (datePicker.value === today) {
                loadEvents();
            }
        }, mapConfig.polling_interval * 1000);
    </script>
</body>
</html>
