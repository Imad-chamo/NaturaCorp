<x-guest-layout>

    <div style="text-align:center; margin-bottom:24px;">
        <div style="width:52px; height:52px; background:#DCFCE7; border-radius:12px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:14px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2" stroke-linecap="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
        </div>
        <div style="font-family:'Fraunces',Georgia,serif; font-size:18px; font-weight:400; color:#1A2B1E; margin-bottom:8px;">Vérifiez votre email</div>
        <div style="font-size:13px; color:#6B9270; line-height:1.6;">
            Un lien de vérification vous a été envoyé. Cliquez dessus pour activer votre compte.
        </div>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div style="background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; padding:10px 14px; border-radius:6px; font-size:12px; margin-bottom:16px; text-align:center;">
            Un nouveau lien a été envoyé à votre adresse email.
        </div>
    @endif

    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    style="display:inline-flex; align-items:center; gap:6px; padding:9px 16px; background:#16A34A; color:#fff; border:none; cursor:pointer; font-family:'DM Sans',sans-serif; font-size:13px; font-weight:500; border-radius:6px; transition:background 0.18s;"
                    onmouseover="this.style.background='#15803D'" onmouseout="this.style.background='#16A34A'">
                Renvoyer le lien
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    style="font-size:12px; color:#A8C4AB; background:transparent; border:none; cursor:pointer; font-family:'DM Sans',sans-serif; transition:color 0.15s;"
                    onmouseover="this.style.color='#DC2626'" onmouseout="this.style.color='#A8C4AB'">
                Se déconnecter
            </button>
        </form>
    </div>

</x-guest-layout>
