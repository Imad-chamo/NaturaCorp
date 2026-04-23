@props(['roles', 'zones'])

<div x-show="modalOpen" x-cloak
     style="display:none; position:fixed; inset:0; z-index:200; align-items:center; justify-content:center; background:rgba(15,25,17,0.45); backdrop-filter:blur(5px); padding:20px;"
     :style="modalOpen ? 'display:flex' : 'display:none'"
     @keydown.escape.window="modalOpen = false">

    <div x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         style="width:100%; max-width:580px;">

    <form :action="modalMode === 'create' ? '{{ route('users.store') }}' : `/users/${editingUser.id}`"
          method="POST"
          style="background:var(--c-surface); border:1px solid var(--c-border); border-radius:16px; box-shadow:var(--shadow-xl); overflow:hidden; max-height:92vh; display:flex; flex-direction:column;"
          x-data="userFormModal()"
          x-effect="if (modalOpen && modalMode === 'edit') initFromEditingUser()">
        @csrf
        <template x-if="modalMode === 'edit'">
            <input type="hidden" name="_method" value="PUT">
        </template>

        <!-- ── Header ── -->
        <div style="display:flex; align-items:center; gap:16px; padding:20px 24px; border-bottom:1px solid var(--c-border); background:var(--c-raised); flex-shrink:0;">
            <!-- Dynamic avatar -->
            <div style="width:48px; height:48px; border-radius:12px; background:var(--c-green-l); border:1.5px solid rgba(22,163,74,0.2); display:flex; align-items:center; justify-content:center; flex-shrink:0; overflow:hidden; box-shadow:0 2px 8px rgba(22,163,74,0.15);">
                <span style="font-family:'Fraunces',Georgia,serif; font-size:20px; font-weight:500; color:var(--c-green-d); text-transform:uppercase; line-height:1;"
                      x-text="(editingUser?.name || newName || '?').charAt(0)"></span>
            </div>
            <div style="flex:1;">
                <div style="font-family:'Fraunces',Georgia,serif; font-size:17px; font-weight:500; color:var(--c-text);"
                     x-text="modalMode === 'create' ? 'Ajouter un utilisateur' : 'Modifier l\'utilisateur'"></div>
                <div style="font-size:12px; color:var(--c-muted); margin-top:2px;"
                     x-text="modalMode === 'create' ? 'Créer un nouveau compte d\'accès CRM' : (editingUser?.email ?? '')"></div>
            </div>
            <button type="button" @click="modalOpen = false"
                    style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; color:var(--c-faint); cursor:pointer; border:1px solid transparent; border-radius:8px; background:transparent; transition:all 0.15s;"
                    onmouseover="this.style.borderColor='var(--c-border)'; this.style.background='var(--c-hover)'; this.style.color='var(--c-text)'"
                    onmouseout="this.style.borderColor='transparent'; this.style.background='transparent'; this.style.color='var(--c-faint)'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <!-- ── Scrollable body ── -->
        <div style="overflow-y:auto; flex:1; padding:22px 24px; display:flex; flex-direction:column; gap:20px;">

            <!-- Section: Informations -->
            <div>
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:14px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--c-faint)" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span style="font-size:10px; font-weight:700; color:var(--c-faint); letter-spacing:0.10em; text-transform:uppercase;">Informations</span>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div>
                        <label class="form-label">Nom complet *</label>
                        <input name="name" type="text" required class="crm-input"
                               :value="editingUser?.name ?? ''"
                               @input="newName = $event.target.value"
                               placeholder="Jean Dupont">
                    </div>
                    <div>
                        <label class="form-label">Adresse email *</label>
                        <input name="email" type="email" required class="crm-input"
                               :value="editingUser?.email ?? ''"
                               placeholder="jean@naturacorp.fr">
                    </div>
                </div>
            </div>

            <!-- Section: Sécurité -->
            <div>
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:14px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--c-faint)" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    <span style="font-size:10px; font-weight:700; color:var(--c-faint); letter-spacing:0.10em; text-transform:uppercase;">Sécurité</span>
                    <span x-show="modalMode === 'edit'" style="font-size:10px; color:var(--c-faint); font-style:italic; font-weight:400; text-transform:none; letter-spacing:0;">(laisser vide pour ne pas modifier)</span>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div>
                        <label class="form-label">Mot de passe <template x-if="modalMode === 'create'"><span style="color:var(--c-red);">*</span></template></label>
                        <div style="position:relative;">
                            <input name="password" :type="showPwd ? 'text' : 'password'" class="crm-input"
                                   :required="modalMode === 'create'"
                                   placeholder="••••••••" style="padding-right:38px;"
                                   @input="checkStrength($event.target.value)">
                            <button type="button" @click="showPwd = !showPwd"
                                    style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:transparent; border:none; cursor:pointer; color:var(--c-faint); padding:2px; display:flex;">
                                <svg x-show="!showPwd" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg x-show="showPwd" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                            </button>
                        </div>
                        <!-- Strength bar -->
                        <div x-show="pwdStrength > 0" style="margin-top:6px;">
                            <div style="display:flex; gap:3px; margin-bottom:3px;">
                                <div style="height:3px; flex:1; border-radius:2px; transition:background 0.2s;"
                                     :style="{ background: pwdStrength >= 1 ? pwdColor : '#E0E9E1' }"></div>
                                <div style="height:3px; flex:1; border-radius:2px; transition:background 0.2s;"
                                     :style="{ background: pwdStrength >= 2 ? pwdColor : '#E0E9E1' }"></div>
                                <div style="height:3px; flex:1; border-radius:2px; transition:background 0.2s;"
                                     :style="{ background: pwdStrength >= 3 ? pwdColor : '#E0E9E1' }"></div>
                                <div style="height:3px; flex:1; border-radius:2px; transition:background 0.2s;"
                                     :style="{ background: pwdStrength >= 4 ? pwdColor : '#E0E9E1' }"></div>
                            </div>
                            <span style="font-size:10px; font-weight:600;" :style="{ color: pwdColor }" x-text="pwdLabel"></span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Confirmation</label>
                        <div style="position:relative;">
                            <input name="password_confirmation" :type="showPwd ? 'text' : 'password'" class="crm-input"
                                   placeholder="••••••••">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Rôle -->
            <div>
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:14px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--c-faint)" stroke-width="2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span style="font-size:10px; font-weight:700; color:var(--c-faint); letter-spacing:0.10em; text-transform:uppercase;">Rôle & accès</span>
                </div>

                <!-- Hidden select for form submission -->
                <select name="role" x-model="selectedRole" style="display:none;">
                    @foreach($roles as $role)
                        <option value="{{ $role }}">{{ $role }}</option>
                    @endforeach
                </select>

                <!-- Visual role cards -->
                <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:10px; margin-bottom:16px;">
                    @foreach($roles as $role)
                    @php
                        $config = match($role) {
                            'admin'      => ['#2563EB', '#DBEAFE', 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z', 'Accès complet'],
                            'commercial' => ['#16A34A', '#DCFCE7', 'M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z', 'Pharmacies & commandes'],
                            'logistique' => ['#D97706', '#FEF3C7', 'M1 3h15v13H1zM16 8l4 2v6h-4', 'Produits & stock'],
                            default      => ['#6B7280', '#F3F4F6', 'M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2', 'Accès standard'],
                        };
                    @endphp
                    <label style="cursor:pointer;">
                        <input type="radio" name="_role_visual" value="{{ $role }}"
                               x-model="selectedRole" style="display:none;">
                        <div style="padding:12px; border-radius:10px; border:2px solid; text-align:center; transition:all 0.15s;"
                             :style="selectedRole === '{{ $role }}'
                                ? 'border-color:{{ $config[0] }}; background:{{ $config[1] }};'
                                : 'border-color:var(--c-border); background:var(--c-surface);'"
                             onmouseover="if(this.parentElement.querySelector('input').value !== this.closest('[x-data]')?.getAttribute('x-model')) this.style.borderColor='var(--c-bolder)'"
                             onmouseout="this.style.borderColor=''">
                            <div style="width:32px; height:32px; border-radius:8px; margin:0 auto 8px; display:flex; align-items:center; justify-content:center; transition:background 0.15s;"
                                 :style="selectedRole === '{{ $role }}' ? 'background:{{ $config[0] }}20' : 'background:var(--c-raised)'">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="{{ $config[0] }}" stroke-width="2" stroke-linecap="round"><path d="{{ $config[2] }}"/></svg>
                            </div>
                            <div style="font-size:12px; font-weight:600; color:{{ $config[0] }}; margin-bottom:2px;">{{ ucfirst($role) }}</div>
                            <div style="font-size:10px; color:var(--c-faint);">{{ $config[3] }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>

                <!-- Active toggle (edit only) -->
                <div x-show="modalMode === 'edit'"
                     style="display:flex; align-items:center; justify-content:space-between; padding:12px 14px; background:var(--c-raised); border:1px solid var(--c-border); border-radius:10px;">
                    <div>
                        <div style="font-size:13px; font-weight:500; color:var(--c-text);">Compte actif</div>
                        <div style="font-size:11px; color:var(--c-muted); margin-top:1px;">L'utilisateur peut se connecter au CRM</div>
                    </div>
                    <label style="position:relative; display:inline-block; width:42px; height:24px; cursor:pointer; flex-shrink:0;">
                        <input type="checkbox" name="is_active" value="1"
                               :checked="isActiveToggle"
                               @change="isActiveToggle = $event.target.checked"
                               style="opacity:0; width:0; height:0; position:absolute;">
                        <span style="position:absolute; inset:0; border-radius:24px; transition:background 0.2s;"
                              :style="isActiveToggle ? 'background:#16A34A' : 'background:#D1D5DB'"></span>
                        <span style="position:absolute; top:3px; width:18px; height:18px; background:#fff; border-radius:50%; box-shadow:0 1px 3px rgba(0,0,0,0.2); transition:transform 0.2s;"
                              :style="isActiveToggle ? 'transform:translateX(21px)' : 'transform:translateX(3px)'"></span>
                    </label>
                </div>
            </div>

            <!-- Section: Zones -->
            <div>
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:14px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--c-faint)" stroke-width="2" stroke-linecap="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                    <span style="font-size:10px; font-weight:700; color:var(--c-faint); letter-spacing:0.10em; text-transform:uppercase;">Zones assignées</span>
                    <span style="margin-left:auto; font-family:'DM Mono',monospace; font-size:10px; color:var(--c-muted);"
                          x-text="selectedZones.length + ' sélectionnée(s)'"></span>
                </div>

                <div style="position:relative; margin-bottom:8px;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--c-faint); pointer-events:none;"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                    <input type="text" x-model="zoneSearch" placeholder="Filtrer les zones…" class="crm-search" style="width:100%;">
                </div>

                <div style="max-height:130px; overflow-y:auto; border:1px solid var(--c-border); border-radius:8px; padding:6px;">
                    <template x-for="zone in filteredZones()" :key="zone.id">
                        <label style="display:flex; align-items:center; gap:9px; padding:5px 8px; border-radius:6px; cursor:pointer; transition:background 0.12s;"
                               onmouseover="this.style.background='var(--c-hover)'" onmouseout="this.style.background='transparent'">
                            <div style="width:16px; height:16px; border-radius:4px; border:2px solid; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:all 0.15s;"
                                 :style="isZoneSelected(zone.id) ? 'border-color:#16A34A; background:#16A34A;' : 'border-color:#C4D6C6; background:transparent;'">
                                <svg x-show="isZoneSelected(zone.id)" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <input type="checkbox" :value="zone.id" :checked="isZoneSelected(zone.id)"
                                   @change="toggleZone(zone.id)" style="display:none;">
                            <span style="font-size:13px; color:var(--c-text);" x-text="zone.nom"></span>
                        </label>
                    </template>
                    <template x-if="filteredZones().length === 0">
                        <div style="padding:12px; text-align:center; font-size:12px; color:var(--c-faint);">Aucune zone trouvée</div>
                    </template>
                </div>

                <template x-for="id in selectedZones">
                    <input type="hidden" name="zones[]" :value="id">
                </template>
            </div>
        </div>

        <!-- ── Footer ── -->
        <div style="display:flex; align-items:center; padding:16px 24px; border-top:1px solid var(--c-border); background:var(--c-raised); flex-shrink:0; gap:10px;">
            <!-- Delete (edit mode only) -->
            <template x-if="modalMode === 'edit'">
                <form :action="`/users/${editingUser.id}`" method="POST"
                      @submit.prevent="if(confirm('Supprimer cet utilisateur ? Cette action est irréversible.')) $el.submit()">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit"
                            style="display:inline-flex; align-items:center; gap:6px; padding:8px 12px; background:transparent; border:1px solid var(--c-border); border-radius:7px; cursor:pointer; font-size:12px; color:var(--c-faint); font-family:'DM Sans',sans-serif; transition:all 0.15s;"
                            onmouseover="this.style.borderColor='#DC2626'; this.style.background='#FEE2E2'; this.style.color='#DC2626'"
                            onmouseout="this.style.borderColor='var(--c-border)'; this.style.background='transparent'; this.style.color='var(--c-faint)'">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                        Supprimer
                    </button>
                </form>
            </template>

            <div style="flex:1;"></div>

            <button type="button" @click="modalOpen = false" class="btn-secondary">Annuler</button>
            <button type="submit" class="btn-primary" style="min-width:130px; justify-content:center;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <template x-if="modalMode === 'create'"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/></template>
                    <template x-if="modalMode === 'edit'"><polyline points="20 6 9 17 4 12"/></template>
                </svg>
                <span x-text="modalMode === 'create' ? 'Créer le compte' : 'Enregistrer'"></span>
            </button>
        </div>
    </form>
    </div>
</div>

<script>
    function userFormModal() {
        return {
            zoneSearch: '',
            selectedZones: [],
            selectedRole: '{{ $roles->first() ?? '' }}',
            isActiveToggle: true,
            showPwd: false,
            pwdStrength: 0,
            pwdLabel: '',
            pwdColor: '',
            newName: '',
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
                this.selectedRole  = this.editingUser?.roles?.[0]?.name || '{{ $roles->first() ?? '' }}';
                this.isActiveToggle = this.editingUser?.is_active ?? true;
                this.pwdStrength = 0;
                this.newName = this.editingUser?.name || '';
            },
            checkStrength(val) {
                if (!val) { this.pwdStrength = 0; return; }
                let score = 0;
                if (val.length >= 8)                   score++;
                if (val.length >= 12)                  score++;
                if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
                if (/\d/.test(val))                    score++;
                if (/[^a-zA-Z0-9]/.test(val))         score++;
                this.pwdStrength = Math.min(4, score);
                const levels = [
                    [0, '', ''],
                    [1, '#DC2626', 'Très faible'],
                    [2, '#D97706', 'Faible'],
                    [3, '#CA8A04', 'Moyen'],
                    [4, '#16A34A', 'Fort'],
                ];
                [, this.pwdColor, this.pwdLabel] = levels[this.pwdStrength] || levels[0];
            }
        }
    }
    document.addEventListener('alpine:init', () => {
        Alpine.data('userFormModal', userFormModal);
    });
</script>
