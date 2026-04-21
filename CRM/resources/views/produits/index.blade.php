<x-app-layout>

    <x-slot name="header">
        <div>
            <div class="crm-page-title">Catalogue produits</div>
            <div class="crm-page-sub">{{ $stats['total'] }} produits · {{ $stats['actifs'] }} actifs</div>
        </div>
        <button onclick="document.getElementById('modal-produit').classList.remove('hidden')"
                class="btn-primary">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouveau produit
        </button>
    </x-slot>

    @if(session('success'))
        <div class="flash-success">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- KPI Cards --}}
    <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:12px; margin-bottom:20px;">
        <div class="kpi-card kpi-muted">
            <div class="kpi-label">Total</div>
            <div class="kpi-value">{{ $stats['total'] }}</div>
        </div>
        <div class="kpi-card kpi-green">
            <div class="kpi-label">Actifs</div>
            <div class="kpi-value">{{ $stats['actifs'] }}</div>
        </div>
        <div class="kpi-card kpi-red">
            <div class="kpi-label">Ruptures</div>
            <div class="kpi-value">{{ $stats['ruptures'] }}</div>
        </div>
        <div class="kpi-card kpi-amber">
            <div class="kpi-label">Stock faible</div>
            <div class="kpi-value">{{ $stats['stock_faible'] }}</div>
        </div>
        <div class="kpi-card kpi-blue">
            <div class="kpi-label">Valeur stock</div>
            <div class="kpi-value" style="font-size:18px;">{{ number_format($stats['valeur_stock'], 0, ',', ' ') }} €</div>
        </div>
    </div>

    <div x-data="produitPage()">

        {{-- Toolbar --}}
        <div class="crm-toolbar">
            <div style="position:relative;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--c-muted);">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
                <input type="text" x-model="search" placeholder="Rechercher…" class="crm-search" style="width:180px;">
            </div>

            <select x-model="filterCategorie" class="crm-input" style="width:auto; padding:7px 10px;">
                <option value="">Toutes catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>

            <div style="display:flex; gap:4px; flex-wrap:wrap;">
                <button @click="filterStatut = ''"
                        :style="filterStatut === '' ? 'background:var(--c-raised); color:var(--c-text); border-color:var(--c-bolder);' : ''"
                        style="background:transparent; border:1px solid var(--c-border); color:var(--c-muted); font-size:11px; font-family:\'DM Mono\',monospace; padding:5px 10px; border-radius:2px; cursor:pointer; transition:all 0.15s; letter-spacing:0.05em;">
                    TOUS
                </button>
                <button @click="filterStatut = 'actif'"
                        :style="filterStatut === 'actif' ? 'background:var(--c-green-m); color:var(--c-green); border-color:rgba(34,197,94,0.3);' : ''"
                        style="background:transparent; border:1px solid var(--c-border); color:var(--c-muted); font-size:11px; font-family:\'DM Mono\',monospace; padding:5px 10px; border-radius:2px; cursor:pointer; transition:all 0.15s;">
                    ACTIFS
                </button>
                <button @click="filterStatut = 'rupture'"
                        :style="filterStatut === 'rupture' ? 'background:var(--c-red-m); color:var(--c-red); border-color:rgba(239,68,68,0.3);' : ''"
                        style="background:transparent; border:1px solid var(--c-border); color:var(--c-muted); font-size:11px; font-family:\'DM Mono\',monospace; padding:5px 10px; border-radius:2px; cursor:pointer; transition:all 0.15s;">
                    RUPTURE
                </button>
                <button @click="filterStatut = 'faible'"
                        :style="filterStatut === 'faible' ? 'background:var(--c-amber-m); color:var(--c-amber); border-color:rgba(217,119,6,0.3);' : ''"
                        style="background:transparent; border:1px solid var(--c-border); color:var(--c-muted); font-size:11px; font-family:\'DM Mono\',monospace; padding:5px 10px; border-radius:2px; cursor:pointer; transition:all 0.15s;">
                    FAIBLE
                </button>
            </div>

            <div style="margin-left:auto; display:flex; align-items:center; gap:10px;">
                <span style="font-family:'DM Mono',monospace; font-size:11px; color:var(--c-muted);"
                      x-text="filteredProduits().length + ' résultat(s)'"></span>
                <select x-model="sortBy" class="crm-input" style="width:auto; padding:7px 10px;">
                    <option value="nom">A → Z</option>
                    <option value="stock_asc">Stock ↑</option>
                    <option value="stock_desc">Stock ↓</option>
                    <option value="tarif_asc">Prix ↑</option>
                    <option value="tarif_desc">Prix ↓</option>
                </select>
            </div>
        </div>

        {{-- Grille produits --}}
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:14px;">
            <template x-for="p in filteredProduits()" :key="p.id">
                <div style="background:var(--c-surface); border:1px solid var(--c-border); border-radius:12px; overflow:hidden; display:flex; flex-direction:column; box-shadow:var(--shadow-sm); transition:box-shadow 0.2s, transform 0.2s;"
                     onmouseover="this.style.boxShadow='var(--shadow-md)'; this.style.transform='translateY(-3px)'"
                     onmouseout="this.style.boxShadow='var(--shadow-sm)'; this.style.transform='translateY(0)'">

                    {{-- Image produit --}}
                    <div style="position:relative; height:160px; overflow:hidden; background:var(--c-raised);">
                        <img :src="p.image_url" :alt="p.nom"
                             style="width:100%; height:100%; object-fit:cover; transition:transform 0.4s ease;"
                             onerror="this.style.display='none'"
                             onmouseover="this.style.transform='scale(1.04)'"
                             onmouseout="this.style.transform='scale(1)'">
                        {{-- Overlay catégorie --}}
                        <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(24,39,28,0.55) 0%, transparent 60%);"></div>
                        {{-- Badge statut --}}
                        <div style="position:absolute; top:10px; right:10px;">
                            <span class="badge"
                                  :class="p.is_actif ? 'badge-green' : 'badge-gray'"
                                  x-text="p.is_actif ? 'Actif' : 'Inactif'"
                                  style="backdrop-filter:blur(4px); background:rgba(255,255,255,0.9);"></span>
                        </div>
                        {{-- Catégorie + REF en bas de l'image --}}
                        <div style="position:absolute; bottom:10px; left:12px; right:12px; display:flex; align-items:center; justify-content:space-between;">
                            <template x-if="p.categorie">
                                <span style="font-size:10px; font-family:'DM Mono',monospace; font-weight:600; color:rgba(255,255,255,0.85); letter-spacing:0.08em; text-transform:uppercase;"
                                      x-text="p.categorie"></span>
                            </template>
                            <span style="font-size:10px; font-family:'DM Mono',monospace; color:rgba(255,255,255,0.5);" x-text="'REF-' + String(p.id).padStart(3,'0')"></span>
                        </div>
                    </div>

                    <div style="padding:14px 16px; flex:1; display:flex; flex-direction:column; gap:10px;">

                        {{-- Nom + prix --}}
                        <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:8px;">
                            <div style="font-weight:600; color:var(--c-text); font-size:14px; line-height:1.3;" x-text="p.nom"></div>
                            <div style="flex-shrink:0; text-align:right;">
                                <span style="font-family:'Fraunces',Georgia,serif; font-size:18px; font-weight:400; color:var(--c-green);" x-text="parseFloat(p.tarif_unitaire).toFixed(2)"></span>
                                <span style="font-size:10px; color:var(--c-faint); display:block; font-family:'DM Mono',monospace;">€/u.</span>
                            </div>
                        </div>

                        {{-- Description --}}
                        <p style="font-size:12px; color:var(--c-muted); line-height:1.5; margin:0; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;"
                           x-text="p.description || 'Aucune description disponible.'"></p>

                        {{-- Stock --}}
                        <div style="padding:9px 11px; border-radius:8px; margin-top:auto;"
                             :style="{
                               background: p.stock === 0 ? 'rgba(220,38,38,0.06)' : (p.stock > 0 && p.stock <= p.stock_alerte ? 'rgba(217,119,6,0.06)' : 'rgba(22,163,74,0.05)'),
                               border: p.stock === 0 ? '1px solid rgba(220,38,38,0.15)' : (p.stock > 0 && p.stock <= p.stock_alerte ? '1px solid rgba(217,119,6,0.15)' : '1px solid var(--c-border)'),
                             }">
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:5px;">
                                <div style="display:flex; align-items:center; gap:5px;">
                                    <span style="width:6px; height:6px; border-radius:50%; flex-shrink:0;"
                                          :style="{ background: p.stock === 0 ? '#DC2626' : (p.stock > 0 && p.stock <= p.stock_alerte ? '#D97706' : '#16A34A') }"></span>
                                    <span style="font-size:10px; font-family:'DM Mono',monospace; font-weight:600; letter-spacing:0.06em;"
                                          :style="{ color: p.stock === 0 ? '#DC2626' : (p.stock > 0 && p.stock <= p.stock_alerte ? '#D97706' : 'var(--c-muted)') }"
                                          x-text="p.stock === 0 ? 'RUPTURE' : (p.stock > p.stock_alerte ? 'EN STOCK' : 'STOCK FAIBLE')"></span>
                                </div>
                                <span style="font-family:'DM Mono',monospace; font-size:11px; font-weight:600;"
                                      :style="{ color: p.stock === 0 ? '#DC2626' : (p.stock > 0 && p.stock <= p.stock_alerte ? '#D97706' : 'var(--c-text)') }"
                                      x-text="p.stock + ' u.'"></span>
                            </div>
                            <div style="height:3px; background:rgba(0,0,0,0.08); border-radius:2px; overflow:hidden;">
                                <div style="height:100%; border-radius:2px; transition:width 0.6s ease;"
                                     :style="{
                                       width: Math.min(100, Math.round(p.stock / Math.max(p.stock_alerte * 4, 1) * 100)) + '%',
                                       background: p.stock === 0 ? '#DC2626' : (p.stock > 0 && p.stock <= p.stock_alerte ? '#D97706' : '#16A34A'),
                                     }"></div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div style="display:flex; gap:8px;">
                            <button @click="openEdit(p)" class="btn-secondary" style="flex:1; justify-content:center; padding:7px 10px; font-size:12px;">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Modifier
                            </button>
                            <form :action="`/produits/${p.id}`" method="POST"
                                  @submit.prevent="if(confirm('Supprimer «\u00a0' + p.nom + '\u00a0» ?')) $el.submit()">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit"
                                        style="background:transparent; border:1px solid var(--c-border); color:var(--c-faint); padding:7px 10px; cursor:pointer; border-radius:7px; transition:all 0.15s; display:flex; align-items:center;"
                                        onmouseover="this.style.borderColor='rgba(220,38,38,0.35)'; this.style.color='#DC2626'; this.style.background='#FEE2E2'"
                                        onmouseout="this.style.borderColor='var(--c-border)'; this.style.color='var(--c-faint)'; this.style.background='transparent'">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6m4-6v6M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </template>

            {{-- État vide --}}
            <template x-if="filteredProduits().length === 0">
                <div style="grid-column:1/-1; text-align:center; padding:60px 20px; background:var(--c-surface); border:1px solid var(--c-border); border-radius:4px;">
                    <div style="font-family:'DM Mono',monospace; font-size:11px; color:var(--c-faint); letter-spacing:0.1em; margin-bottom:8px;">AUCUN PRODUIT TROUVÉ</div>
                    <div style="font-size:12px; color:var(--c-muted); margin-bottom:16px;">Modifiez vos filtres ou créez un nouveau produit.</div>
                    <button onclick="document.getElementById('modal-produit').classList.remove('hidden')"
                            class="btn-primary">
                        + Nouveau produit
                    </button>
                </div>
            </template>
        </div>
    </div>

    {{-- Modal --}}
    <div id="modal-produit" class="hidden"
         style="position:fixed; inset:0; z-index:200; display:none; align-items:center; justify-content:center; background:rgba(0,0,0,0.35); backdrop-filter:blur(3px); padding:20px;"
         x-data="produitModal()" x-on:keydown.escape.window="closeModal()"
         onclick="if(event.target===this) { document.getElementById('modal-produit').style.display='none'; document.getElementById('modal-produit').classList.add('hidden'); }">
        <div class="crm-modal" onclick="event.stopPropagation()">

            <div class="crm-modal-header">
                <div>
                    <div class="crm-modal-title" x-text="form.id ? 'Modifier le produit' : 'Nouveau produit'"></div>
                    <div style="font-family:'DM Mono',monospace; font-size:10px; color:var(--c-muted); margin-top:2px;"
                         x-text="form.id ? 'REF-' + String(form.id).padStart(3,'0') : 'Référence auto'"></div>
                </div>
                <button @click="closeModal()" class="crm-modal-close">&times;</button>
            </div>

            <form @submit.prevent="submitForm()" class="crm-modal-body" style="display:flex; flex-direction:column; gap:16px;">

                <div>
                    <label class="form-label">Nom du produit *</label>
                    <input type="text" x-model="form.nom" required placeholder="Ex : Oméga-3 Premium" class="crm-input">
                </div>

                <div>
                    <label class="form-label">Description</label>
                    <textarea x-model="form.description" rows="2" placeholder="Composition, bienfaits, dosage…" class="crm-input"></textarea>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div>
                        <label class="form-label">Catégorie</label>
                        <input type="text" x-model="form.categorie" list="cats-list" placeholder="Ex : Immunité" class="crm-input">
                        <datalist id="cats-list">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="form-label">Tarif unitaire (€) *</label>
                        <input type="number" step="0.01" min="0" x-model="form.tarif_unitaire" required placeholder="0.00" class="crm-input">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div>
                        <label class="form-label">Stock actuel *</label>
                        <input type="number" min="0" x-model="form.stock" required placeholder="0" class="crm-input">
                    </div>
                    <div>
                        <label class="form-label">Seuil d'alerte</label>
                        <input type="number" min="0" x-model="form.stock_alerte" placeholder="20" class="crm-input">
                    </div>
                </div>

                {{-- Toggle actif --}}
                <label style="display:flex; align-items:center; gap:12px; padding:12px 14px; background:var(--c-raised); border:1px solid var(--c-border); border-radius:3px; cursor:pointer;"
                       onmouseover="this.style.borderColor='var(--c-bolder)'" onmouseout="this.style.borderColor='var(--c-border)'">
                    <div style="position:relative; width:38px; height:22px; border-radius:11px; transition:background 0.2s; flex-shrink:0;"
                         :style="form.is_actif ? 'background:#16A34A' : 'background:var(--c-bolder)'">
                        <input type="checkbox" id="modal_is_actif" x-model="form.is_actif" style="display:none;">
                        <div style="position:absolute; top:3px; width:16px; height:16px; border-radius:50%; background:#fff; transition:left 0.2s;"
                             :style="form.is_actif ? 'left:19px' : 'left:3px'"></div>
                    </div>
                    <div>
                        <div style="font-size:13px; font-weight:500; color:var(--c-text);">Produit actif</div>
                        <div style="font-size:11px; color:var(--c-muted);">Disponible à la commande</div>
                    </div>
                </label>

                <div style="display:flex; gap:10px; padding-top:4px;">
                    <button type="submit" class="btn-primary" style="flex:1; justify-content:center; padding:10px 16px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                        <span x-text="form.id ? 'Enregistrer' : 'Créer le produit'"></span>
                    </button>
                    <button type="button" @click="closeModal()" class="btn-secondary" style="padding:10px 16px;">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const CAT_STYLES = {
            'Immunité':    { bar: 'bg-emerald-600',  pill: 'badge badge-green', dotColor: '#22C55E' },
            'Articulaire': { bar: 'bg-orange-600',   pill: '',                  dotColor: '#D97706' },
            'Énergie':     { bar: 'bg-amber-500',    pill: '',                  dotColor: '#D97706' },
            'Sommeil':     { bar: 'bg-indigo-600',   pill: '',                  dotColor: '#60A5FA' },
            'Digestion':   { bar: 'bg-teal-600',     pill: 'badge badge-green', dotColor: '#22C55E' },
            'Beauté':      { bar: 'bg-pink-600',     pill: '',                  dotColor: '#D97706' },
        };

        const catBarColors = {
            'Immunité':    '#16A34A',
            'Articulaire': '#D97706',
            'Énergie':     '#B45309',
            'Sommeil':     '#4F46E5',
            'Digestion':   '#0D9488',
            'Beauté':      '#BE185D',
        };

        function produitPage() {
            return {
                produits: @json($produits),
                search: '',
                filterCategorie: '',
                filterStatut: '',
                sortBy: 'nom',

                catBar(cat) {
                    const color = catBarColors[cat] ?? '#283D2B';
                    return `background:${color}`;
                },
                catPill(cat) {
                    const color = catBarColors[cat] ?? '#283D2B';
                    return `background:${color}20; color:${color}; border:1px solid ${color}40`;
                },

                filteredProduits() {
                    return this.produits
                        .filter(p => {
                            const s = this.search.toLowerCase();
                            const matchSearch = !s || p.nom.toLowerCase().includes(s) || (p.categorie || '').toLowerCase().includes(s);
                            const matchCat    = !this.filterCategorie || p.categorie === this.filterCategorie;
                            const matchStatut = !this.filterStatut
                                || (this.filterStatut === 'actif'   && p.is_actif && p.stock > 0)
                                || (this.filterStatut === 'inactif' && !p.is_actif)
                                || (this.filterStatut === 'rupture' && p.stock === 0)
                                || (this.filterStatut === 'faible'  && p.stock > 0 && p.stock <= p.stock_alerte);
                            return matchSearch && matchCat && matchStatut;
                        })
                        .sort((a, b) => {
                            if (this.sortBy === 'stock_asc')  return a.stock - b.stock;
                            if (this.sortBy === 'stock_desc') return b.stock - a.stock;
                            if (this.sortBy === 'tarif_asc')  return a.tarif_unitaire - b.tarif_unitaire;
                            if (this.sortBy === 'tarif_desc') return b.tarif_unitaire - a.tarif_unitaire;
                            return a.nom.localeCompare(b.nom, 'fr');
                        });
                },

                openEdit(p) {
                    window.dispatchEvent(new CustomEvent('open-edit', { detail: { ...p } }));
                    const modal = document.getElementById('modal-produit');
                    modal.classList.remove('hidden');
                    modal.style.display = 'flex';
                },
            }
        }

        function produitModal() {
            return {
                form: { id: null, nom: '', description: '', categorie: '', tarif_unitaire: '', stock: 0, stock_alerte: 20, is_actif: true },

                init() {
                    window.addEventListener('open-edit', e => { this.form = { ...e.detail }; });
                },

                closeModal() {
                    this.form = { id: null, nom: '', description: '', categorie: '', tarif_unitaire: '', stock: 0, stock_alerte: 20, is_actif: true };
                    const modal = document.getElementById('modal-produit');
                    modal.classList.add('hidden');
                    modal.style.display = 'none';
                },

                submitForm() {
                    const method = this.form.id ? 'PUT' : 'POST';
                    const url    = this.form.id ? `/produits/${this.form.id}` : `/produits`;
                    fetch(url, {
                        method,
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: JSON.stringify({ ...this.form }),
                    }).then(() => window.location.reload());
                },
            }
        }

        // Fix modal open button
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.querySelector('[onclick*="modal-produit"]');
            if (btn) {
                btn.addEventListener('click', function(e) {
                    const modal = document.getElementById('modal-produit');
                    modal.classList.remove('hidden');
                    modal.style.display = 'flex';
                    e.stopImmediatePropagation();
                }, true);
            }
        });
    </script>

</x-app-layout>
