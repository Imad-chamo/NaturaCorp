<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="crm-page-title" style="display:flex; align-items:center; gap:10px;">
                <a href="{{ route('pharmacies.index') }}"
                   style="color:var(--c-muted); font-size:13px; font-weight:400; text-decoration:none; font-family:'DM Sans',sans-serif;"
                   onmouseover="this.style.color='var(--c-text)'" onmouseout="this.style.color='var(--c-muted)'">
                    ← Pharmacies
                </a>
                <span style="color:var(--c-border);">/</span>
                {{ $pharmacy->nom }}
            </div>
            <div class="crm-page-sub">{{ $pharmacy->ville }} — {{ $pharmacy->adresse }}</div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="flash-success">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <script>
        window.commandesFromLaravel = @json($pharmacy->commandes);
        window.statutsCommande = @json(\App\Enums\StatutCommande::cases());
        window.produitsFromLaravel = @json($produits);
    </script>

    <!-- KPI Cards -->
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:24px;">
        <div class="kpi-card kpi-blue">
            <div class="kpi-label">Total commandes</div>
            <div class="kpi-value">{{ $pharmacy->commandes->count() }}</div>
        </div>
        <div class="kpi-card kpi-green">
            <div class="kpi-label">Montant cumulé</div>
            <div class="kpi-value" style="font-size:22px;">
                {{ number_format($pharmacy->commandes->sum(fn($c) => $c->quantite * $c->tarif_unitaire), 2, ',', ' ') }} €
            </div>
        </div>
        <div class="kpi-card kpi-muted">
            <div class="kpi-label">Dernière commande</div>
            <div class="kpi-value" style="font-size:20px;">
                @php
                    $last = $pharmacy->commandes->sortByDesc('date_commande')->first();
                    $lastDate = $last ? optional($last->date_commande)->format('d/m/Y') ?? $last->date_commande : '—';
                @endphp
                {{ $lastDate }}
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div x-data="initCommandeTable(window.commandesFromLaravel, window.produitsFromLaravel)"
         style="display:grid; grid-template-columns:5fr 7fr; gap:16px;">

        <!-- Left: Formulaire + Documents -->
        <div style="display:flex; flex-direction:column; gap:16px;">
            <div class="crm-panel">
                <x-pharmacies.formulaire :pharmacy="$pharmacy" />
            </div>
            <div class="crm-panel">
                <x-pharmacies.documents :pharmacy="$pharmacy" />
            </div>
        </div>

        <!-- Right: Commandes -->
        <div class="crm-panel">
            <x-pharmacies.commandes :pharmacy="$pharmacy" :produits="$produits" />
        </div>
    </div>

    @push('scripts')
        <script>
            function initCommandeTable(commandes, produits) {
                return {
                    commandes,
                    produits,
                    modalOpen: false,
                    modalMode: 'create',
                    editingCommande: {},
                }
            }
        </script>
    @endpush
</x-app-layout>
