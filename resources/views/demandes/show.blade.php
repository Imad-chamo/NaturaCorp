<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('demandes.index') }}"
               style="display:inline-flex; align-items:center; gap:6px; font-size:12px; color:var(--c-muted); text-decoration:none; margin-bottom:6px;"
               onmouseover="this.style.color='var(--c-text)'" onmouseout="this.style.color='var(--c-muted)'">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Demandes partenaires
            </a>
            <div class="crm-page-title">{{ $pharmacie->nom }}</div>
            <div class="crm-page-sub">Demande reçue {{ $pharmacie->created_at->diffForHumans() }} — {{ $pharmacie->created_at->format('d/m/Y à H:i') }}</div>
        </div>
        <div style="display:flex; gap:10px;">
            @if($pharmacie->email)
            <a href="mailto:{{ $pharmacie->email }}?subject=Votre demande de partenariat NaturaCorp&body=Bonjour,%0A%0ANous avons bien reçu votre demande de partenariat pour {{ urlencode($pharmacie->nom) }}.%0A%0AUn commercial NaturaCorp vous contactera très prochainement.%0A%0ACordialement,%0AL'équipe NaturaCorp"
               class="btn-primary"
               style="gap:7px; text-decoration:none;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                Envoyer un email
            </a>
            @endif

            <form method="POST" action="{{ route('demandes.accept', $pharmacie) }}" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" class="btn-primary"
                        style="background:var(--c-green); gap:7px;"
                        onclick="return confirm('Accepter {{ $pharmacie->nom }} comme pharmacie partenaire ?')">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Accepter
                </button>
            </form>

            <form method="POST" action="{{ route('demandes.reject', $pharmacie) }}" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn-secondary"
                        style="color:var(--c-red); border-color:rgba(220,38,38,0.2); gap:7px;"
                        onclick="return confirm('Supprimer cette demande ?')">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M9 6V4h6v2"/></svg>
                    Rejeter
                </button>
            </form>
        </div>
    </x-slot>

    <div style="display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start;">

        <!-- Fiche principale -->
        <div style="display:flex; flex-direction:column; gap:16px;">

            <!-- Coordonnées -->
            <div class="crm-panel">
                <div class="section-label">Coordonnées</div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

                    <div>
                        <div style="font-size:11px; font-weight:700; color:var(--c-faint); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px;">Pharmacie</div>
                        <div style="font-size:15px; font-weight:600; color:var(--c-text);">{{ $pharmacie->nom }}</div>
                    </div>

                    <div>
                        <div style="font-size:11px; font-weight:700; color:var(--c-faint); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px;">Code postal</div>
                        <div style="font-family:'DM Mono',monospace; font-size:14px; color:var(--c-text);">{{ $pharmacie->code_postal ?? '—' }}</div>
                    </div>

                    <div>
                        <div style="font-size:11px; font-weight:700; color:var(--c-faint); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px;">Email</div>
                        @if($pharmacie->email)
                        <a href="mailto:{{ $pharmacie->email }}" style="color:var(--c-green-d); font-size:14px; text-decoration:none;"
                           onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                            {{ $pharmacie->email }}
                        </a>
                        @else
                        <span style="color:var(--c-faint);">—</span>
                        @endif
                    </div>

                    <div>
                        <div style="font-size:11px; font-weight:700; color:var(--c-faint); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px;">Téléphone</div>
                        @if($pharmacie->telephone)
                        <a href="tel:{{ $pharmacie->telephone }}" style="color:var(--c-text); font-family:'DM Mono',monospace; font-size:14px; text-decoration:none;">
                            {{ $pharmacie->telephone }}
                        </a>
                        @else
                        <span style="color:var(--c-faint);">—</span>
                        @endif
                    </div>

                    @if($pharmacie->ville)
                    <div>
                        <div style="font-size:11px; font-weight:700; color:var(--c-faint); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px;">Ville</div>
                        <div style="font-size:14px; color:var(--c-text);">{{ $pharmacie->ville }}</div>
                    </div>
                    @endif

                </div>
            </div>

            <!-- Template email -->
            <div class="crm-panel">
                <div class="section-label">Template de réponse</div>
                <div style="background:var(--c-base); border:1px solid var(--c-border); border-radius:8px; padding:16px 18px; font-size:13px; color:var(--c-text); line-height:1.8; font-family:'DM Mono',monospace; white-space:pre-wrap;">Bonjour,

