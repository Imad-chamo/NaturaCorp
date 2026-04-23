<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="crm-page-title">Utilisateurs</div>
            <div class="crm-page-sub">Gestion des accès et rôles</div>
        </div>
        <button onclick="window.dispatchEvent(new CustomEvent('open-create-user'))"
                class="btn-primary">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Ajouter un utilisateur
        </button>
    </x-slot>

    <script>
        window.usersFromLaravel = @json($users);
        window.zonesFromLaravel = @json($zones);
    </script>

    @if(session('success'))
        <div class="flash-success">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div x-data="initUserTable(window.usersFromLaravel, window.zonesFromLaravel)"
         @open-create-user.window="modalMode = 'create'; editingUser = {}; selectedZones = []; modalOpen = true"
         @open-edit-user.window="modalMode = 'edit'; editingUser = $event.detail; selectedZones = ($event.detail.zones||[]).map(z=>z.id); modalOpen = true">

        <!-- Toolbar -->
        <div class="crm-toolbar">
            <div style="position:relative;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--c-muted);">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
                <input type="text" x-model="search" placeholder="Rechercher nom, email…" class="crm-search" style="width:240px;">
            </div>
            <span style="margin-left:auto; font-family:'DM Mono',monospace; font-size:11px; color:var(--c-muted);"
                  x-text="filteredUsers().length + ' utilisateur(s)'"></span>
        </div>

        <!-- Table -->
        <div class="crm-table-wrap">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th class="td-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="user in filteredUsers()" :key="user.id">
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div style="width:30px; height:30px; border-radius:8px; background:var(--c-green-l); display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:600; color:var(--c-green-d); flex-shrink:0;"
                                         x-text="user.name.charAt(0).toUpperCase()"></div>
                                    <span style="font-weight:500; color:var(--c-text);" x-text="user.name"></span>
                                </div>
                            </td>
                            <td class="td-mono" style="font-size:12px;" x-text="user.email"></td>
                            <td>
                                <span class="badge"
                                      :class="{
                                          'badge-blue':  user.roles[0]?.name === 'admin',
                                          'badge-green': user.roles[0]?.name === 'commercial',
                                          'badge-amber': user.roles[0]?.name === 'logistique',
                                          'badge-gray':  !user.roles[0]?.name,
                                      }"
                                      x-text="user.roles[0]?.name ? user.roles[0].name.charAt(0).toUpperCase() + user.roles[0].name.slice(1) : '—'">
                                </span>
                            </td>
                            <td>
                                <span class="badge"
                                      :class="user.is_active ? 'badge-green' : 'badge-gray'"
                                      x-text="user.is_active ? 'Actif' : 'Inactif'">
                                </span>
                            </td>
                            <td class="td-right">
                                <button @click="modalMode = 'edit'; editingUser = user; selectedZones = (user.zones || []).map(z => z.id); modalOpen = true"
                                        class="link-edit">Modifier</button>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredUsers().length === 0">
                        <tr>
                            <td colspan="5" style="text-align:center; padding:40px; color:var(--c-faint); font-family:'DM Mono',monospace; font-size:12px;">
                                Aucun utilisateur trouvé
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <x-user-form-modal :roles="$roles" :zones="$zones" />
    </div>

    <script>
        function initUserTable(users, zones) {
            return {
                search: '',
                modalOpen: false,
                modalMode: 'create',
                editingUser: {},
                selectedZones: [],
                users: users,
                zones: zones,

                filteredUsers() {
                    const s = this.search.toLowerCase();
                    return this.users.filter(u =>
                        u.name.toLowerCase().includes(s) || u.email.toLowerCase().includes(s)
                    );
                }
            }
        }
    </script>
</x-app-layout>
