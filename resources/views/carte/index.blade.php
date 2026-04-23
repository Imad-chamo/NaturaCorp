<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="crm-page-title">Carte des pharmacies</div>
            <div class="crm-page-sub">Visualisation géographique du réseau partenaire</div>
        </div>
        <div style="display:flex; gap:20px;">
            <div style="text-align:center;">
                <div style="font-family:'Fraunces',serif; font-size:22px; font-weight:400; color:var(--c-green-d); line-height:1;">{{ $pharmacies->where('statut','client_actif')->count() }}</div>
                <div style="font-size:10px; color:var(--c-muted); letter-spacing:0.06em; text-transform:uppercase; font-weight:700;">Actifs</div>
            </div>
            <div style="width:1px; height:36px; background:var(--c-border); align-self:center;"></div>
            <div style="text-align:center;">
                <div style="font-family:'Fraunces',serif; font-size:22px; font-weight:400; color:var(--c-amber); line-height:1;">{{ $pharmacies->where('statut','prospect')->count() }}</div>
                <div style="font-size:10px; color:var(--c-muted); letter-spacing:0.06em; text-transform:uppercase; font-weight:700;">Prospects</div>
            </div>
            <div style="width:1px; height:36px; background:var(--c-border); align-self:center;"></div>
            <div style="text-align:center;">
                <div style="font-family:'Fraunces',serif; font-size:22px; font-weight:400; color:#9CA3AF; line-height:1;">{{ $pharmacies->where('statut','client_inactif')->count() }}</div>
                <div style="font-size:10px; color:var(--c-muted); letter-spacing:0.06em; text-transform:uppercase; font-weight:700;">Inactifs</div>
            </div>
            <div style="width:1px; height:36px; background:var(--c-border); align-self:center;"></div>
            <div style="text-align:center;">
                <div style="font-family:'Fraunces',serif; font-size:22px; font-weight:400; color:var(--c-text); line-height:1;">{{ $pharmacies->count() }}</div>
                <div style="font-size:10px; color:var(--c-muted); letter-spacing:0.06em; text-transform:uppercase; font-weight:700;">Total</div>
            </div>
        </div>
    </x-slot>

    <div x-data="initCarte(@js($pharmacies), @js($commerciaux->values()), @js($villes->values()))"
         x-init="setupMap()"
         style="display:grid; grid-template-columns:300px 1fr; gap:16px; align-items:start;">

        <!-- ── Left panel ── -->
        <div style="display:flex; flex-direction:column; gap:0; background:var(--c-surface); border:1px solid var(--c-border); border-radius:12px; overflow:hidden; box-shadow:var(--shadow-sm); height:calc(100vh - 198px);">

            <!-- Filters -->
            <div style="padding:16px; border-bottom:1px solid var(--c-border); flex-shrink:0;">
                <div class="section-label" style="margin-bottom:12px;">Filtres</div>

                <!-- Search -->
                <div style="position:relative; margin-bottom:10px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--c-faint); pointer-events:none;">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                    <input type="text" x-model="search" placeholder="Rechercher une pharmacie…"
                           class="crm-search" style="width:100%;">
                </div>

                <select x-model="filterStatut" class="crm-input" style="margin-bottom:8px;">
                    <option value="">Tous les statuts</option>
                    <option value="client_actif">Actif</option>
                    <option value="prospect">Prospect</option>
                    <option value="client_inactif">Inactif</option>
                </select>

                <select x-model="filterCommercial" class="crm-input" style="margin-bottom:8px;">
                    <option value="">Tous les commerciaux</option>
                    <template x-for="nom in commerciaux" :key="nom">
                        <option :value="nom" x-text="nom"></option>
                    </template>
                </select>

                <select x-model="filterVille" class="crm-input">
                    <option value="">Toutes les villes</option>
                    <template x-for="v in villes" :key="v">
                        <option :value="v" x-text="v"></option>
                    </template>
                </select>

                <div style="display:flex; align-items:center; justify-content:space-between; margin-top:10px;">
                    <span style="font-size:12px; color:var(--c-muted);">
                        <span style="font-family:'DM Mono',monospace; font-weight:600; color:var(--c-text);" x-text="filtered.length"></span>
                        résultat(s)
                    </span>
                    <button @click="search=''; filterStatut=''; filterVille=''; filterCommercial='';"
                            x-show="search || filterStatut || filterVille || filterCommercial"
                            class="btn-secondary" style="padding:5px 10px; font-size:11px; gap:4px;">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Réinitialiser
                    </button>
                </div>
            </div>

            <!-- Legend -->
            <div style="padding:10px 16px; border-bottom:1px solid var(--c-border); flex-shrink:0; display:flex; gap:14px;">
                <div style="display:flex; align-items:center; gap:5px; font-size:11px; color:var(--c-muted);">
                    <span style="width:9px; height:9px; background:#16A34A; border-radius:50%; border:1.5px solid #fff; box-shadow:0 1px 3px rgba(0,0,0,0.2); flex-shrink:0;"></span> Actif
                </div>
                <div style="display:flex; align-items:center; gap:5px; font-size:11px; color:var(--c-muted);">
                    <span style="width:9px; height:9px; background:#D97706; border-radius:50%; border:1.5px solid #fff; box-shadow:0 1px 3px rgba(0,0,0,0.2); flex-shrink:0;"></span> Prospect
                </div>
                <div style="display:flex; align-items:center; gap:5px; font-size:11px; color:var(--c-muted);">
                    <span style="width:9px; height:9px; background:#9CA3AF; border-radius:50%; border:1.5px solid #fff; box-shadow:0 1px 3px rgba(0,0,0,0.2); flex-shrink:0;"></span> Inactif
                </div>
            </div>

            <!-- Pharmacy list -->
            <div style="flex:1; overflow-y:auto; padding:8px;">
                <template x-for="p in filtered" :key="p.id">
                    <div @click="flyTo(p)"
                         style="display:flex; align-items:center; gap:10px; padding:9px 10px; border-radius:8px; cursor:pointer; transition:background 0.12s; border:1px solid transparent; margin-bottom:2px;"
                         :style="activeId === p.id ? 'background:var(--c-green-l); border-color:rgba(22,163,74,0.2);' : ''"
                         onmouseover="if(!this.style.background.includes('green')) this.style.background='var(--c-hover)'"
                         onmouseout="if(!this.style.background.includes('green')) this.style.background='transparent'">
                        <!-- Dot -->
                        <span style="width:9px; height:9px; border-radius:50%; flex-shrink:0; border:1.5px solid rgba(255,255,255,0.8); box-shadow:0 1px 3px rgba(0,0,0,0.15);"
                              :style="{ background: p.statut === 'client_actif' ? '#16A34A' : p.statut === 'prospect' ? '#D97706' : '#9CA3AF' }"></span>
                        <div style="flex:1; min-width:0;">
                            <div style="font-size:13px; font-weight:500; color:var(--c-text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" x-text="p.nom"></div>
                            <div style="font-size:11px; color:var(--c-faint); font-family:'DM Mono',monospace; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" x-text="(p.code_postal || '') + (p.ville ? ' ' + p.ville : '')"></div>
                        </div>
                        <template x-if="!p.latitude">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2" title="Sans coordonnées"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </template>
                    </div>
                </template>

                <template x-if="filtered.length === 0">
                    <div style="text-align:center; padding:32px 16px; color:var(--c-faint); font-size:12px; font-family:'DM Mono',monospace;">
                        Aucune pharmacie trouvée
                    </div>
                </template>
            </div>
        </div>

        <!-- ── Map ── -->
        <div style="background:var(--c-surface); border:1px solid var(--c-border); border-radius:12px; overflow:hidden; box-shadow:var(--shadow-sm); height:calc(100vh - 198px); position:relative;">
            <div id="leaflet-map" style="width:100%; height:100%; z-index:0;"></div>

            <!-- No-coords notice -->
            <template x-if="pharmacies.filter(p => !p.latitude).length > 0">
                <div style="position:absolute; bottom:16px; left:50%; transform:translateX(-50%); z-index:400; background:#FEF3C7; border:1px solid #FDE68A; border-radius:8px; padding:7px 14px; font-size:12px; color:#78350F; display:flex; align-items:center; gap:7px; pointer-events:none; white-space:nowrap; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span x-text="pharmacies.filter(p => !p.latitude).length + ' pharmacie(s) sans coordonnées GPS'"></span>
                </div>
            </template>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
        <style>
            /* Custom cluster colors */
            .marker-cluster-small  { background-color: rgba(22,163,74,0.18); }
            .marker-cluster-small div  { background-color: rgba(22,163,74,0.85); color:#fff; font-family:'DM Mono',monospace; font-weight:700; font-size:12px; }
            .marker-cluster-medium { background-color: rgba(22,163,74,0.22); }
            .marker-cluster-medium div { background-color: rgba(22,163,74,0.9); color:#fff; font-family:'DM Mono',monospace; font-weight:700; font-size:12px; }
            .marker-cluster-large  { background-color: rgba(21,128,61,0.25); }
            .marker-cluster-large div  { background-color: rgba(21,128,61,0.95); color:#fff; font-family:'DM Mono',monospace; font-weight:700; font-size:13px; }
            /* Popup styling */
            .leaflet-popup-content-wrapper {
                border-radius: 10px !important;
                box-shadow: 0 8px 28px rgba(24,39,28,0.14), 0 2px 8px rgba(24,39,28,0.08) !important;
                border: 1px solid #E0E9E1 !important;
                padding: 0 !important;
            }
            .leaflet-popup-content { margin: 0 !important; }
            .leaflet-popup-tip { background: #fff !important; }
            .leaflet-popup-close-button {
                top: 8px !important; right: 10px !important;
                color: #9DBDA0 !important; font-size: 16px !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
        <script>
        function initCarte(pharmacies, commerciaux, villes) {
            return {
                pharmacies,
                commerciaux,
                villes,
                search: '',
                filterStatut: '',
                filterVille: '',
                filterCommercial: '',
                activeId: null,
                map: null,
                clusterGroup: null,
                markerMap: {},

                get filtered() {
                    const s = this.search.toLowerCase();
                    return this.pharmacies.filter(p =>
                        (!s || p.nom.toLowerCase().includes(s) || (p.ville || '').toLowerCase().includes(s) || (p.code_postal || '').includes(s)) &&
                        (!this.filterStatut     || p.statut === this.filterStatut) &&
                        (!this.filterVille      || p.ville === this.filterVille) &&
                        (!this.filterCommercial || p.commercial?.name === this.filterCommercial)
                    );
                },

                setupMap() {
                    const container = document.getElementById('leaflet-map');
                    if (container._leaflet_id) container._leaflet_id = null;

                    this.map = L.map(container, { zoomControl: false }).setView([46.6, 2.2], 6);

                    // Custom zoom control position
                    L.control.zoom({ position: 'topright' }).addTo(this.map);

                    // Tile layer — clean light style
                    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> © <a href="https://carto.com/">CARTO</a>',
                        subdomains: 'abcd',
                        maxZoom: 19,
                    }).addTo(this.map);

                    this.clusterGroup = L.markerClusterGroup({
                        maxClusterRadius: 50,
                        spiderfyOnMaxZoom: true,
                        showCoverageOnHover: false,
                        zoomToBoundsOnClick: true,
                        iconCreateFunction: (cluster) => {
                            const count = cluster.getChildCount();
                            const size = count < 10 ? 32 : count < 50 ? 38 : 44;
                            return L.divIcon({
                                html: `<div style="
                                    width:${size}px; height:${size}px;
                                    background:rgba(22,163,74,0.9);
                                    border:3px solid #fff;
                                    border-radius:50%;
                                    box-shadow:0 3px 10px rgba(22,163,74,0.35);
                                    display:flex; align-items:center; justify-content:center;
                                    color:#fff; font-family:'DM Mono',monospace; font-weight:700; font-size:${count < 10 ? 12 : 11}px;
                                ">${count}</div>`,
                                className: '',
                                iconSize: [size, size],
                                iconAnchor: [size/2, size/2],
                            });
                        }
                    });
                    this.map.addLayer(this.clusterGroup);

                    // Build all markers once
                    this.pharmacies.forEach(p => {
                        if (!p.latitude || !p.longitude) return;
                        const marker = L.marker([p.latitude, p.longitude], { icon: this.makeIcon(p.statut) });
                        marker.bindPopup(this.makePopup(p), { maxWidth: 260, minWidth: 220 });
                        marker.on('click', () => { this.activeId = p.id; });
                        this.markerMap[p.id] = marker;
                    });

                    this.updateMarkers();

                    // Watch filters
                    this.$watch('search',           () => this.updateMarkers());
                    this.$watch('filterStatut',     () => this.updateMarkers());
                    this.$watch('filterVille',      () => this.updateMarkers());
                    this.$watch('filterCommercial', () => this.updateMarkers());
                },

                updateMarkers() {
                    this.clusterGroup.clearLayers();
                    const visible = this.filtered;
                    visible.forEach(p => {
                        if (this.markerMap[p.id]) {
                            this.clusterGroup.addLayer(this.markerMap[p.id]);
                        }
                    });
                    if (this.clusterGroup.getLayers().length > 0) {
                        try {
                            this.map.fitBounds(this.clusterGroup.getBounds().pad(0.15), { maxZoom: 13, animate: true });
                        } catch(e) {}
                    }
                },

                flyTo(p) {
                    this.activeId = p.id;
                    if (!p.latitude || !p.longitude) return;
                    this.map.flyTo([p.latitude, p.longitude], 14, { duration: 0.8 });
                    setTimeout(() => {
                        const m = this.markerMap[p.id];
                        if (m) { this.clusterGroup.zoomToShowLayer(m, () => m.openPopup()); }
                    }, 850);
                },

                makeIcon(statut) {
                    const colors = { 'client_actif': '#16A34A', 'prospect': '#D97706', 'client_inactif': '#9CA3AF' };
                    const c = colors[statut] || '#9CA3AF';
                    return L.divIcon({
                        html: `<div style="
                            width:14px; height:14px;
                            background:${c};
                            border:2.5px solid #fff;
                            border-radius:50%;
                            box-shadow:0 2px 8px rgba(0,0,0,0.25), 0 0 0 2px ${c}40;
                            transition:transform 0.15s;
                        "></div>`,
                        className: '',
                        iconSize: [14, 14],
                        iconAnchor: [7, 7],
                        popupAnchor: [0, -12],
                    });
                },

                makePopup(p) {
                    const badges = {
                        'client_actif':  ['#DCFCE7', '#166534', 'Actif'],
                        'prospect':      ['#FEF3C7', '#78350F', 'Prospect'],
                        'client_inactif':['#F1F3F2', '#4B5563', 'Inactif'],
                    };
                    const [bg, col, lbl] = badges[p.statut] || ['#F1F3F2', '#4B5563', p.statut];
                    const addr = [p.adresse, p.code_postal, p.ville].filter(Boolean).join(', ');
                    const nbCmds = p.commandes_count || 0;
                    return `<div style="font-family:'DM Sans',sans-serif; padding:14px 16px;">
                        <div style="font-weight:600; font-size:14px; color:#18271C; margin-bottom:3px; padding-right:20px;">${p.nom}</div>
                        ${addr ? `<div style="font-size:12px; color:#5E8264; margin-bottom:6px; line-height:1.5;">${addr}</div>` : ''}
                        ${p.email ? `<div style="font-size:12px; color:#9DBDA0; margin-bottom:8px;">${p.email}</div>` : ''}
                        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:10px;">
                            <span style="font-size:11px; padding:2px 9px; border-radius:20px; background:${bg}; color:${col}; font-weight:600;">${lbl}</span>
                            <span style="font-size:11px; color:#9DBDA0; font-family:'DM Mono',monospace;">${nbCmds} commande${nbCmds !== 1 ? 's' : ''}</span>
                            ${p.commercial?.name ? `<span style="font-size:11px; color:#9DBDA0;">· ${p.commercial.name}</span>` : ''}
                        </div>
                        <a href="/pharmacies/${p.id}"
                           style="display:inline-flex; align-items:center; gap:5px; font-size:12px; color:#15803D; font-weight:600; text-decoration:none; background:#DCFCE7; padding:5px 12px; border-radius:6px; transition:background 0.15s;"
                           onmouseover="this.style.background='#BBF7D0'" onmouseout="this.style.background='#DCFCE7'">
                            Voir la fiche
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>`;
                },
            };
        }
        </script>
    @endpush
</x-app-layout>
