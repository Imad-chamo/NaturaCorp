<section>
    <div style="font-family:'Fraunces',Georgia,serif; font-size:15px; font-weight:500; color:var(--c-text); margin-bottom:4px;">Mot de passe</div>
    <div style="font-size:12px; color:var(--c-muted); margin-bottom:20px;">Utilisez un mot de passe long et aléatoire pour rester sécurisé.</div>

    <form method="post" action="{{ route('password.update') }}" style="display:flex; flex-direction:column; gap:14px;">
        @csrf
        @method('put')

        <div>
            <label class="form-label">Mot de passe actuel</label>
            <input id="current_password" name="current_password" type="password" class="crm-input" autocomplete="current-password" placeholder="••••••••">
            @error('current_password', 'updatePassword') <div style="font-size:11px; color:var(--c-red); margin-top:4px;">{{ $message }}</div> @enderror
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div>
                <label class="form-label">Nouveau mot de passe</label>
                <input id="password" name="password" type="password" class="crm-input" autocomplete="new-password" placeholder="••••••••">
                @error('password', 'updatePassword') <div style="font-size:11px; color:var(--c-red); margin-top:4px;">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="form-label">Confirmation</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="crm-input" autocomplete="new-password" placeholder="••••••••">
                @error('password_confirmation', 'updatePassword') <div style="font-size:11px; color:var(--c-red); margin-top:4px;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="display:flex; align-items:center; gap:12px; padding-top:4px;">
            <button type="submit" class="btn-primary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                Enregistrer
            </button>
            @if (session('status') === 'password-updated')
                <span style="font-size:12px; color:var(--c-green-d);">Mot de passe mis à jour.</span>
            @endif
        </div>
    </form>
</section>
