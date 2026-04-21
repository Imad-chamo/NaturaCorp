<div style="font-family:'Fraunces',Georgia,serif; font-size:16px; font-weight:500; color:var(--c-text); padding-bottom:12px; border-bottom:1px solid var(--c-border); margin-bottom:16px;">
    Documents joints
</div>

<div class="crm-table-wrap" style="margin-bottom:16px;">
    <table class="crm-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Type</th>
                <th>Date</th>
                <th class="td-right">Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($pharmacy->documents as $doc)
            <tr>
                <td style="font-weight:500; color:var(--c-text); font-size:13px;">{{ $doc->nom_fichier }}</td>
                <td>
                    <span class="badge badge-gray" style="text-transform:capitalize;">{{ str_replace('_', ' ', $doc->type) }}</span>
                </td>
                <td class="td-mono" style="font-size:12px;">{{ $doc->created_at->format('d/m/Y') }}</td>
                <td class="td-right">
                    <a href="{{ asset('storage/' . $doc->chemin) }}" target="_blank" class="link-edit" style="font-size:12px;">
                        Télécharger
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="text-align:center; padding:24px; color:var(--c-faint); font-family:'DM Mono',monospace; font-size:12px;">
                    Aucun document.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data"
      style="display:grid; grid-template-columns:1fr 1fr auto; gap:10px; align-items:end; border-top:1px solid var(--c-border); padding-top:14px;">
    @csrf
    <input type="hidden" name="pharmacie_id" value="{{ $pharmacy->id }}">

    <div>
        <label class="form-label">Type</label>
        <select name="type" class="crm-input" required>
            <option value="">— Sélectionner —</option>
            <option value="contrat">Contrat</option>
            <option value="devis">Devis</option>
            <option value="document_reglementaire">Document réglementaire</option>
            <option value="autre">Autre</option>
        </select>
    </div>

    <div>
        <label class="form-label">Fichier</label>
        <input type="file" name="fichier" required
               style="display:block; width:100%; background:var(--c-surface); border:1px solid var(--c-border); border-radius:6px; padding:7px 10px; font-family:'DM Sans',sans-serif; font-size:12px; color:var(--c-muted); cursor:pointer;">
    </div>

    <button type="submit" class="btn-primary" style="white-space:nowrap;">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        Ajouter
    </button>
</form>
