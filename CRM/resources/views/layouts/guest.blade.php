<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'NaturaCorp') }} — Connexion</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,400;9..144,500&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    body {
      background-color: #F4F7F4;
      font-family: 'DM Sans', system-ui, sans-serif;
      font-size: 14px;
      -webkit-font-smoothing: antialiased;
    }
    .guest-input {
      display: block; width: 100%;
      background: #FFFFFF; color: #1A2B1E;
      border: 1px solid #E2EAE3;
      font-family: 'DM Sans', sans-serif; font-size: 13px;
      padding: 10px 14px; outline: none;
      border-radius: 6px;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .guest-input:focus { border-color: #16A34A; box-shadow: 0 0 0 3px rgba(22,163,74,0.12); }
    .guest-input::placeholder { color: #A8C4AB; }
    </style>
</head>
<body>
<div style="min-height:100vh; display:flex; align-items:center; justify-content:center; padding:24px; background:#F4F7F4;">
    <div style="width:100%; max-width:400px;">

        <!-- Logo -->
        <div style="text-align:center; margin-bottom:32px;">
            <div style="display:inline-flex; align-items:center; gap:12px;">
                <svg width="36" height="36" viewBox="0 0 32 32" fill="none">
                    <path d="M16 4C16 4 8 10 8 18C8 22.4 11.6 26 16 26C20.4 26 24 22.4 24 18C24 10 16 4 16 4Z" fill="#DCFCE7"/>
                    <path d="M16 8C16 8 10 13 10 19C10 22.3 12.7 25 16 25C19.3 25 22 22.3 22 19C22 13 16 8 16 8Z" fill="#16A34A"/>
                    <path d="M16 28V18" stroke="#92692A" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M13 22C13 22 14.5 20 16 20" stroke="#92692A" stroke-width="1.2" stroke-linecap="round"/>
                </svg>
                <div>
                    <div style="font-family:'Fraunces',Georgia,serif; font-size:20px; font-weight:400; color:#1A2B1E; letter-spacing:-0.01em;">NaturaCorp</div>
                    <div style="font-family:'DM Mono',monospace; font-size:9px; color:#A8C4AB; letter-spacing:0.15em; text-transform:uppercase;">CRM · Espace professionnel</div>
                </div>
            </div>
        </div>

        <!-- Card -->
        <div style="background:#FFFFFF; border:1px solid #E2EAE3; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.06);">
            <div style="height:3px; background:linear-gradient(90deg,#16A34A,#22C55E);"></div>
            <div style="padding:32px;">
                {{ $slot }}
            </div>
        </div>

        <div style="text-align:center; margin-top:20px; font-family:'DM Mono',monospace; font-size:10px; color:#A8C4AB;">
            © {{ date('Y') }} NaturaCorp — Accès réservé aux collaborateurs
        </div>
    </div>
</div>
</body>
</html>
