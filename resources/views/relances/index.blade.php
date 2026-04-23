<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="crm-page-title">Relances commerciales</div>
            <div class="crm-page-sub">Pharmacies partenaires sans commande depuis plus de 30 jours</div>
        </div>
        @if($relances->isNotEmpty())
        <span class="badge badge-red" style="font-size:13px; padding:5px 14px;">
            {{ $relances->count() }} à relancer
        </span>
        @endif
    </x-slot>

    @if($relances->isEmpty())
        <div class="crm-panel" style="text-align:center; padding:60px 24px;">
            <div style="width:56px; height:56px; background:var(--c-green-l); border-radius:14px; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--c-green)" stroke-width="1.8" stroke-linecap="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <div style="font-family:'Fraunces',serif; font-size:18px; font-weight:500; color:var(--c-text); margin-bottom:6px;">Toutes les pharmacies sont actives</div>
            <div style="font-size:13px; color:var(--c-muted);">Aucune pharmacie n'est inactive depuis plus de 30 jours.</div>
        </div>
    @else
        <!-- Info banner -->
        <div style="background:#FEF2F2; border:1px solid #FECACA; border-radius:10px; padding:14px 18px; margin-bottom:20px; display:flex; align-items:center; gap:12px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" style="flex-shrink:0;">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span style="font-size:13px; color:#7F1D1D;">
                <strong>{{ $relances->count() }} pharmacie{{ $relances->count() > 1 ? 's' : '' }}</strong>
                n'{{ $relances->count() > 1 ? 'ont' : 'a' }} pas passé de commande depuis plus de 30 jours. Cliquez sur <strong>Relancer</strong> pour ouvrir un email pré-rempli.
            </span>
        </div>

        <div class="crm-table-wrap">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th>Pharmacie</th>
                        <th>Ville</th>
                        <th>Email</th>
                        <th>Dernière commande</th>
                        <th>Inactivité</th>
                        <th class="td-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($relances as $p)
                    @php
                        $derniere = $p->commandes->first();
                        $jours = $derniere ? (int) $derniere->created_at->diffInDays(now()) : null;
                    @endphp
                    <tr>
                        <td>
                            <div style="font-weight:600; color:var(--c-text);">{{ $p->nom }}</div>
                            @if($p->siret)
                            <div class="td-mono" style="font-size:10px; color:var(--c-faint);">SIRET {{ $p->siret }}</div>
                            @endif
                        </td>
                        <td style="color:var(--c-muted);">{{ $p->ville ?? ($p->code_postal ?? '—') }}</td>
                        <td>
                            @if($p->email)
                            <a href="mailto:{{ $p->email }}" style="color:var(--c-green-d); font-size:13px; text-decoration:none;"
                               onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                {{ $p->email }}
                            </a>
                            @else
                            <span style="color:var(--c-faint);">—</span>
                            @endif
                        </td>
                        <td>
                            @if($derniere)
                            <span style="font-size:12px; color:var(--c-muted);">{{ $derniere->created_at->format('d/m/Y') }}</span>
                            @else
                            <span class="badge badge-gray">Jamais commandé</span>
                            @endif
                        </td>
                        <td>
                            @if($jours !== null)
                            <span class="badge {{ $jours > 60 ? 'badge-red' : 'badge-amber' }}">
                                {{ $jours }} jour{{ $jours > 1 ? 's' : '' }}
                            </span>
                            @else
                            <span class="badge badge-red">—</span>
                            @endif
                        </td>
                        <td class="td-right">
                            @if($p->email)
                            <a href="mailto:{{ $p->email }}?subject=Votre activité NaturaCorp — Passez votre commande&body=Bonjour,%0A%0ANous espérons que tout va bien à la pharmacie {{ urlencode($p->nom) }}.%0A%0ANous avons remarqué que vous n'avez pas passé de commande récemment. Notre gamme de compléments alimentaires est disponible avec livraison J+1.%0A%0AN'hésitez pas à nous contacter ou à commander directement depuis votre espace partenaire.%0A%0ACordialement,%0AL'équipe NaturaCorp"
                               class="btn-primary" style="font-size:12px; padding:6px 14px; text-decoration:none; gap:6px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                Relancer
                            </a>
                            @else
                            <span style="font-size:12px; color:var(--c-faint);">Pas d'email</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</x-app-layout>
