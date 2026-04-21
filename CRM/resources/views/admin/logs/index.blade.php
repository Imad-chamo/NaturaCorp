<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="crm-page-title">Journal des activités</div>
            <div class="crm-page-sub">Historique des actions utilisateurs</div>
        </div>
    </x-slot>

    <!-- Filtres -->
    <form method="GET" style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:16px;" class="crm-toolbar">
        <select name="user_id" class="crm-input" style="width:auto; padding:7px 10px;">
            <option value="">Tous les utilisateurs</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>

        <select name="action" class="crm-input" style="width:auto; padding:7px 10px;">
            <option value="">Toutes les actions</option>
            @foreach ($actions as $action)
                <option value="{{ $action }}" @selected(request('action') == $action)>{{ ucfirst($action) }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn-primary">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            Filtrer
        </button>

        @if(request('user_id') || request('action'))
            <a href="{{ route('admin.logs') }}" class="btn-secondary" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    <!-- Table -->
    <div class="crm-table-wrap">
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Utilisateur</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td class="td-mono" style="font-size:12px; white-space:nowrap;">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span style="font-weight:500; color:var(--c-text);">{{ $log->user->name ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="badge badge-blue">{{ $log->action }}</span>
                        </td>
                        <td style="font-size:13px; color:var(--c-muted); max-width:320px;">{{ $log->description }}</td>
                        <td class="td-mono" style="font-size:11px; color:var(--c-faint);">{{ $log->ip }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:40px; color:var(--c-faint); font-family:'DM Mono',monospace; font-size:12px;">
                            Aucune activité enregistrée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
        <div style="margin-top:16px;">
            {{ $logs->withQueryString()->links() }}
        </div>
    @endif
</x-app-layout>
