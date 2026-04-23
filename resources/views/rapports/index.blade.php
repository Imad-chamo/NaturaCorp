<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="crm-page-title">Rapports</div>
            <div class="crm-page-sub">Générez et téléchargez vos exports de données</div>
        </div>
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-family:'DM Mono',monospace; font-size:11px; color:var(--c-faint);">
                {{ $rapports->count() }} rapport{{ $rapports->count() > 1 ? 's' : '' }} généré{{ $rapports->count() > 1 ? 's' : '' }}
            </span>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="flash-success">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── KPIs live ── --}}
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:28px;">

        <div style="background:var(--c-surface); border:1px solid var(--c-border); border-radius:12px; padding:18px 20px; box-shadow:var(--shadow-sm); position:relative; overflow:hidden;">
            <div style="position:absolute; top:0; left:0; bottom:0; width:3px; background:var(--c-green); border-radius:0 2px 2px 0;"></div>
            <div style="position:absolute; top:14px; right:14px; width:34px; height:34px; background:var(--c-green-l); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--c-green)" stroke-width="2" stroke-linecap="round"><path d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 100 4 2 2 0 000-4zm8 0a2 2 0 100 4 2 2 0 000-4zm.75-3H7.5"/></svg>
            </div>
            <div style="font-size:10px; font-weight:700; color:var(--c-faint); letter-spacing:0.08em; text-transform:uppercase; margin-bottom:8px;">Commandes totales</div>
            <div style="font-family:'Fraunces',serif; font-size:28px; font-weight:400; color:var(--c-green-d); line-height:1;">{{ number_format($stats['commandes_total']) }}</div>
            <div style="font-size:11px; color:var(--c-muted); margin-top:5px;">{{ $stats['commandes_mois'] }} ce mois</div>
        </div>

        <div style="background:var(--c-surface); border:1px solid var(--c-border); border-radius:12px; padding:18px 20px; box-shadow:var(--shadow-sm); position:relative; overflow:hidden;">
            <div style="position:absolute; top:0; left:0; bottom:0; width:3px; background:var(--c-blue); border-radius:0 2px 2px 0;"></div>
            <div style="position:absolute; top:14px; right:14px; width:34px; height:34px; background:var(--c-blue-l); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--c-blue)" stroke-width="2" stroke-linecap="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
            </div>
            <div style="font-size:10px; font-weight:700; color:var(--c-faint); letter-spacing:0.08em; text-transform:uppercase; margin-bottom:8px;">Chiffre d'affaires</div>
            <div style="font-family:'Fraunces',serif; font-size:28px; font-weight:400; color:var(--c-blue); line-height:1;">{{ number_format($stats['ca_total'], 0, ',', ' ') }} €</div>
            <div style="font-size:11px; color:var(--c-muted); margin-top:5px;">{{ number_format($stats['ca_mois'], 0, ',', ' ') }} € ce mois</div>
        </div>

        <div style="background:var(--c-surface); border:1px solid var(--c-border); border-radius:12px; padding:18px 20px; box-shadow:var(--shadow-sm); position:relative; overflow:hidden;">
            <div style="position:absolute; top:0; left:0; bottom:0; width:3px; background:var(--c-muted); border-radius:0 2px 2px 0;"></div>
            <div style="position:absolute; top:14px; right:14px; width:34px; height:34px; background:var(--c-hover); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--c-muted)" stroke-width="2" stroke-linecap="round"><path d="M12 13a3 3 0 100-6 3 3 0 000 6z"/><path d="M17.8 13.938h-.011a7 7 0 10-11.464.144h-.016l.14.171.3.371L12 21l5.13-6.248.54-.659.13-.155z"/></svg>
            </div>
            <div style="font-size:10px; font-weight:700; color:var(--c-faint); letter-spacing:0.08em; text-transform:uppercase; margin-bottom:8px;">Pharmacies actives</div>
            <div style="font-family:'Fraunces',serif; font-size:28px; font-weight:400; color:var(--c-text); line-height:1;">{{ number_format($stats['pharmacies_total']) }}</div>
            <div style="font-size:11px; color:var(--c-muted); margin-top:5px;">clients actifs</div>
        </div>

        <div style="background:var(--c-surface); border:1px solid var(--c-border); border-radius:12px; padding:18px 20px; box-shadow:var(--shadow-sm); position:relative; overflow:hidden;">
            <div style="position:absolute; top:0; left:0; bottom:0; width:3px; background:{{ $stats['relances_total'] > 0 ? 'var(--c-amber)' : 'var(--c-green)' }}; border-radius:0 2px 2px 0;"></div>
            <div style="position:absolute; top:14px; right:14px; width:34px; height:34px; background:{{ $stats['relances_total'] > 0 ? 'var(--c-amber-l)' : 'var(--c-green-l)' }}; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="{{ $stats['relances_total'] > 0 ? '#D97706' : 'var(--c-green)' }}" stroke-width="2" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.95 12a19.79 19.79 0 01-3.07-8.67A2 2 0 012.86 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.83a16 16 0 006.07 6.07l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
            </div>
            <div style="font-size:10px; font-weight:700; color:var(--c-faint); letter-spacing:0.08em; text-transform:uppercase; margin-bottom:8px;">À relancer</div>
            <div style="font-family:'Fraunces',serif; font-size:28px; font-weight:400; color:{{ $stats['relances_total'] > 0 ? 'var(--c-amber)' : 'var(--c-green-d)' }}; line-height:1;">{{ $stats['relances_total'] }}</div>
            <div style="font-size:11px; color:var(--c-muted); margin-top:5px;">inactives depuis 30j+</div>
        </div>

    </div>

    {{-- ── Génération de rapports ── --}}
    <div style="margin-bottom:28px;">
        <div class="section-label" style="margin-bottom:16px;">Générer un nouveau rapport</div>

        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px;">

            @php
            $types = [
                [
                    'type'    => 'commandes',
                    'label'   => 'Export Commandes',
                    'desc'    => 'Toutes les commandes avec pharmacie, produit, quantité, tarif, statut et date.',
                    'color'   => '#16A34A',
                    'bg'      => '#DCFCE7',
                    'icon'    => 'M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 100 4 2 2 0 000-4zm8 0a2 2 0 100 4 2 2 0 000-4zm.75-3H7.5',
                    'fields'  => 'Réf. · Pharmacie · Produit · Qté · Tarif · Total · Statut · Date',
                ],
                [
                    'type'    => 'pharmacies',
                    'label'   => 'Export Pharmacies',
                    'desc'    => 'Annuaire complet des pharmacies avec contacts, statut, commercial et historique.',
                    'color'   => '#2563EB',
                    'bg'      => '#DBEAFE',
                    'icon'    => 'M12 13a3 3 0 100-6 3 3 0 000 6zM17.8 13.938h-.011a7 7 0 10-11.464.144h-.016l.14.171.3.371L12 21l5.13-6.248.54-.659.13-.155z',
                    'fields'  => 'Nom · SIRET · Email · Téléphone · Statut · Commercial · Nb cmd',
                ],
                [
                    'type'    => 'relances',
                    'label'   => 'Rapport Relances',
                    'desc'    => 'Pharmacies actives sans commande depuis plus de 30 jours à recontacter.',
                    'color'   => '#D97706',
                    'bg'      => '#FEF3C7',
                    'icon'    => 'M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.95 12a19.79 19.79 0 01-3.07-8.67A2 2 0 012.86 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.83a16 16 0 006.07 6.07l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z',
                    'fields'  => 'Pharmacie · Email · Ville · Dernière cmd · Jours inactif',
                ],
                [
                    'type'    => 'ca_mensuel',
                    'label'   => 'CA Mensuel',
                    'desc'    => 'Évolution du chiffre d\'affaires mois par mois avec moyennes par commande.',
                    'color'   => '#7C3AED',
                    'bg'      => '#EDE9FE',
                    'icon'    => 'M3 3v18h18M9 17v-6M15 17V9M21 17V3',
                    'fields'  => 'Mois · Nb commandes · CA total · CA moyen',
                ],
            ];
            @endphp

            @foreach($types as $t)
            <div style="background:var(--c-surface); border:1px solid var(--c-border); border-radius:12px; overflow:hidden; box-shadow:var(--shadow-sm); display:flex; flex-direction:column; transition:box-shadow 0.2s, transform 0.2s;"
                 onmouseover="this.style.boxShadow='var(--shadow-md)'; this.style.transform='translateY(-2px)'"
                 onmouseout="this.style.boxShadow='var(--shadow-sm)'; this.style.transform='translateY(0)'">

                {{-- Card header --}}
                <div style="padding:20px 20px 16px; flex:1;">
                    <div style="width:44px; height:44px; background:{{ $t['bg'] }}; border-radius:11px; display:flex; align-items:center; justify-content:center; margin-bottom:14px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="{{ $t['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="{{ $t['icon'] }}"/>
                        </svg>
                    </div>
                    <div style="font-size:14px; font-weight:600; color:var(--c-text); margin-bottom:7px;">{{ $t['label'] }}</div>
                    <div style="font-size:12px; color:var(--c-muted); line-height:1.6; margin-bottom:12px;">{{ $t['desc'] }}</div>
                    <div style="font-size:10px; color:var(--c-faint); font-family:'DM Mono',monospace; line-height:1.8;">{{ $t['fields'] }}</div>
                </div>

                {{-- Format badge + button --}}
                <div style="padding:14px 20px; border-top:1px solid var(--c-border); display:flex; align-items:center; justify-content:space-between; background:var(--c-raised);">
                    <span style="font-size:10px; font-weight:700; letter-spacing:0.08em; padding:3px 8px; border-radius:5px; background:{{ $t['bg'] }}; color:{{ $t['color'] }};">CSV</span>
                    <form method="POST" action="{{ route('rapports.generate') }}">
                        @csrf
                        <input type="hidden" name="type" value="{{ $t['type'] }}">
                        <button type="submit"
                                style="display:inline-flex; align-items:center; gap:6px; padding:7px 14px; background:{{ $t['color'] }}; color:#fff; border:none; border-radius:7px; font-size:12px; font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif; transition:opacity 0.15s, transform 0.15s; box-shadow:0 2px 8px {{ $t['color'] }}33;"
                                onmouseover="this.style.opacity='0.88'; this.style.transform='translateY(-1px)'"
                                onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)'">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Générer
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── Historique ── --}}
    <div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;">
            <div class="section-label" style="margin-bottom:0;">Historique des rapports</div>
            @if($rapports->isNotEmpty())
            <span style="font-family:'DM Mono',monospace; font-size:11px; color:var(--c-faint);">{{ $rapports->count() }} fichier(s)</span>
            @endif
        </div>

        @if($rapports->isEmpty())
            <div style="background:var(--c-surface); border:1px solid var(--c-border); border-radius:12px; padding:48px 24px; text-align:center; box-shadow:var(--shadow-sm);">
                <div style="width:52px; height:52px; background:var(--c-raised); border:1px solid var(--c-border); border-radius:13px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:14px;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--c-faint)" stroke-width="1.5" stroke-linecap="round">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                </div>
                <div style="font-family:'Fraunces',Georgia,serif; font-size:16px; color:var(--c-text); margin-bottom:5px;">Aucun rapport généré</div>
                <div style="font-size:12px; color:var(--c-muted);">Utilisez les cartes ci-dessus pour générer votre premier rapport CSV.</div>
            </div>
        @else
            <div class="crm-table-wrap">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>Rapport</th>
                            <th>Type</th>
                            <th>Généré par</th>
                            <th>Date</th>
                            <th>Fichier</th>
                            <th class="td-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rapports as $rapport)
                        @php
                            $typeConfig = [
                                'commandes'  => ['badge-green', '#16A34A', '#DCFCE7', 'Commandes'],
                                'pharmacies' => ['badge-blue',  '#2563EB', '#DBEAFE', 'Pharmacies'],
                                'relances'   => ['badge-amber', '#D97706', '#FEF3C7', 'Relances'],
                                'ca_mensuel' => ['badge-gray',  '#7C3AED', '#EDE9FE', 'CA Mensuel'],
                            ][$rapport->type] ?? ['badge-gray', '#6B7280', '#F3F4F6', ucfirst($rapport->type)];
                        @endphp
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div style="width:32px; height:32px; border-radius:8px; background:{{ $typeConfig[2] }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $typeConfig[1] }}" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    </div>
                                    <span style="font-weight:500; color:var(--c-text);">{{ $rapport->titre }}</span>
                                </div>
                            </td>
                            <td>
                                <span style="font-size:11px; font-weight:700; padding:2px 9px; border-radius:20px; letter-spacing:0.04em; background:{{ $typeConfig[2] }}; color:{{ $typeConfig[1] }};">
                                    {{ $typeConfig[3] }}
                                </span>
                            </td>
                            <td>
                                @if($rapport->utilisateur)
                                <div style="display:flex; align-items:center; gap:7px;">
                                    <div style="width:22px; height:22px; background:var(--c-green-l); border-radius:5px; display:flex; align-items:center; justify-content:center; font-size:9px; font-weight:700; color:var(--c-green-d); flex-shrink:0;">
                                        {{ strtoupper(substr($rapport->utilisateur->name, 0, 1)) }}
                                    </div>
                                    <span style="font-size:13px; color:var(--c-muted);">{{ $rapport->utilisateur->name }}</span>
                                </div>
                                @else
                                    <span style="color:var(--c-faint);">—</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-size:12px; color:var(--c-text);">{{ $rapport->created_at->format('d/m/Y') }}</div>
                                <div style="font-family:'DM Mono',monospace; font-size:10px; color:var(--c-faint);">{{ $rapport->created_at->format('H:i') }}</div>
                            </td>
                            <td>
                                <div style="font-family:'DM Mono',monospace; font-size:11px; color:var(--c-faint);">
                                    {{ basename($rapport->chemin_fichier) }}
                                </div>
                            </td>
                            <td class="td-right">
                                <div style="display:flex; align-items:center; justify-content:flex-end; gap:8px;">
                                    <a href="{{ route('rapports.show', $rapport) }}"
                                       class="link-edit" style="gap:5px;">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                        Télécharger
                                    </a>
                                    <form method="POST" action="{{ route('rapports.destroy', $rapport) }}"
                                          @submit.prevent="if(confirm('Supprimer ce rapport ?')) $el.submit()">
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
    </div>

</x-app-layout>
