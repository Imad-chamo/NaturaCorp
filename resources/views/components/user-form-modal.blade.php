@props(['roles', 'zones'])

<div x-show="modalOpen" x-cloak
     style="display:none; position:fixed; inset:0; z-index:200; align-items:center; justify-content:center; background:rgba(0,0,0,0.35); backdrop-filter:blur(3px); padding:20px;"
     :style="modalOpen ? 'display:flex' : 'display:none'">

    <form :action="modalMode === 'create' ? '{{ route('users.store') }}' : `/users/${editingUser.id}`"
          method="POST"
          style="background:#fff; border:1px solid #E2EAE3; border-radius:12px; box-shadow:0 20px 60px rgba(0,0,0,0.12); width:100%; max-width:560px; max-height:90vh; overflow-y:auto;"
          x-data="userFormModal()"
          x-effect="if (modalOpen && modalMode === 'edit') initFromEditingUser()">
        @csrf
        <template x-if="modalMode === 'edit'">
            <input type="hidden" name="_method" value="PUT">
        </template>

        <!-- Header -->
        <div style="display:flex; align-items:center; justify-content:space-between; padding:18px 22px; border-bottom:1px solid #E2EAE3; position:sticky; top:0; background:#fff; z-index:1; border-radius:12px 12px 0 0;">
            <div style="font-family:'Fraunces',Georgia,serif; font-size:17px; font-weight:500; color:#1A2B1E;"
                 x-text="modalMode === 'create' ? 'Ajouter un utilisateur' : 'Modifier l\'utilisateur'"></div>
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
                    <input name="name" type="text" required class="crm-input" :value="editingUser?.name ?? ''">
                </div>
                <div>
                    <label class="form-label">Email *</label>
                    <input name="email" type="email" required class="crm-input" :value="editingUser?.email ?? ''">
                </div>
                <div>
                    <label class="form-label">Mot de passe</label>
                    <input name="password" type="password" class="crm-input" placeholder="••••••••">
                    <div x-show="modalMode === 'edit'" style="font-size:11px; color:var(--c-muted); margin-top:4px;">Laisser vide pour ne pas modifier</div>
                </div>
                <div>
                    <label class="form-label">Confirmation</label>
                    <input name="password_confirmation" type="password" class="crm-input" placeholder="••••••••">
                </div>
                <div>
                    <label class="form-label">Rôle</label>
                    <select name="role" class="crm-input">
                        @foreach($roles as $role)
                            <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                </div>
                <div x-show="modalMode === 'edit'" style="display:flex; align-items:center; gap:10px; padding-top:20px;">
                    <input type="checkbox" name="is_active" value="1" :checked="editingUser?.is_active"
                           style="width:15px; height:15px; accent-color:#16A34A;">
                    <label style="font-size:13px; color:var(--c-text);">Compte actif</label>
                </div>
            </div>

            <!-- Zones -->
            <div>
                <label class="form-label">Zones assignées</label>
                <div style="position:relative; margin-bottom:8px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--c-muted);">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                    <input type="text" x-model="zoneSearch" placeholder="Filtrer par département…"
                           class="crm-search" style="width:100%;">
                </div>
                <div style="max-height:140px; overflow-y:auto; border:1px solid #E2EAE3; border-radius:6px; padding:8px;">
                    <template x-for="zone in filteredZones()" :key="zone.id">
                        <label style="display:flex; align-items:center; gap:8px; padding:4px 6px; border-radius:4px; cursor:pointer; font-size:13px; color:var(--c-text);"
                               onmouseover="this.style.background='#F4F7F4'" onmouseout="this.style.background='transparent'">
                            <input type="checkbox" :value="zone.id" :checked="isZoneSelected(zone.id)"
                                   @change="toggleZone(zone.id)"
                                   style="width:14px; height:14px; accent-color:#16A34A;">
                            <span x-text="zone.nom"></span>
                        </label>
                    </template>
                </div>
                <template x-for="id in selectedZones">
                    <input type="hidden" name="zones[]" :value="id">
                </template>
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

<script>
    function userFormModal() {
        return {
            zoneSearch: '',
            selectedZones: [],
            allZones: window.zonesFromLaravel,

            filteredZones() {
                return this.allZones.filter(z =>
                    z.nom.toLowerCase().includes(this.zoneSearch.toLowerCase())
                );
            },
            toggleZone(id) {
                if (this.selectedZones.includes(id)) {
                    this.selectedZones = this.selectedZones.filter(z => z !== id);
                } else {
                    this.selectedZones.push(id);
                }
            },
            isZoneSelected(id) {
                return this.selectedZones.includes(id);
            },
            initFromEditingUser() {
                this.selectedZones = this.editingUser?.zones?.map(z => z.id) || [];
            }
        }
    }
    document.addEventListener('alpine:init', () => {
        Alpine.data('userFormModal', userFormModal);
    });
</script>
