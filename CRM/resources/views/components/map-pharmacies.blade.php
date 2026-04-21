@props([
    'pharmacies' => [],
    'height' => 'h-96',
    'showFilters' => false,
    'commerciaux' => [],
    'villes' => [],
    'statuts' => [],
])

<div
    x-data="initMap(@js($pharmacies), @js($commerciaux), @js($villes), @js($statuts))"
    x-init="setupMap()"
    class="w-full"
>
    <div class="rounded border border-gray-200 shadow overflow-hidden">
        <div id="map-container" class="relative w-full {{ $height }}">
            <div id="map" class="absolute inset-0 z-0"></div>
        </div>
    </div>
</div>

@once
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            function initMap(pharmacies, commerciaux, villes, statuts) {
                return {
                    pharmacies, commerciaux, villes, statuts,
                    map: null,
                    markers: [],
                    filters: { commercial: '', ville: '', statut: '' },

                    setupMap() {
                        const container = document.getElementById('map');

                        // Empêche l'erreur si déjà initialisé
                        if (container._leaflet_id) {
                            container._leaflet_id = null;
                        }

                        this.map = L.map(container).setView([46.6, 2.2], 6);

                        L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors',
                            maxZoom: 18,
                        }).addTo(this.map);

                        this.updateMarkers();
                    },

                    filteredPharmacies() {
                        return this.pharmacies.filter(p =>
                            (!this.filters.commercial || p.commercial?.name === this.filters.commercial) &&
                            (!this.filters.ville || p.ville === this.filters.ville) &&
                            (!this.filters.statut || p.statut === this.filters.statut)
                        );
                    },

                    updateMarkers() {
                        this.markers.forEach(m => this.map.removeLayer(m));
                        this.markers = [];

                        this.filteredPharmacies().forEach(p => {
                            if (!p.latitude || !p.longitude) return;

                            const marker = L.marker([p.latitude, p.longitude]).addTo(this.map);
                            marker.bindPopup(`
                                <div class="text-sm">
                                    <strong>${p.nom}</strong><br>
                                    ${p.ville} – ${this.formatStatut(p.statut)}<br>
                                    ${p.commercial?.name || ''}
                                </div>
                            `);
                            this.markers.push(marker);
                        });
                    },

                    formatStatut(value) {
                        return {
                            'client_actif': 'Actif',
                            'client_inactif': 'Inactif',
                            'prospect': 'Prospect'
                        }[value] || value;
                    },
                }
            }
        </script>
    @endpush
@endonce
