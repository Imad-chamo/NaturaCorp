<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="crm-page-title">Rapports</div>
            <div class="crm-page-sub">Fichiers générés et historique des exports</div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="flash-success">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($rapports->isEmpty())
        <div class="crm-panel" style="text-align:center; padding:60px 24px;">
            <div style="width:56px; height:56px; background:var(--c-raised); border-radius:14px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:16px; border:1px solid var(--c-border);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--c-faint)" stroke-width="1.5" stroke-linecap="round">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
            </div>
            <div style="font-family:'Fraunces',Georgia,serif; font-size:17px; color:var(--c-text); margin-bottom:6px;">Aucun rapport disponible</div>
            <div style="font-size:13px; color:var(--c-muted);">Les rapports générés apparaîtront ici.</div>
        </div>
    @else
        <div class="crm-table-wrap">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Généré par</th>
                        <th>Date</th>
                        <th class="td-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rapports as $rapport)
                        <tr>
                            <td>
                                <span style="font-weight:500; color:var(--c-text);">{{ $rapport->titre }}</span>
                            </td>
                            <td>
                                <span class="badge badge-gray" style="text-transform:capitalize;">{{ str_replace('_', ' ', $rapport->type) }}</span>
                            </td>
                            <td style="font-size:13px; color:var(--c-muted);">{{ $rapport->utilisateur?->name ?? '—' }}</td>
                            <td class="td-mono" style="font-size:12px;">{{ $rapport->created_at->format('d/m/Y H:i') }}</td>
                            <td class="td-right">
                                <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
                                    <a href="{{ route('rapports.show', $rapport) }}"
                                       class="link-edit">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                        Télécharger
                                    </a>
                                    <form method="POST" action="{{ route('rapports.destroy', $rapport) }}"
                                          onsubmit="return confirm('Supprimer ce rapport ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="link-del" style="background:transparent; border:none; cursor:pointer;">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-app-layout>
