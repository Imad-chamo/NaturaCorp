<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="crm-page-title">Tableau de bord</div>
            <div class="crm-page-sub">{{ \Carbon\Carbon::now()->translatedFormat('l d MMMM Y') }}</div>
        </div>
        <div style="display:flex; gap:8px;">
            <a href="{{ route('commandes.index') }}" class="btn-secondary" style="padding:7px 14px; font-size:12px;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 100 4 2 2 0 000-4zm8 0a2 2 0 100 4 2 2 0 000-4zm.75-3H7.5"/></svg>
                Commandes
            </a>
            <a href="{{ route('pharmacies.index') }}" class="btn-primary" style="padding:7px 14px; font-size:12px;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 13a3 3 0 100-6 3 3 0 000 6z"/><path d="M17.8 13.938h-.011a7 7 0 10-11.464.144h-.016l.14.171.3.371L12 21l5.13-6.248.54-.659.13-.155z"/></svg>
                Pharmacies
            </a>
        </div>
    </x-slot>

    {{-- Banner demandes en attente --}}
    @if($demandes->isNotEmpty())
    <div style="background:linear-gradient(135deg, #FFFBEB 0%, #FEF9EE 100%); border:1px solid #FDE68A; border-radius:12px; padding:18px 22px; margin-bottom:24px; display:flex; align-items:center; gap:18px; position:relative; overflow:hidden;">
        <div style="position:absolute; right:-10px; top:-10px; width:80px; height:80px; background:#FDE68A; border-radius:50%; opacity:0.3;"></div>
        <div style="width:44px; height:44px; background:#FEF3C7; border:1px solid #FDE68A; border-radius:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 2px 8px rgba(217,119,6,0.15);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2" stroke-linecap="round">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
            </svg>
        </div>
        <div style="flex:1; min-width:0;">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px;">
                <span style="font-size:14px; font-weight:600; color:#92400E;">
                    {{ $demandes->count() }} nouvelle{{ $demandes->count() > 1 ? 's' : '' }} demande{{ $demandes->count() > 1 ? 's' : '' }} partenaire
                </span>
                <span style="background:#D97706; color:#fff; font-size:9px; font-weight:700; padding:2px 8px; border-radius:20px; letter-spacing:0.06em;">NOUVEAU</span>
            </div>
            <div style="display:flex; flex-wrap:wrap; gap:6px;">
                @foreach($demandes as $d)
                <span style="background:#FEF3C7; border:1px solid #FDE68A; color:#78350F; font-size:11px; font-weight:500; padding:2px 10px; border-radius:20px;">
                    {{ $d->nom }}
                    @if($d->code_postal)<span style="color:#D97706; font-family:'DM Mono',monospace; font-size:10px;"> · {{ $d->code_postal }}</span>@endif
                </span>
                @endforeach
            </div>
        </div>
        <a href="{{ route('demandes.index') }}"
           style="display:inline-flex; align-items:center; gap:7px; background:#D97706; color:#fff; font-size:12px; font-weight:600; padding:9px 18px; border-radius:8px; text-decoration:none; white-space:nowrap; box-shadow:0 2px 8px rgba(217,119,6,0.3); transition:all 0.15s; flex-shrink:0;"
           onmouseover="this.style.background='#B45309'; this.style.transform='translateY(-1px)'"
           onmouseout="this.style.background='#D97706'; this.style.transform='translateY(0)'">
            Traiter maintenant
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
    @endif

    <x-dashboard-global-stats :stats="$stats" :commandes_recentes="$commandes_recentes" />
</x-app-layout>
