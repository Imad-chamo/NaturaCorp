<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="crm-page-title">Tableau de bord</div>
            <div class="crm-page-sub">Vue d'ensemble NaturaCorp — {{ \Carbon\Carbon::now()->translatedFormat('d MMMM Y') }}</div>
        </div>
    </x-slot>

    <x-dashboard-global-stats :stats="$stats" />
</x-app-layout>
