<x-guest-layout>

    <h2 style="font-family:'Fraunces',Georgia,serif; font-size:18px; font-weight:400; color:#1A2B1E; margin-bottom:24px; text-align:center; letter-spacing:-0.01em;">
        Nouveau mot de passe
    </h2>

    <form method="POST" action="{{ route('password.store') }}" style="display:flex; flex-direction:column; gap:16px;">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label style="display:block; font-size:11px; font-weight:600; letter-spacing:0.05em; text-transform:uppercase; color:#6B9270; margin-bottom:6px;">
                Adresse email
            </label>
            <input id="email" name="email" type="email" class="guest-input"
                   value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            @error('email') <div style="font-size:11px; color:#DC2626; margin-top:4px;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label style="display:block; font-size:11px; font-weight:600; letter-spacing:0.05em; text-transform:uppercase; color:#6B9270; margin-bottom:6px;">
                Nouveau mot de passe
            </label>
            <input id="password" name="password" type="password" class="guest-input"
                   required autocomplete="new-password" placeholder="••••••••">
            @error('password') <div style="font-size:11px; color:#DC2626; margin-top:4px;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label style="display:block; font-size:11px; font-weight:600; letter-spacing:0.05em; text-transform:uppercase; color:#6B9270; margin-bottom:6px;">
                Confirmation
            </label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="guest-input"
                   required autocomplete="new-password" placeholder="••••••••">
            @error('password_confirmation') <div style="font-size:11px; color:#DC2626; margin-top:4px;">{{ $message }}</div> @enderror
        </div>

        <button type="submit"
                style="width:100%; padding:11px 16px; background:#16A34A; color:#fff; border:none; cursor:pointer; font-family:'DM Sans',sans-serif; font-size:13px; font-weight:500; border-radius:6px; margin-top:4px; transition:background 0.18s; display:flex; align-items:center; justify-content:center; gap:8px;"
                onmouseover="this.style.background='#15803D'" onmouseout="this.style.background='#16A34A'">
            Réinitialiser le mot de passe
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
                <polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
        </button>
    </form>

</x-guest-layout>
