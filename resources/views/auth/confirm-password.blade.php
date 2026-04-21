<x-guest-layout>

    <h2 style="font-family:'Fraunces',Georgia,serif; font-size:18px; font-weight:400; color:#1A2B1E; margin-bottom:8px; text-align:center; letter-spacing:-0.01em;">
        Zone sécurisée
    </h2>
    <p style="font-size:12px; color:#6B9270; text-align:center; margin-bottom:24px; line-height:1.6;">
        Confirmez votre mot de passe pour continuer.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" style="display:flex; flex-direction:column; gap:16px;">
        @csrf

        <div>
            <label style="display:block; font-size:11px; font-weight:600; letter-spacing:0.05em; text-transform:uppercase; color:#6B9270; margin-bottom:6px;">
                Mot de passe
            </label>
            <input id="password" name="password" type="password" class="guest-input"
                   required autocomplete="current-password" placeholder="••••••••">
            @error('password') <div style="font-size:11px; color:#DC2626; margin-top:4px;">{{ $message }}</div> @enderror
        </div>

        <button type="submit"
                style="width:100%; padding:11px 16px; background:#16A34A; color:#fff; border:none; cursor:pointer; font-family:'DM Sans',sans-serif; font-size:13px; font-weight:500; border-radius:6px; transition:background 0.18s; display:flex; align-items:center; justify-content:center; gap:8px;"
                onmouseover="this.style.background='#15803D'" onmouseout="this.style.background='#16A34A'">
            Confirmer
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
        </button>
    </form>

</x-guest-layout>
