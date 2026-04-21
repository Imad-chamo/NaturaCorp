@props(['commerciaux'])

<div x-show="modalOpen" x-cloak
     style="display:none; position:fixed; inset:0; z-index:200; align-items:center; justify-content:center; background:rgba(0,0,0,0.35); backdrop-filter:blur(3px); padding:20px;"
     :style="modalOpen ? 'display:flex' : 'display:none'">

  <form :action="modalMode === 'create' ? '{{ route('pharmacies.store') }}' : `/pharmacies/${editingPharmacie.id}`"
        method="POST"
        style="background:#fff; border:1px solid #E2EAE3; border-radius:12px; box-shadow:0 20px 60px rgba(0,0,0,0.12); width:100%; max-width:680px; max-height:90vh; overflow-y:auto;">
    @csrf

    <template x-if="modalMode === 'edit'">
      <input type="hidden" name="_method" value="PUT">
    </template>

    <!-- Header -->
    <div style="display:flex; align-items:center; justify-content:space-between; padding:18px 22px; border-bottom:1px solid #E2EAE3; position:sticky; top:0; background:#fff; z-index:1; border-radius:12px 12px 0 0;">
      <div>
        <div style="font-family:'Fraunces',Georgia,serif; font-size:17px; font-weight:500; color:#1A2B1E;"
             x-text="modalMode === 'create' ? 'Nouvelle pharmacie' : 'Modifier la pharmacie'"></div>
        <div style="font-size:11px; color:#A8C4AB; margin-top:2px;" x-show="modalMode === 'edit'"
             x-text="`ID: ${editingPharmacie.id}`"></div>
      </div>
      <button type="button" @click="modalOpen = false"
              style="width:30px; height:30px; display:flex; align-items:center; justify-content:center; color:#A8C4AB; cursor:pointer; border:1px solid transparent; border-radius:6px; background:transparent; font-size:20px; line-height:1; transition:all 0.15s;"
              onmouseover="this.style.borderColor='#E2EAE3'; this.style.background='#F4F7F4'; this.style.color='#6B9270'"
              onmouseout="this.style.borderColor='transparent'; this.style.background='transparent'; this.style.color='#A8C4AB'">&times;</button>
    </div>

    <!-- Body -->
    <div style="padding:22px; display:flex; flex-direction:column; gap:14px;">
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <div>
          <label class="form-label">Nom *</label>
          <input name="nom" required class="crm-input" :value="editingPharmacie?.nom ?? ''">
        </div>
        <div>
          <label class="form-label">SIRET</label>
          <input name="siret" class="crm-input" :value="editingPharmacie?.siret ?? ''">
        </div>
        <div>
          <label class="form-label">Email</label>
          <input name="email" type="email" class="crm-input" :value="editingPharmacie?.email ?? ''">
        </div>
        <div>
          <label class="form-label">Téléphone</label>
          <input name="telephone" class="crm-input" :value="editingPharmacie?.telephone ?? ''">
        </div>
        <div style="grid-column:1/-1;">
          <label class="form-label">Adresse *</label>
          <input name="adresse" required class="crm-input" :value="editingPharmacie?.adresse ?? ''">
        </div>
        <div>
          <label class="form-label">Code postal *</label>
          <input name="code_postal" required class="crm-input" :value="editingPharmacie?.code_postal ?? ''">
        </div>
        <div>
          <label class="form-label">Ville *</label>
          <input name="ville" required class="crm-input" :value="editingPharmacie?.ville ?? ''">
        </div>
        <div>
          <label class="form-label">Statut</label>
          <select name="statut" class="crm-input">
            <option value="prospect"       :selected="editingPharmacie?.statut === 'prospect'">Prospect</option>
            <option value="client_actif"   :selected="editingPharmacie?.statut === 'client_actif'">Client actif</option>
            <option value="client_inactif" :selected="editingPharmacie?.statut === 'client_inactif'">Client inactif</option>
          </select>
        </div>
        <div>
          <label class="form-label">Commercial</label>
          <select name="commercial_id" class="crm-input" x-model="editingPharmacie.commercial_id">
            <option value="">—</option>
            @foreach($commerciaux as $user)
              <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="form-label">Dernière prise de contact</label>
          <input name="derniere_prise_contact" type="date" class="crm-input" :value="editingPharmacie?.derniere_prise_contact ?? ''">
        </div>
      </div>

      <div style="display:flex; gap:10px; justify-content:flex-end; border-top:1px solid #E2EAE3; padding-top:16px; margin-top:4px;">
        <button type="button" @click="modalOpen = false" class="btn-secondary">Annuler</button>
        <button type="submit" class="btn-primary">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
          Enregistrer
        </button>
      </div>
    </div>
  </form>
</div>
