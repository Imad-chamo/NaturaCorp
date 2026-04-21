<x-app-layout>
    <x-slot name="header">
        <div>
            <div class="crm-page-title">Mon profil</div>
            <div class="crm-page-sub">Gérer vos informations personnelles et sécurité</div>
        </div>
    </x-slot>

    <div style="max-width:700px; display:flex; flex-direction:column; gap:16px;">

        <div class="crm-panel">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="crm-panel">
            @include('profile.partials.update-password-form')
        </div>

        <div class="crm-panel">
            @include('profile.partials.delete-user-form')
        </div>

    </div>
</x-app-layout>
