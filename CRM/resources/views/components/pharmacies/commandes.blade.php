<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
    <div style="font-family:'Fraunces',Georgia,serif; font-size:16px; font-weight:500; color:var(--c-text);">Commandes associées</div>
    <button @click="modalMode = 'create'; editingCommande = { pharmacie_id: {{ $pharmacy->id }} }; modalOpen = true"
            class="btn-primary" style="font-size:12px; padding:6px 12px;">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nouvelle commande
    </button>
</div>

<div class="crm-table-wrap">
    <table class="crm-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Produit</th>
                <th>Statut</th>
                <th class="td-right">Qté</th>
                <th class="td-right">Total</th>
                <th class="td-right">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($pharmacy->commandes->sortByDesc('date_commande') as $cmd)
            <tr>
                <td class="td-mono" style="font-size:12px;">#{{ str_pad($cmd->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td class="td-mono" style="font-size:12px;">{{ optional($cmd->date_commande)->format('d/m/Y') ?? $cmd->date_commande }}</td>
                <td style="font-size:13px; color:var(--c-muted);">{{ $cmd->produit?->nom ?? '—' }}</td>
                <td>
                    @php
                        $statut = is_string($cmd->statut) ? $cmd->statut : $cmd->statut->value;
                        $badgeClass = match($statut) {
                            'livree'    => 'badge-green',
                            'en_cours'  => 'badge-amber',
                            'validee'   => 'badge-blue',
                            'annulee'   => 'badge-gray',
                            default     => 'badge-gray',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $cmd->statut->label() }}</span>
                </td>
                <td class="td-right td-mono">{{ $cmd->quantite }}</td>
                <td class="td-right td-mono" style="color:var(--c-green);">{{ number_format($cmd->quantite * $cmd->tarif_unitaire, 2, ',', ' ') }} €</td>
                <td class="td-right">
                    <div style="display:flex; align-items:center; justify-content:flex-end; gap:10px;">
                        @if($cmd->document?->chemin)
                            <a href="{{ asset('storage/' . $cmd->document->chemin) }}" target="_blank"
                               class="link-edit" style="font-size:11px;">PDF</a>
                        @endif
                        <form method="POST" action="{{ route('commandes.destroy', $cmd) }}"
                              onsubmit="return confirm('Supprimer cette commande ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="link-del" style="background:transparent; border:none; cursor:pointer; font-size:12px;">Supprimer</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align:center; padding:32px; color:var(--c-faint); font-family:'DM Mono',monospace; font-size:12px;">
                    Aucune commande pour cette pharmacie.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<x-commande-form-modal
    :pharmacies="[$pharmacy]"
    :statuts="\App\Enums\StatutCommande::cases()"
    :produits="$produits"
/>
