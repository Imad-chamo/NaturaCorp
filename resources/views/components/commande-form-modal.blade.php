<div x-show="modalOpen" x-cloak
     style="display:none; position:fixed; inset:0; z-index:200; align-items:center; justify-content:center; background:rgba(0,0,0,0.35); backdrop-filter:blur(3px); padding:20px;"
     :style="modalOpen ? 'display:flex' : 'display:none'"
     x-data="commandeModal()" x-init="init()">

  <form method="POST"
        :action="modalMode === 'edit' ? '/commandes/' + editingCommande.id : '/commandes'"
        style="background:var(--c-surface); border:1px solid var(--c-bolder); border-radius:5px; width:100%; max-width:580px; max-height:90vh; overflow-y:auto;">
    @csrf
    <template x-if="modalMode === 'edit'">
      <input type="hidden" name="_method" value="PUT">
    </template>

    <!-- Header -->
    <div style="display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid var(--c-border); position:sticky; top:0; background:var(--c-surface); z-index:1;">
      <div style="font-family:'Fraunces',Georgia,serif; font-size:16px; font-weight:400; color:var(--c-text);"
           x-text="modalMode === 'edit' ? 'Modifier la commande' : 'Nouvelle commande'"></div>
      <button type="button" @click="modalOpen = false"
              style="width:28px; height:28px; display:flex; align-items:center; justify-content:center; color:var(--c-muted); cursor:pointer; border:1px solid transparent; border-radius:3px; background:transparent; font-size:18px; line-height:1; transition:all 0.15s;"
              onmouseover="this.style.borderColor='var(--c-border)'; this.style.color='var(--c-text)'"
              onmouseout="this.style.borderColor='transparent'; this.style.color='var(--c-muted)'">&times;</button>
    </div>

    <!-- Body -->
    <div style="padding:20px; display:flex; flex-direction:column; gap:14px;">

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <div>
          <label class="form-label">Pharmacie</label>
          <select name="pharmacie_id" x-model="editingCommande.pharmacie_id" class="crm-input">
            @foreach($pharmacies as $ph)
              <option value="{{ $ph->id }}">{{ $ph->nom }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="form-label">Produit</label>
          <select name="produit_id" x-model="editingCommande.produit_id" @change="updateTarifEtStock()" class="crm-input">
            @foreach($produits as $produit)
              <option value="{{ $produit->id }}">{{ $produit->nom }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="form-label">Date commande</label>
          <input type="date" name="date_commande" x-model="editingCommande.date_commande" class="crm-input">
        </div>

        <div>
          <label class="form-label">Statut</label>
          <select name="statut" x-model="editingCommande.statut" class="crm-input">
            @foreach($statuts as $statut)
              <option value="{{ $statut->value }}">{{ $statut->label() }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="form-label">Quantité</label>
          <input type="number" name="quantite" x-model="editingCommande.quantite"
                 :max="quantiteMaxAutorisee()" min="1" class="crm-input">
          <div style="font-size:11px; color:var(--c-muted); margin-top:4px; font-family:'DM Mono',monospace;">
            Stock dispo : <span x-text="stockProduitActuel()"></span> u.
          </div>
        </div>

        <div>
          <label class="form-label">Tarif unitaire (€)</label>
          <input type="text" name="tarif_unitaire" x-model="editingCommande.tarif_unitaire"
                 class="crm-input" readonly style="opacity:0.6; cursor:not-allowed;">
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-label">Observations</label>
          <textarea name="observations" x-model="editingCommande.observations" class="crm-input" rows="2"></textarea>
        </div>
      </div>

      <!-- Actions -->
      <div style="display:flex; gap:10px; justify-content:flex-end; border-top:1px solid var(--c-border); padding-top:16px; margin-top:4px;">
        <button type="button" @click="modalOpen = false" class="btn-secondary">Annuler</button>
        <button type="submit" class="btn-primary">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
          <span x-text="modalMode === 'edit' ? 'Mettre à jour' : 'Créer la commande'"></span>
        </button>
      </div>
    </div>
  </form>
</div>

<script>
function commandeModal() {
    return {
        produits: window.produitsFromLaravel,

        init() {
            this.$watch('editingCommande', () => this.updateTarifEtStock());
        },

        updateTarifEtStock() {
            const produit = this.produits.find(p => p.id == this.editingCommande?.produit_id);
            if (!produit) return;
            if (this.modalMode === 'create') {
                this.editingCommande.tarif_unitaire = produit.tarif_unitaire;
            }
        },

        stockProduitActuel() {
            const produit = this.produits.find(p => p.id == this.editingCommande.produit_id);
            return produit ? produit.stock : 0;
        },

        quantiteMaxAutorisee() {
            const produit = this.produits.find(p => p.id == this.editingCommande.produit_id);
            if (!produit) return 0;
            const initiale = parseInt(this.editingCommande.quantite_initiale) || 0;
            return this.modalMode === 'edit' ? produit.stock + initiale : produit.stock;
        }
    };
}
</script>
