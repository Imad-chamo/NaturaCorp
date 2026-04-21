<section>
    <div style="font-family:'Fraunces',Georgia,serif; font-size:15px; font-weight:500; color:var(--c-red); margin-bottom:4px;">Supprimer le compte</div>
    <div style="font-size:12px; color:var(--c-muted); margin-bottom:20px;">Cette action est permanente et irréversible.</div>

    <button onclick="document.getElementById('confirm-delete-modal').showModal()"
            style="display:inline-flex; align-items:center; gap:6px; background:transparent; border:1px solid rgba(220,38,38,0.35); color:var(--c-red); font-family:'DM Sans',sans-serif; font-size:13px; font-weight:500; padding:8px 16px; border-radius:6px; cursor:pointer; transition:all 0.15s;"
            onmouseover="this.style.background='#FEE2E2'; this.style.borderColor='rgba(220,38,38,0.6)'"
            onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(220,38,38,0.35)'">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6m4-6v6M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
        Supprimer mon compte
    </button>

    <dialog id="confirm-delete-modal"
            style="border:1px solid #E2EAE3; border-radius:12px; box-shadow:0 20px 60px rgba(0,0,0,0.12); padding:0; width:100%; max-width:420px; background:#fff;">
        <form method="post" action="{{ route('profile.destroy') }}" style="padding:28px; display:flex; flex-direction:column; gap:16px;">
            @csrf
            @method('delete')

            <div>
                <div style="font-family:'Fraunces',Georgia,serif; font-size:17px; font-weight:500; color:var(--c-text); margin-bottom:6px;">Confirmation requise</div>
                <div style="font-size:13px; color:var(--c-muted); line-height:1.5;">Vous êtes sur le point de supprimer définitivement votre compte. Entrez votre mot de passe pour confirmer.</div>
            </div>

            <div>
                <label class="form-label">Mot de passe</label>
                <input id="password" name="password" type="password" class="crm-input" placeholder="••••••••">
                @error('password', 'userDeletion') <div style="font-size:11px; color:var(--c-red); margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px; padding-top:4px;">
                <button type="button" onclick="document.getElementById('confirm-delete-modal').close()"
                        class="btn-secondary">Annuler</button>
                <button type="submit"
                        style="display:inline-flex; align-items:center; gap:6px; background:var(--c-red); color:#fff; font-family:'DM Sans',sans-serif; font-size:13px; font-weight:500; padding:8px 16px; border:none; border-radius:6px; cursor:pointer; transition:background 0.18s;"
                        onmouseover="this.style.background='#B91C1C'" onmouseout="this.style.background='var(--c-red)'">
                    Supprimer définitivement
                </button>
            </div>
        </form>
    </dialog>
</section>
