<form method="POST" action="{{ route('pharmacies.update', $pharmacy) }}"
      style="display:flex; flex-direction:column; gap:16px;">
    @csrf
    @method('PUT')

    <div style="font-family:'Fraunces',Georgia,serif; font-size:16px; font-weight:500; color:var(--c-text); padding-bottom:12px; border-bottom:1px solid var(--c-border);">
        Informations générales
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        @foreach ([
            'nom'                    => ['Nom *',                  'text'],
            'siret'                  => ['SIRET',                  'text'],
            'email'                  => ['Email',                  'email'],
            'telephone'              => ['Téléphone',              'text'],
        ] as $field => [$label, $type])
            <div>
                <label class="form-label">{{ $label }}</label>
                <input name="{{ $field }}" type="{{ $type }}" class="crm-input"
                       value="{{ old($field, $pharmacy->$field) }}">
            </div>
        @endforeach

        <div style="grid-column:1/-1;">
            <label class="form-label">Adresse</label>
            <input name="adresse" type="text" class="crm-input" value="{{ old('adresse', $pharmacy->adresse) }}">
        </div>

        <div>
            <label class="form-label">Code postal</label>
            <input name="code_postal" type="text" class="crm-input" value="{{ old('code_postal', $pharmacy->code_postal) }}">
        </div>
        <div>
            <label class="form-label">Ville</label>
            <input name="ville" type="text" class="crm-input" value="{{ old('ville', $pharmacy->ville) }}">
        </div>

        <div>
            <label class="form-label">Statut</label>
            <select name="statut" class="crm-input">
                <option value="prospect"       @selected($pharmacy->statut === 'prospect')>Prospect</option>
                <option value="client_actif"   @selected($pharmacy->statut === 'client_actif')>Client actif</option>
                <option value="client_inactif" @selected($pharmacy->statut === 'client_inactif')>Client inactif</option>
            </select>
        </div>
        <div>
            <label class="form-label">Dernière prise de contact</label>
            <input name="derniere_prise_contact" type="date" class="crm-input"
                   value="{{ old('derniere_prise_contact', $pharmacy->derniere_prise_contact) }}">
        </div>
    </div>

    <div style="display:flex; align-items:center; justify-content:space-between; padding-top:12px; border-top:1px solid var(--c-border); margin-top:4px;">
        <form method="POST" action="{{ route('pharmacies.destroy', $pharmacy) }}"
              onsubmit="return confirm('Supprimer définitivement cette pharmacie ?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    style="display:inline-flex; align-items:center; gap:6px; background:transparent; border:1px solid var(--c-border); color:var(--c-red); font-family:'DM Sans',sans-serif; font-size:12px; font-weight:600; padding:7px 12px; border-radius:6px; cursor:pointer; transition:all 0.15s;"
                    onmouseover="this.style.background='#FEE2E2'; this.style.borderColor='rgba(220,38,38,0.3)'"
                    onmouseout="this.style.background='transparent'; this.style.borderColor='var(--c-border)'">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                Supprimer
            </button>
        </form>

        <button type="submit" class="btn-primary">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            Enregistrer
        </button>
    </div>
</form>
