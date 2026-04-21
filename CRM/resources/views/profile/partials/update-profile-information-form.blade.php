<section>
    <div style="font-family:'Fraunces',Georgia,serif; font-size:15px; font-weight:500; color:var(--c-text); margin-bottom:4px;">Informations du profil</div>
    <div style="font-size:12px; color:var(--c-muted); margin-bottom:20px;">Modifiez les informations de votre compte.</div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

    <form method="post" action="{{ route('profile.update') }}" style="display:flex; flex-direction:column; gap:14px;">
        @csrf
        @method('patch')

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div>
                <label class="form-label">Nom *</label>
                <input id="name" name="name" type="text" class="crm-input" value="{{ old('name', $user->name) }}" required>
                @error('name') <div style="font-size:11px; color:var(--c-red); margin-top:4px;">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="form-label">Email *</label>
                <input id="email" name="email" type="email" class="crm-input" value="{{ old('email', $user->email) }}" required>
                @error('email') <div style="font-size:11px; color:var(--c-red); margin-top:4px;">{{ $message }}</div> @enderror
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div style="background:var(--c-amber-l); border:1px solid rgba(217,119,6,0.2); border-radius:6px; padding:10px 14px; font-size:13px;">
                <span style="color:#92400E;">Votre adresse email n'est pas vérifiée.</span>
                <button form="send-verification"
                        style="color:var(--c-green-d); background:none; border:none; cursor:pointer; font-size:13px; font-weight:500; text-decoration:underline; margin-left:6px;">
                    Renvoyer le lien
                </button>
                @if (session('status') === 'verification-link-sent')
                    <div style="color:var(--c-green-d); font-size:12px; margin-top:4px;">Un nouveau lien a été envoyé.</div>
                @endif
            </div>
        @endif

        <div style="display:flex; align-items:center; gap:12px; padding-top:4px;">
            <button type="submit" class="btn-primary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                Enregistrer
            </button>
            @if (session('status') === 'profile-updated')
                <span style="font-size:12px; color:var(--c-green-d);">Profil mis à jour.</span>
            @endif
        </div>
    </form>
</section>
