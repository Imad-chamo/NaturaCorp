<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="crm-page-title">Pharmacies</div>
            <div class="crm-page-sub">Gestion du réseau officinal partenaire</div>
        </div>
        <button @click="modalMode = 'create'; editingPharmacie = {}; modalOpen = true"
                class="btn-primary">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouvelle pharmacie
        </button>
    </x-slot>

    <script>
        window.pharmaciesFromLaravel = @json($pharmacies->items());
    </script>

    @if(session('success'))
        <div class="flash-success">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div x-data="initPharmacieTable(window.pharmaciesFromLaravel)">

        <!-- Toolbar -->
        <div class="crm-toolbar">
            <div style="position:relative;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--c-muted);">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
                <input type="text" x-model="search" placeholder="Rechercher nom, ville…" class="crm-search" style="width:220px;">
            </div>

            <select x-model="filterStatut" class="crm-input" style="width:auto; padding:7px 10px;">
                <option value="">Tous statuts</option>
                <option value="client_actif">Actif</option>
                <option value="prospect">Prospect</option>
                <option value="client_inactif">Inactif</option>
            </select>

            <span style="margin-left:auto; font-family:'DM Mono',monospace; font-size:11px; color:var(--c-muted);"
                  x-text="filteredPharmacies().length + ' résultat(s)'"></span>
        </div>

        <!-- Table -->
        <div class="crm-table-wrap">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Ville</th>
                        <th>Statut</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Commercial</th>
                        <th class="td-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="pharmacie in filteredPharmacies()" :key="pharmacie.id">
                        <tr style="cursor:pointer;" @click="window.location.href = `/pharmacies/${pharmacie.id}`">
                            <td>
                                <span style="font-weight:500; color:var(--c-text);" x-text="pharmacie.nom"></span>
                            </td>
                            <td class="td-mono" x-text="pharmacie.ville"></td>
                            <td>
                                <span class="badge"
                                    :class="{
                                        'badge-green': pharmacie.statut === 'client_actif',
                                        'badge-amber': pharmacie.statut === 'prospect',
                                        'badge-gray':  pharmacie.statut === 'client_inactif',
                                    }"
                                    x-text="statutLabel(pharmacie.statut)">
                                </span>
                            </td>
                            <td class="td-mono" style="font-size:12px;" x-text="pharmacie.email || '—'"></td>
                            <td class="td-mono" style="font-size:12px;" x-text="pharmacie.telephone || '—'"></td>
                            <td x-text="pharmacie.commercial?.name || '—'"></td>
                            <td class="td-right">
                                <div style="display:flex; align-items:center; justify-content:flex-end; gap:12px;" @click.stop>
                                    <button @click="modalMode = 'edit'; editingPharmacie = pharmacie; modalOpen = true"
                                            class="link-edit">Modifier</button>
                                    <form method="POST" :action="`/pharmacies/${pharmacie.id}`"
                                          @submit.prevent="if(confirm('Supprimer cette pharmacie ?')) $el.submit()">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="link-del" style="background:transparent; border:none; cursor:pointer;">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredPharmacies().length === 0">
                        <tr>
                            <td colspan="7" style="text-align:center; padding:40px; color:var(--c-faint); font-family:'DM Mono',monospace; font-size:12px;">
                                Aucune pharmacie trouvée
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <x-pharmacie-form-modal :commerciaux="$commerciaux" />
    </div>

    @if($pharmacies->hasPages())
        <div style="margin-top:16px;">
            {{ $pharmacies->links() }}
        </div>
    @endif

    <script>
        function initPharmacieTable(pharmacies) {
            return {
                search: '',
                filterStatut: '',
                modalOpen: false,
                modalMode: 'create',
                editingPharmacie: {},
                pharmacies: pharmacies,

                filteredPharmacies() {
                    return this.pharmacies.filter(p => {
                        const s = this.search.toLowerCase();
                        const matchSearch = !s || p.nom.toLowerCase().includes(s) || (p.ville && p.ville.toLowerCase().includes(s));
                        const matchStatut = !this.filterStatut || p.statut === this.filterStatut;
                        return matchSearch && matchStatut;
                    });
                },

                statutLabel(statut) {
                    return { client_actif: 'Actif', client_inactif: 'Inactif', prospect: 'Prospect' }[statut] ?? statut;
                }
            }
        }
    </script>
</x-app-layout>