Nous avons bien reçu votre demande de partenariat pour {{ $pharmacie->nom }}.

Notre équipe commerciale a pris note de votre intérêt et vous contactera dans les 48h ouvrées pour vous présenter notre offre partenaire : tarifs préférentiels, livraison J+1, accompagnement dédié.

En attendant, n'hésitez pas à consulter notre catalogue sur notre site.

Cordialement,
L'équipe NaturaCorp</div>
                @if($pharmacie->email)
                <a href="mailto:{{ $pharmacie->email }}?subject=Votre demande de partenariat NaturaCorp&body=Bonjour,%0A%0ANous avons bien reçu votre demande de partenariat pour {{ urlencode($pharmacie->nom) }}.%0A%0ANotre équipe commerciale a pris note de votre intérêt et vous contactera dans les 48h ouvrées pour vous présenter notre offre partenaire.%0A%0ACordialement,%0AL'équipe NaturaCorp"
                   style="display:inline-flex; align-items:center; gap:7px; margin-top:14px; background:var(--c-green); color:#fff; font-size:12px; font-weight:600; padding:8px 18px; border-radius:7px; text-decoration:none; box-shadow:0 2px 8px rgba(22,163,74,0.25); transition:background 0.15s;"
                   onmouseover="this.style.background='var(--c-green-d)'" onmouseout="this.style.background='var(--c-green)'">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    Ouvrir dans ma messagerie
                </a>
                @endif
            </div>
        </div>

        <!-- Colonne droite -->
        <div style="display:flex; flex-direction:column; gap:14px;">

            <!-- Statut -->
            <div class="crm-panel">
                <div class="section-label">Statut</div>
                <span class="badge badge-amber" style="font-size:12px; padding:4px 14px;">En attente de traitement</span>
                <div style="margin-top:12px; font-size:12px; color:var(--c-muted); line-height:1.6;">
                    Reçue le <strong>{{ $pharmacie->created_at->format('d/m/Y') }}</strong> à {{ $pharmacie->created_at->format('H:i') }}<br>
                    {{ $pharmacie->created_at->diffForHumans() }}
                </div>
            </div>

            <!-- Zone géographique -->
            @if($pharmacie->zone)
            <div class="crm-panel">
                <div class="section-label">Zone</div>
                <div style="font-size:13px; color:var(--c-text); font-weight:500;">{{ $pharmacie->zone->nom ?? '—' }}</div>
                @if($pharmacie->zone->commercial_referent ?? false)
                <div style="font-size:12px; color:var(--c-muted); margin-top:4px;">Commercial : {{ $pharmacie->zone->commercial_referent }}</div>
                @endif
            </div>
            @endif

            <!-- Actions rapides -->
            <div class="crm-panel">
                <div class="section-label">Actions</div>
                <div style="display:flex; flex-direction:column; gap:8px;">

                    @if($pharmacie->email)
                    <a href="mailto:{{ $pharmacie->email }}"
                       style="display:flex; align-items:center; gap:8px; padding:9px 12px; background:var(--c-base); border:1px solid var(--c-border); border-radius:7px; font-size:12px; font-weight:500; color:var(--c-text); text-decoration:none; transition:all 0.15s;"
                       onmouseover="this.style.borderColor='var(--c-bolder)'; this.style.background='var(--c-hover)'"
                       onmouseout="this.style.borderColor='var(--c-border)'; this.style.background='var(--c-base)'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--c-green)" stroke-width="2" stroke-linecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        {{ $pharmacie->email }}
                    </a>
                    @endif

                    @if($pharmacie->telephone)
                    <a href="tel:{{ $pharmacie->telephone }}"
                       style="display:flex; align-items:center; gap:8px; padding:9px 12px; background:var(--c-base); border:1px solid var(--c-border); border-radius:7px; font-size:12px; font-weight:500; color:var(--c-text); text-decoration:none; transition:all 0.15s;"
                       onmouseover="this.style.borderColor='var(--c-bolder)'; this.style.background='var(--c-hover)'"
                       onmouseout="this.style.borderColor='var(--c-border)'; this.style.background='var(--c-base)'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--c-green)" stroke-width="2" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.1 1.18 2 2 0 012.1 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6z"/></svg>
                        {{ $pharmacie->telephone }}
                    </a>
                    @endif

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
