<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="crm-page-title">Demandes partenaires</div>
            <div class="crm-page-sub">Pharmacies ayant soumis une demande depuis la vitrine</div>
        </div>
        <div style="display:flex; align-items:center; gap:10px;">
            @if($total > 0)
            <span class="badge badge-amber" style="font-size:13px; padding:5px 14px;">{{ $total }} en attente</span>
            @endif
            @if($total > 0)
            <a href="{{ route('demandes.export') }}"
               style="display:inline-flex; align-items:center; gap:6px; background:white; border:1px solid var(--c-border); color:var(--c-muted); font-size:12px; font-weight:500; padding:7px 14px; border-radius:7px; text-decoration:none; box-shadow:var(--shadow-xs); transition:all 0.15s;"
               onmouseover="this.style.borderColor='var(--c-bolder)'; this.style.color='var(--c-text)'"
               onmouseout="this.style.borderColor='var(--c-border)'; this.style.color='var(--c-muted)'">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Exporter CSV
            </a>
            @endif
        </div>
    </x-slot>

    @if(session('success'))
        <div class="flash-success">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($demandes->isEmpty())
        <div class="crm-panel" style="text-align:center; padding:60px 24px;">
            <div style="width:56px; height:56px; background:var(--c-green-l); border-radius:14px; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--c-green)" stroke-width="1.8" stroke-linecap="round">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </div>
            <div style="font-family:'Fraunces',serif; font-size:18px; font-weight:500; color:var(--c-text); margin-bottom:6px;">Aucune demande en attente</div>
            <div style="font-size:13px; color:var(--c-muted);">Les nouvelles demandes soumises depuis la vitrine apparaîtront ici.</div>
        </div>
    @else
    <div x-data="{ search: '' }">

        <!-- Toolbar -->
        <div class="crm-toolbar" style="margin-bottom:16px;">
            <div style="position:relative;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--c-muted); pointer-events:none;">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
                <input type="text" x-model="search" placeholder="Rechercher pharmacie, email, CP…" class="crm-search" style="width:260px;">
            </div>
            <span style="margin-left:auto; font-family:'DM Mono',monospace; font-size:11px; color:var(--c-muted);"
                  x-text="document.querySelectorAll('tbody tr:not([style*=\'display: none\'])').length + ' résultat(s)'"></span>
        </div>

        <div class="crm-table-wrap">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th>Pharmacie</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Code postal</th>
                        <th>Reçue le</th>
                        <th class="td-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($demandes as $d)
                    <tr x-show="
                        !search ||
                        '{{ strtolower($d->nom) }}'.includes(search.toLowerCase()) ||
                        '{{ strtolower($d->email ?? '') }}'.includes(search.toLowerCase()) ||
                        '{{ $d->code_postal ?? '' }}'.includes(search)
                    " style="cursor:pointer;" onclick="window.location='{{ route('demandes.show', $d) }}'">
                        <td>
                            <div style="font-weight:600; color:var(--c-text);">{{ $d->nom }}</div>
                            @if($d->ville)
                            <div style="font-size:11px; color:var(--c-muted); margin-top:2px;">{{ $d->ville }}</div>
                            @endif
                        </td>
                        <td>
                            @if($d->email)
                                <a href="mailto:{{ $d->email }}" style="color:var(--c-green-d); text-decoration:none; font-size:13px;"
                                   onmouseover="this.style.textDecoration='underline'"
                                   onmouseout="this.style.textDecoration='none'">{{ $d->email }}</a>
                            @else
                                <span style="color:var(--c-faint);">—</span>
                            @endif
                        </td>
                        <td class="td-mono">{{ $d->telephone ?? '—' }}</td>
                        <td class="td-mono">{{ $d->code_postal ?? '—' }}</td>
                        <td>
                            <span style="font-size:12px; color:var(--c-muted);">{{ $d->created_at->format('d/m/Y') }}</span>
                            <div style="font-size:11px; color:var(--c-faint);">{{ $d->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="td-right">
                            <div style="display:inline-flex; align-items:center; gap:6px;">
                                <form method="POST" action="{{ route('demandes.accept', $d) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="link-edit"
                                            onclick="return confirm('Accepter {{ $d->nom }} comme pharmacie partenaire ?')"
                                            style="gap:5px; padding:4px 10px;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        Accepter
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('demandes.reject', $d) }}" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="link-del"
                                            onclick="return confirm('Supprimer la demande de {{ $d->nom }} ?')"
                                            style="gap:5px; padding:4px 10px;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                        Rejeter
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($demandes->hasPages())
            <div style="margin-top:16px;">{{ $demandes->links() }}</div>
        @endif

    </div>
    @endif

</x-app-layout>
