<header style="
  height: 58px; background: #FFFFFF;
  border-bottom: 1px solid #E0E9E1;
  padding: 0 28px;
  display: flex; align-items: center; justify-content: space-between;
  position: sticky; top: 0; z-index: 50;
  box-shadow: 0 1px 4px rgba(24,39,28,0.06);
">
  <!-- Breadcrumb -->
  <div style="display:flex; align-items:center; gap:8px; font-size:13px;">
    <span style="color:#C4D6C6; font-size:12px; font-weight:500; letter-spacing:0.01em;">NaturaCorp</span>
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#C4D6C6" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
    <span style="color:#5E8264; font-weight:600; font-size:13px;">
      @if(request()->routeIs('dashboard'))        Tableau de bord
      @elseif(request()->routeIs('pharmacies.*')) Pharmacies
      @elseif(request()->routeIs('commandes.*'))  Commandes
      @elseif(request()->routeIs('produits.*'))   Produits
      @elseif(request()->routeIs('carte.index'))  Carte
      @elseif(request()->routeIs('users.*'))      Utilisateurs
      @elseif(request()->routeIs('rapports.*'))   Rapports
      @elseif(request()->routeIs('admin.*'))      Administration
      @elseif(request()->routeIs('profile.*'))    Profil
      @else —
      @endif
    </span>
  </div>

  <!-- Right -->
  <div style="display:flex; align-items:center; gap:10px;">

    <span style="font-size:11px; color:#C4D6C6; font-family:'DM Mono',monospace; letter-spacing:0.02em;">
      {{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}
    </span>

    <div style="width:1px; height:20px; background:#E0E9E1;"></div>

    <x-notification-bell />

    <!-- Avatar dropdown -->
    <div x-data="{ open: false }" style="position:relative;">
      <button @click="open = !open"
              style="display:flex; align-items:center; gap:8px; background:transparent; border:1px solid transparent; cursor:pointer; padding:5px 8px; border-radius:8px; transition:all 0.15s;"
              :style="open ? 'background:#F2F6F2; border-color:#E0E9E1' : ''"
              onmouseover="this.style.background='#F2F6F2'; this.style.borderColor='#E0E9E1'"
              onmouseout="if(!open) { this.style.background='transparent'; this.style.borderColor='transparent'; }">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'U') }}&background=DCFCE7&color=15803D&size=28"
             style="width:26px; height:26px; border-radius:7px;" alt="">
        <span style="font-size:13px; color:#18271C; font-weight:500;">{{ auth()->user()->name ?? '' }}</span>
        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" style="color:#9DBDA0; transition:transform 0.15s;" :style="open ? 'transform:rotate(180deg)' : ''">
          <path d="M2 3.5L5 6.5L8 3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>

      <div x-show="open" @click.away="open = false"
           x-transition:enter="transition ease-out duration-100"
           x-transition:enter-start="opacity-0 scale-95"
           x-transition:enter-end="opacity-100 scale-100"
           style="display:none; position:absolute; right:0; top:calc(100% + 8px); width:188px; background:#fff; border:1px solid #E0E9E1; border-radius:10px; box-shadow:0 8px 28px rgba(24,39,28,0.1), 0 2px 6px rgba(24,39,28,0.06); overflow:hidden; z-index:200;">
        <div style="padding:10px 14px 8px; border-bottom:1px solid #F2F6F2;">
          <div style="font-size:12px; font-weight:600; color:#18271C;">{{ auth()->user()->name ?? '' }}</div>
          <div style="font-size:11px; color:#9DBDA0; margin-top:1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ auth()->user()->email ?? '' }}</div>
        </div>
        <div style="padding:4px;">
          <a href="{{ route('profile.edit') }}"
             style="display:flex; align-items:center; gap:9px; padding:8px 10px; font-size:13px; color:#18271C; text-decoration:none; border-radius:6px; transition:background 0.12s;"
             onmouseover="this.style.background='#F2F6F2'" onmouseout="this.style.background='transparent'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="color:#5E8264;">
              <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
            Mon profil
          </a>
          <div style="height:1px; background:#F2F6F2; margin:2px 0;"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button style="display:flex; align-items:center; gap:9px; padding:8px 10px; width:100%; background:transparent; border:none; cursor:pointer; font-size:13px; color:#DC2626; font-family:'DM Sans',sans-serif; text-align:left; border-radius:6px; transition:background 0.12s;"
                    onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='transparent'">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
              </svg>
              Déconnexion
            </button>
          </form>
        </div>
      </div>
    </div>

  </div>
</header>
