<x-guest-layout>

    <x-auth-session-status :status="session('status')"
        style="background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; padding:10px 14px; border-radius:6px; font-size:12px; margin-bottom:16px; font-family:'DM Mono',monospace;" />

    <h2 style="font-family:'Fraunces',Georgia,serif; font-size:18px; font-weight:400; color:#1A2B1E; margin-bottom:24px; text-align:center; letter-spacing:-0.01em;">
        Connexion
    </h2>

    <form method="POST" action="{{ route('login') }}" style="display:flex; flex-direction:column; gap:16px;">
        @csrf

        <div>
            <label style="display:block; font-size:11px; font-weight:600; letter-spacing:0.05em; text-transform:uppercase; color:#6B9270; margin-bottom:6px;">
                Adresse email
            </label>
            <input id="email" name="email" type="email" autocomplete="username" required autofocus
                   value="{{ old('email') }}"
                   class="guest-input" placeholder="vous@naturacorp.fr">
            <x-input-error :messages="$errors->get('email')"
                style="font-size:11px; color:#DC2626; margin-top:4px;" />
        </div>

        <div>
            <label style="display:block; font-size:11px; font-weight:600; letter-spacing:0.05em; text-transform:uppercase; color:#6B9270; margin-bottom:6px;">
                Mot de passe
            </label>
            <input id="password" name="password" type="password" autocomplete="current-password" required
                   class="guest-input" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')"
                style="font-size:11px; color:#DC2626; margin-top:4px;" />
        </div>

        <div style="display:flex; align-items:center; justify-content:space-between;">
            <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:12px; color:#6B9270;">
                <input id="remember_me" type="checkbox" name="remember" style="width:14px; height:14px; accent-color:#16A34A;">
                Se souvenir de moi
            </label>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   style="font-size:11px; color:#A8C4AB; text-decoration:none; transition:color 0.15s;"
                   onmouseover="this.style.color='#16A34A'" onmouseout="this.style.color='#A8C4AB'">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <button type="submit"
                style="width:100%; padding:11px 16px; background:#16A34A; color:#fff; border:none; cursor:pointer; font-family:'DM Sans',sans-serif; font-size:13px; font-weight:500; letter-spacing:0.03em; border-radius:6px; margin-top:4px; transition:background 0.18s; display:flex; align-items:center; justify-content:center; gap:8px;"
                onmouseover="this.style.background='#15803D'" onmouseout="this.style.background='#16A34A'">
            Se connecter
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
                <polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
        </button>
    </form>

</x-guest-layout>
