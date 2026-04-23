<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="crm-page-title">Recherche</div>
            <div class="crm-page-sub">
                @if($q) Résultats pour « <strong>{{ $q }}</strong> » @else Saisissez un terme pour rechercher @endif
            </div>
        </div>
    </x-slot>

    <!-- Barre de recherche -->
    <form method="GET" action="{{ route('search') }}" style="margin-bottom:24px;">
        <div style="position:relative; max-width:500px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--c-muted); pointer-events:none;">
                <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
            </svg>
            <input type="text" name="q" value="{{ $q }}" placeholder="Pharmacie, ville, email, n° commande…"
                   class="crm-search" style="width:100%; padding-left:42px; padding-right:100px; font-size:14px; height:44px;"
                   autofocus>
            <button type="submit" class="btn-primary"
                    style="position:absolute; right:6px; top:50%; transform:translateY(-50%); padding:6px 16px; font-size:12px;">
                Rechercher
            </button>
        </div>
    </form>

    @if($q && strlen($q) >= 2)

        @if($pharmacies->isEmpty() && $commandes->isEmpty())
            <div class="crm-panel" style="text-align:center; padding:48px 24px;">
                <div style="font-size:14px; color:var(--c-faint); font-family:'DM Mono',monospace;">Aucun résultat pour « {{ $q }} »</div>
            </div>
        @else

        @if($pharmacies->isNotEmpty())
        <div style="margin-bottom:24px;">
            <div class="section-label">Pharmacies ({{ $pharmacies->count() }})</div>
            <div class="crm-table-wrap">
                <table class="crm-table">
                    <thead>
                        <tr><th>Nom</th><th>Ville</th><th>Email</th><th>Statut</th><th class="td-right">Action</th></tr>
                    </thead>
                    <tbody>
                        @foreach($pharmacies as $p)
                        <tr>
                            <td style="font-weight:600;">{{ $p->nom }}</td>
                            <td style="color:var(--c-muted);">{{ $p->ville ?? $p->code_postal ?? '—' }}</td>
                            <td>
                                @if($p->email)
                                <a href="mailto:{{ $p->email }}" style="color:var(--c-green-d); font-size:13px; text-decoration:none;">{{ $p->email }}</a>
                                @else —
                                @endif
                            </td>
                            <td>
                                @php $badge = match($p->statut) { 'client_actif' => ['badge-green','Actif'], 'prospect' => ['badge-amber','Prospect'], default => ['badge-gray','Inactif'] }; @endphp
                                <span class="badge {{ $badge[0] }}">{{ $badge[1] }}</span>
                            </td>
                            <td class="td-right">
                                <a href="{{ route('pharmacies.show', $p) }}" class="link-edit">Voir</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if($commandes->isNotEmpty())
        <div>
            <div class="section-label">Commandes ({{ $commandes->count() }})</div>
            <div class="crm-table-wrap">
                <table class="crm-table">
                    <thead>
                        <tr><th>Réf.</th><th>Pharmacie</th><th>Produit</th><th>Statut</th><th>Date</th><th class="td-right">Total</th></tr>
                    </thead>
                    <tbody>
                        @foreach($commandes as $c)
                        <tr>
                            <td class="td-mono" style="font-size:11px; color:var(--c-muted);">
                                NC-{{ $c->created_at->format('Y') }}-{{ str_pad($c->id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td style="font-weight:500;">{{ $c->pharmacie?->nom ?? '—' }}</td>
                            <td style="color:var(--c-muted); font-size:12px;">{{ $c->produit?->nom ?? '—' }}</td>
                            <td>
                                @php $s = $c->statut?->value ?? $c->statut; $badge = match($s) { 'livree' => ['badge-green','Livrée'], 'validee' => ['badge-blue','Validée'], 'en_cours' => ['badge-amber','En cours'], default => ['badge-gray', ucfirst($s)] }; @endphp
                                <span class="badge {{ $badge[0] }}">{{ $badge[1] }}</span>
                            </td>
                            <td class="td-mono" style="font-size:12px; color:var(--c-muted);">{{ $c->created_at->format('d/m/Y') }}</td>
                            <td class="td-right td-mono" style="color:var(--c-green);">
                                {{ number_format($c->tarif_unitaire * $c->quantite, 2, ',', ' ') }} €
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @endif
    @endif

</x-app-layout>
