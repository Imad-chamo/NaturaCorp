<x-guest-layout>

    <h2 style="font-family:'Fraunces',Georgia,serif; font-size:18px; font-weight:400; color:#1A2B1E; margin-bottom:10px; text-align:center; letter-spacing:-0.01em;">
        Mot de passe oublié
    </h2>
    <p style="font-size:12px; color:#6B9270; text-align:center; margin-bottom:24px; line-height:1.6;">
        Indiquez votre adresse email et nous vous enverrons un lien de réinitialisation.
    </p>

    <x-auth-session-status :status="session('status')"
        style="background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; padding:10px 14px; border-radius:6px; font-size:12px; margin-bottom:16px;" />

    <form method="POST" action="{{ route('password.email') }}" style="display:flex; flex-direction:column; gap:16px;">
        @csrf

        <div>
            <label style="display:block; font-size:11px; font-weight:600; letter-spacing:0.05em; text-transform:uppercase; color:#6B9270; margin-bottom:6px;">
                Adresse email
            </label>
            <input id="email" name="email" type="email" class="guest-input"
                   value="{{ old('email') }}" required autofocus placeholder="vous@naturacorp.fr">
            @error('email')
                <div style="font-size:11px; color:#DC2626; margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit"
                style="width:100%; padding:11px 16px; background:#16A34A; color:#fff; border:none; cursor:pointer; font-family:'DM Sans',sans-serif; font-size:13px; font-weight:500; border-radius:6px; transition:background 0.18s; display:flex; align-items:center; justify-content:center; gap:8px;"
                onmouseover="this.style.background='#15803D'" onmouseout="this.style.background='#16A34A'">
            Envoyer le lien
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
        </button>

        <div style="text-align:center;">
            <a href="{{ route('login') }}"
               style="font-size:12px; color:#A8C4AB; text-decoration:none; transition:color 0.15s;"
               onmouseover="this.style.color='#16A34A'" onmouseout="this.style.color='#A8C4AB'">
                ← Retour à la connexion
            </a>
        </div>
    </form>

</x-guest-layout>
