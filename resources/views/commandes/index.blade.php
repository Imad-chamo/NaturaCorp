<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="crm-page-title">Commandes</div>
            <div class="crm-page-sub">Suivi des commandes en cours et historique</div>
        </div>
        <button onclick="window.dispatchEvent(new CustomEvent('open-create-commande'))"
            class="btn-primary">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouvelle commande
        </button>
    </x-slot>

    <script>
        window.commandesFromLaravel  = @json($commandes->items());
        window.pharmaciesFromLaravel = @json($pharmacies);
        window.statutsFromLaravel    = @json($statuts);
        window.produitsFromLaravel   = @json($produits);
    </script>

    @if(session('success'))
        <div class="flash-success">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div x-data="initCommandeTable(window.commandesFromLaravel, window.produitsFromLaravel)"
         @open-create-commande.window="
             modalMode = 'create';
             editingCommande = {
                 pharmacie_id: window.pharmaciesFromLaravel[0]?.id ?? null,
                 produit_id:   window.produitsFromLaravel[0]?.id ?? null,
                 tarif_unitaire: window.produitsFromLaravel[0]?.tarif_unitaire ?? '',
                 date_commande:  new Date().toISOString().substring(0,10),
                 statut: 'en_cours',
                 quantite: 1,
             };
             modalOpen = true;
         ">

        <!-- Toolbar -->
        <div class="crm-toolbar">
            <div style="position:relative;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--c-muted);">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
                <input type="text" x-model="search" placeholder="Rechercher pharmacie…" class="crm-search" style="width:220px;">
            </div>

            <select x-model="filterStatut" class="crm-input" style="width:auto; padding:7px 10px;">
                <option value="">Tous statuts</option>
                @foreach($statuts as $statut)
                    <option value="{{ $statut->value }}">{{ $statut->label() }}</option>
                @endforeach
            </select>

            <span style="margin-left:auto; font-family:'DM Mono',monospace; font-size:11px; color:var(--c-muted);"
                  x-text="filteredCommandes().length + ' résultat(s)'"></span>
        </div>

        <!-- Table -->
        <div class="crm-table-wrap">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Pharmacie</th>
                        <th>Produit</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th class="td-right">Qté</th>
                        <th class="td-right">Tarif u.</th>
                        <th class="td-right">Total</th>
                        <th class="td-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(commande, index) in filteredCommandes()" :key="commande.id">
                        <tr>
                            <td class="td-mono" x-text="'#' + String(commande.id).padStart(4,'0')"></td>
                            <td>
                                <span style="font-weight:500; color:var(--c-text);" x-text="commande.pharmacie.nom"></span>
                                <div class="td-mono" style="font-size:11px; margin-top:1px;" x-text="commande.pharmacie.ville"></div>
                            </td>
                            <td>
                                <span class="td-mono" style="font-size:12px;" x-text="commande.produit?.nom ?? '—'"></span>
                            </td>
                            <td class="td-mono" style="font-size:12px;" x-text="formatDate(commande.date_commande)"></td>
                            <td>
                                <span class="badge"
                                      :class="{
                                          'badge-green': commande.statut === 'livree',
                                          'badge-amber': commande.statut === 'en_cours',
                                          'badge-blue':  commande.statut === 'validee',
                                          'badge-gray':  commande.statut === 'annulee',
                                      }"
                                      x-text="commande.statut_label">
                                </span>
                            </td>
                            <td class="td-right td-mono" x-text="commande.quantite"></td>
                            <td class="td-right td-mono" x-text="parseFloat(commande.tarif_unitaire).toFixed(2) + ' €'"></td>
                            <td class="td-right td-mono" style="color:var(--c-green);"
                                x-text="(parseFloat(commande.tarif_unitaire) * commande.quantite).toFixed(2) + ' €'"></td>
                            <td class="td-right">
                                <div style="display:flex; align-items:center; justify-content:flex-end; gap:12px;">
                                    <button @click="modalMode = 'edit'; editingCommande = { ...commande, quantite_initiale: commande.quantite }; modalOpen = true"
                                            class="link-edit">Modifier</button>
                                    <form method="POST" :action="`/commandes/${commande.id}`"
                                          @submit.prevent="if(confirm('Supprimer cette commande ?')) $el.submit()">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="link-del" style="background:transparent; border:none; cursor:pointer;">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredCommandes().length === 0">
                        <tr>
                            <td colspan="9" style="text-align:center; padding:40px; color:var(--c-faint); font-family:'DM Mono',monospace; font-size:12px;">
                                Aucune commande trouvée
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <x-commande-form-modal :pharmacies="$pharmacies" :statuts="$statuts" :produits="$produits" />
    </div>

    @if($commandes->hasPages())
        <div style="margin-top:16px;">
            {{ $commandes->links() }}
        </div>
    @endif

    <script>
        function initCommandeTable(commandes, produits) {
            return {
                search: '',
                filterStatut: '',
                modalOpen: false,
                modalMode: 'create',
                editingCommande: {},
                commandes: commandes,
                produits: produits,

                filteredCommandes() {
                    return this.commandes
                        .filter(c => {
                            const s = this.search.toLowerCase();
                            const matchSearch = !s || c.pharmacie.nom.toLowerCase().includes(s);
                            const matchStatut = !this.filterStatut || c.statut === this.filterStatut;
                            return matchSearch && matchStatut;
                        })
                        .sort((a, b) => new Date(b.date_commande) - new Date(a.date_commande));
                },

                formatDate(dateString) {
                    if (!dateString) return '—';
                    return new Date(dateString).toLocaleDateString('fr-FR');
                }
            };
        }
    </script>
</x-app-layout>
