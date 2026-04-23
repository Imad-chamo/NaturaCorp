<aside style="
  width: 240px; height: 100vh; position: fixed; top: 0; left: 0; z-index: 100;
  background: #FFFFFF;
  border-right: 1px solid #E0E9E1;
  box-shadow: 2px 0 12px rgba(24,39,28,0.05);
  display: flex; flex-direction: column;
  overflow: hidden;
">

  <!-- Logo -->
  <div style="padding: 20px 18px 16px; border-bottom: 1px solid #E0E9E1; flex-shrink: 0;">
    <a href="{{ route('dashboard') }}" style="display:flex; align-items:center; gap:10px; text-decoration:none;">
      <div style="width:36px; height:36px; background:#DCFCE7; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
        <svg width="22" height="22" viewBox="0 0 32 32" fill="none">
          <path d="M16 4C16 4 8 10 8 18C8 22.4 11.6 26 16 26C20.4 26 24 22.4 24 18C24 10 16 4 16 4Z" fill="#BBF7D0"/>
          <path d="M16 8C16 8 10 13 10 19C10 22.3 12.7 25 16 25C19.3 25 22 22.3 22 19C22 13 16 8 16 8Z" fill="#16A34A"/>
          <path d="M16 27V18" stroke="#92692A" stroke-width="1.5" stroke-linecap="round"/>
          <path d="M13 22C13 22 14.5 20 16 20" stroke="#92692A" stroke-width="1.2" stroke-linecap="round"/>
        </svg>
      </div>
      <div>
        <div style="font-family:'Fraunces',Georgia,serif; font-size:15px; font-weight:500; color:#18271C; letter-spacing:-0.02em; line-height:1.2;">NaturaCorp</div>
        <div style="font-size:10px; color:#9DBDA0; letter-spacing:0.06em; margin-top:1px; font-family:'DM Mono',monospace;">CRM Pro</div>
      </div>
    </a>
  </div>

  <!-- Nav -->
  <nav style="flex:1; overflow-y:auto; padding:14px 10px 10px;">

    <div style="font-size:9px; font-weight:700; color:#9DBDA0; letter-spacing:0.12em; text-transform:uppercase; padding:2px 10px 10px;">Menu</div>

    @php
      $navItem = function(string $route, string $label, string $icon, string $match) use (&$navItem): string {
          $active = request()->routeIs($match);
          return '';
      };
    @endphp

    {{-- Dashboard --}}
    @php $active = request()->routeIs('dashboard'); @endphp
    <a href="{{ route('dashboard') }}"
       style="display:flex; align-items:center; gap:10px; padding:9px 10px; text-decoration:none; border-radius:8px; margin-bottom:1px; font-size:13px; font-weight:500; transition:all 0.15s; position:relative;
              {{ $active ? 'background:#DCFCE7; color:#15803D;' : 'color:#5E8264;' }}">
      @if($active)<span style="position:absolute; left:0; top:6px; bottom:6px; width:3px; background:#16A34A; border-radius:0 2px 2px 0;"></span>@endif
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; opacity:{{ $active ? '1' : '0.7' }}">
        <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
        <rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
      </svg>
      Tableau de bord
    </a>

    @role('commercial|admin')
    @php $active = request()->routeIs('pharmacies.*'); @endphp
    <a href="{{ route('pharmacies.index') }}"
       style="display:flex; align-items:center; gap:10px; padding:9px 10px; text-decoration:none; border-radius:8px; margin-bottom:1px; font-size:13px; font-weight:500; transition:all 0.15s; position:relative;
              {{ $active ? 'background:#DCFCE7; color:#15803D;' : 'color:#5E8264;' }}"
       onmouseover="if(!this.style.background.includes('DCFCE7')) { this.style.background='#F2F6F2'; this.style.color='#18271C'; }"
       onmouseout="if(!this.style.background.includes('DCFCE7')) { this.style.background='transparent'; this.style.color='#5E8264'; }">
      @if($active)<span style="position:absolute; left:0; top:6px; bottom:6px; width:3px; background:#16A34A; border-radius:0 2px 2px 0;"></span>@endif
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; opacity:{{ $active ? '1' : '0.7' }}">
        <path d="M12 13a3 3 0 100-6 3 3 0 000 6z"/>
        <path d="M17.8 13.938h-.011a7 7 0 10-11.464.144h-.016l.14.171c.1.127.2.251.3.371L12 21l5.13-6.248c.194-.209.374-.429.54-.659l.13-.155z"/>
      </svg>
      Pharmacies
    </a>
    @endrole

    @auth
    @role('commercial|admin')
    @php
        $active = request()->routeIs('demandes.*');
        $pendingCount = \App\Models\Pharmacie::where('statut', 'prospect')->count();
    @endphp
    <a href="{{ route('demandes.index') }}"
       style="display:flex; align-items:center; gap:10px; padding:9px 10px; text-decoration:none; border-radius:8px; margin-bottom:1px; font-size:13px; font-weight:500; transition:all 0.15s; position:relative;
              {{ $active ? 'background:#DCFCE7; color:#15803D;' : 'color:#5E8264;' }}"
       onmouseover="if(!this.style.background.includes('DCFCE7')) { this.style.background='#F2F6F2'; this.style.color='#18271C'; }"
       onmouseout="if(!this.style.background.includes('DCFCE7')) { this.style.background='transparent'; this.style.color='#5E8264'; }">
      @if($active)<span style="position:absolute; left:0; top:6px; bottom:6px; width:3px; background:#16A34A; border-radius:0 2px 2px 0;"></span>@endif
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; opacity:{{ $active ? '1' : '0.7' }}">
        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
      </svg>
      <span style="flex:1;">Demandes</span>
      @if($pendingCount > 0)
        <span style="background:#D97706; color:#fff; font-size:10px; font-weight:700; padding:1px 7px; border-radius:20px; min-width:20px; text-align:center;">{{ $pendingCount }}</span>
      @endif
    </a>
    @endrole
    @endauth

    @role('commercial|admin')
    @php $active = request()->routeIs('commandes.*'); @endphp
    <a href="{{ route('commandes.index') }}"
       style="display:flex; align-items:center; gap:10px; padding:9px 10px; text-decoration:none; border-radius:8px; margin-bottom:1px; font-size:13px; font-weight:500; transition:all 0.15s; position:relative;
              {{ $active ? 'background:#DCFCE7; color:#15803D;' : 'color:#5E8264;' }}"
       onmouseover="if(!this.style.background.includes('DCFCE7')) { this.style.background='#F2F6F2'; this.style.color='#18271C'; }"
       onmouseout="if(!this.style.background.includes('DCFCE7')) { this.style.background='transparent'; this.style.color='#5E8264'; }">
      @if($active)<span style="position:absolute; left:0; top:6px; bottom:6px; width:3px; background:#16A34A; border-radius:0 2px 2px 0;"></span>@endif
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; opacity:{{ $active ? '1' : '0.7' }}">
        <path d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 100 4 2 2 0 000-4zm8 0a2 2 0 100 4 2 2 0 000-4zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6"/>
      </svg>
      Commandes
    </a>
    @endrole

    @role('commercial|admin')
    @php $active = request()->routeIs('relances.*'); @endphp
    <a href="{{ route('relances.index') }}"
       style="display:flex; align-items:center; gap:10px; padding:9px 10px; text-decoration:none; border-radius:8px; margin-bottom:1px; font-size:13px; font-weight:500; transition:all 0.15s; position:relative;
              {{ $active ? 'background:#DCFCE7; color:#15803D;' : 'color:#5E8264;' }}"
       onmouseover="if(!this.style.background.includes('DCFCE7')) { this.style.background='#F2F6F2'; this.style.color='#18271C'; }"
       onmouseout="if(!this.style.background.includes('DCFCE7')) { this.style.background='transparent'; this.style.color='#5E8264'; }">
      @if($active)<span style="position:absolute; left:0; top:6px; bottom:6px; width:3px; background:#16A34A; border-radius:0 2px 2px 0;"></span>@endif
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; opacity:{{ $active ? '1' : '0.7' }}">
        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.95 12a19.79 19.79 0 01-3.07-8.67A2 2 0 012.86 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.83a16 16 0 006.07 6.07l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
      </svg>
      Relances
    </a>
    @endrole

    @role('logistique|admin')
    @php $active = request()->routeIs('produits.*'); @endphp
    <a href="{{ route('produits.index') }}"
       style="display:flex; align-items:center; gap:10px; padding:9px 10px; text-decoration:none; border-radius:8px; margin-bottom:1px; font-size:13px; font-weight:500; transition:all 0.15s; position:relative;
              {{ $active ? 'background:#DCFCE7; color:#15803D;' : 'color:#5E8264;' }}"
       onmouseover="if(!this.style.background.includes('DCFCE7')) { this.style.background='#F2F6F2'; this.style.color='#18271C'; }"
       onmouseout="if(!this.style.background.includes('DCFCE7')) { this.style.background='transparent'; this.style.color='#5E8264'; }">
      @if($active)<span style="position:absolute; left:0; top:6px; bottom:6px; width:3px; background:#16A34A; border-radius:0 2px 2px 0;"></span>@endif
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; opacity:{{ $active ? '1' : '0.7' }}">
        <path d="M10 12v1h4v-1m4 7H6a1 1 0 01-1-1V9h14v9a1 1 0 01-1 1zM4 5h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1z"/>
      </svg>
      Produits
    </a>
    @endrole

    @php $active = request()->routeIs('carte.index'); @endphp
    <a href="{{ route('carte.index') }}"
       style="display:flex; align-items:center; gap:10px; padding:9px 10px; text-decoration:none; border-radius:8px; margin-bottom:1px; font-size:13px; font-weight:500; transition:all 0.15s; position:relative;
              {{ $active ? 'background:#DCFCE7; color:#15803D;' : 'color:#5E8264;' }}"
       onmouseover="if(!this.style.background.includes('DCFCE7')) { this.style.background='#F2F6F2'; this.style.color='#18271C'; }"
       onmouseout="if(!this.style.background.includes('DCFCE7')) { this.style.background='transparent'; this.style.color='#5E8264'; }">
      @if($active)<span style="position:absolute; left:0; top:6px; bottom:6px; width:3px; background:#16A34A; border-radius:0 2px 2px 0;"></span>@endif
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; opacity:{{ $active ? '1' : '0.7' }}">
        <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/>
        <line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/>
      </svg>
      Carte
    </a>

    @role('admin')
    <!-- Admin section -->
    <div style="margin: 18px 0 10px; padding: 0 10px;">
      <div style="height:1px; background:#E0E9E1; margin-bottom:12px;"></div>
      <div style="font-size:9px; font-weight:700; color:#9DBDA0; letter-spacing:0.12em; text-transform:uppercase;">Administration</div>
    </div>

    @foreach([
      ['users.index',    'users.*',    'Utilisateurs', '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87m-4-12a4 4 0 010 7.75"/>'],
      ['rapports.index', 'rapports.*', 'Rapports',     '<path d="M3 3v18h18M9 17v-6M15 17V9M21 17V3"/>'],
      ['admin.logs',     'admin.logs', 'Journal',       '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>'],
    ] as [$r, $match, $label, $iconPath])
      @php $active = request()->routeIs($match); @endphp
      <a href="{{ route($r) }}"
         style="display:flex; align-items:center; gap:10px; padding:9px 10px; text-decoration:none; border-radius:8px; margin-bottom:1px; font-size:13px; font-weight:500; transition:all 0.15s; position:relative;
                {{ $active ? 'background:#DCFCE7; color:#15803D;' : 'color:#5E8264;' }}"
         onmouseover="if(!this.style.background.includes('DCFCE7')) { this.style.background='#F2F6F2'; this.style.color='#18271C'; }"
         onmouseout="if(!this.style.background.includes('DCFCE7')) { this.style.background='transparent'; this.style.color='#5E8264'; }">
        @if($active)<span style="position:absolute; left:0; top:6px; bottom:6px; width:3px; background:#16A34A; border-radius:0 2px 2px 0;"></span>@endif
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; opacity:{{ $active ? '1' : '0.7' }}">{!! $iconPath !!}</svg>
        {{ $label }}
      </a>
    @endforeach
    @endrole

  </nav>

  <!-- Vitrine link -->
  <div style="padding:10px 14px; flex-shrink:0;">
    <a href="/vitrine/index.html" target="_blank"
       style="display:flex; align-items:center; justify-content:space-between; gap:8px; padding:8px 12px; background:#F2F6F2; border:1px solid #E0E9E1; border-radius:8px; text-decoration:none; transition:all 0.15s;"
       onmouseover="this.style.background='#DCFCE7'; this.style.borderColor='#C4D6C6';"
       onmouseout="this.style.background='#F2F6F2'; this.style.borderColor='#E0E9E1';">
      <div style="display:flex; align-items:center; gap:8px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#5E8264" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
          <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/>
          <path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>
        </svg>
        <span style="font-size:12px; font-weight:500; color:#5E8264; font-family:'DM Sans',sans-serif;">Voir le site vitrine</span>
      </div>
      <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9DBDA0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/>
        <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
      </svg>
    </a>
  </div>

  <!-- User footer -->
  <div style="padding:14px 14px 16px; border-top:1px solid #E0E9E1; flex-shrink:0; background:#F7FAF7;">
    <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
      <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'U') }}&background=DCFCE7&color=15803D&size=32"
           style="width:32px; height:32px; border-radius:8px; flex-shrink:0;" alt="">
      <div style="flex:1; min-width:0;">
        <div style="font-size:12px; font-weight:600; color:#18271C; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ auth()->user()->name ?? '' }}</div>
        <div style="font-size:10px; color:#9DBDA0; font-family:'DM Mono',monospace; letter-spacing:0.04em;">
          @if(auth()->user()?->hasRole('admin')) Admin
          @elseif(auth()->user()?->hasRole('commercial')) Commercial
          @elseif(auth()->user()?->hasRole('logistique')) Logistique
          @else Utilisateur @endif
        </div>
      </div>
    </div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button style="display:flex; align-items:center; gap:7px; color:#9DBDA0; font-size:12px; background:transparent; border:none; cursor:pointer; padding:5px 4px; width:100%; font-family:'DM Sans',sans-serif; transition:color 0.15s; border-radius:5px;"
              onmouseover="this.style.color='#DC2626'" onmouseout="this.style.color='#9DBDA0'">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
          <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        Déconnexion
      </button>
    </form>
  </div>
</aside>
