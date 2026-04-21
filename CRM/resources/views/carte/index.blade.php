<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="crm-page-title">Carte des pharmacies</div>
            <div class="crm-page-sub">Visualisation géographique du réseau</div>
        </div>
    </x-slot>

    <div style="display:grid; grid-template-columns:260px 1fr; gap:16px; align-items:start;"
         x-data="initCarteFiltrage(@js($pharmacies), @js($commerciaux), @js($villes), @js($statuts))"
         x-init="update()">

        <!-- Filtres -->
        <div class="crm-panel" style="display:flex; flex-direction:column; gap:16px;">
            <div class="section-label">Filtres</div>

            <div>
                <label class="form-label">Commercial</label>
                <select x-model="filters.commercial" @change="update()" class="crm-input">
                    <option value="">Tous</option>
                    <template x-for="nom in commerciaux" :key="nom">
                        <option :value="nom" x-text="nom"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="form-label">Ville</label>
                <select x-model="filters.ville" @change="update()" class="crm-input">
                    <option value="">Toutes</option>
                    <template x-for="ville in villes" :key="ville">
                        <option :value="ville" x-text="ville"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="form-label">Statut</label>
                <select x-model="filters.statut" @change="update()" class="crm-input">
                    <option value="">Tous</option>
                    <template x-for="statut in statuts" :key="statut">
                        <option :value="statut" x-text="formatStatut(statut)"></option>
                    </template>
                </select>
            </div>

            <button type="button"
                    @click="filters = { commercial: '', ville: '', statut: '' }; update();"
                    class="btn-secondary" style="width:100%; justify-content:center;">
                Réinitialiser
            </button>

            <!-- Résumé -->
            <div style="border-top:1px solid var(--c-border); padding-top:14px; display:flex; flex-direction:column; gap:6px;">
                <div style="font-size:12px; color:var(--c-text);">
                    <span style="font-family:'Fraunces',Georgia,serif; font-size:20px; font-weight:400; color:var(--c-text);" x-text="filtered.length"></span>
                    <span style="font-size:12px; color:var(--c-muted);"> pharmacie(s) affichée(s)</span>
                </div>
                <template x-for="s in statuts" :key="s">
                    <div style="display:flex; align-items:center; justify-content:space-between; font-size:12px;">
                        <div style="display:flex; align-items:center; gap:6px;">
                            <span style="width:7px; height:7px; border-radius:50%; flex-shrink:0;"
                                  :style="{ background: s === 'client_actif' ? '#16A34A' : (s === 'prospect' ? '#D97706' : '#9CA3AF') }"></span>
                            <span style="color:var(--c-muted);" x-text="formatStatut(s)"></span>
                        </div>
                        <span style="font-family:'DM Mono',monospace; font-size:11px; color:var(--c-text); font-weight:500;"
                              x-text="filtered.filter(p => p.statut === s).length"></span>
                    </div>
                </template>
            </div>
        </div>

        <!-- Carte -->
        <div class="crm-panel" style="padding:0; overflow:hidden;">
            <x-map-pharmacies
                :pharmacies="$pharmacies"
                :commerciaux="$commerciaux"
                :villes="$villes"
                :statuts="$statuts"
                height="aspect-[3/2]"
            />
        </div>
    </div>

    @push('scripts')
        <script>
            function initCarteFiltrage(pharmacies, commerciaux, villes, statuts) {
                return {
                    pharmacies, commerciaux, villes, statuts,
                    filters: { commercial: '', ville: '', statut: '' },
                    filtered: pharmacies,

                    update() {
                        this.filtered = this.pharmacies.filter(p =>
                            (!this.filters.commercial || p.commercial?.name === this.filters.commercial) &&
                            (!this.filters.ville || p.ville === this.filters.ville) &&
                            (!this.filters.statut || p.statut === this.filters.statut)
                        );
                    },

                    formatStatut(value) {
                        return { 'client_actif': 'Actif', 'client_inactif': 'Inactif', 'prospect': 'Prospect' }[value] || value;
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
