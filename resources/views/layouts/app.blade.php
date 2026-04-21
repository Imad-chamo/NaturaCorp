<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'NaturaCorp') }} — CRM</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,400;9..144,500;9..144,600&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    /* ═══════════════════════════════════════
       NaturaCorp CRM — Light Professional v2
    ═══════════════════════════════════════ */
    :root {
      --c-base:     #F2F6F2;
      --c-surface:  #FFFFFF;
      --c-raised:   #F7FAF7;
      --c-hover:    #EEF4EE;
      --c-border:   #E0E9E1;
      --c-bolder:   #C4D6C6;
      --c-green:    #16A34A;
      --c-green-l:  #DCFCE7;
      --c-green-d:  #15803D;
      --c-amber:    #D97706;
      --c-amber-l:  #FEF3C7;
      --c-red:      #DC2626;
      --c-red-l:    #FEE2E2;
      --c-blue:     #2563EB;
      --c-blue-l:   #DBEAFE;
      --c-text:     #18271C;
      --c-muted:    #5E8264;
      --c-faint:    #9DBDA0;
      --c-gold:     #92692A;

      --shadow-xs:  0 1px 2px rgba(24,39,28,0.05);
      --shadow-sm:  0 1px 4px rgba(24,39,28,0.06), 0 2px 8px rgba(24,39,28,0.04);
      --shadow-md:  0 2px 8px rgba(24,39,28,0.07), 0 6px 20px rgba(24,39,28,0.05);
      --shadow-lg:  0 8px 32px rgba(24,39,28,0.10), 0 2px 8px rgba(24,39,28,0.06);
      --shadow-xl:  0 20px 60px rgba(24,39,28,0.14), 0 4px 16px rgba(24,39,28,0.08);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; }
    html { scroll-behavior: smooth; }

    body {
      background-color: var(--c-base);
      background-image:
        radial-gradient(circle at 1px 1px, rgba(196,214,198,0.45) 1px, transparent 0);
      background-size: 22px 22px;
      color: var(--c-text);
      font-family: 'DM Sans', system-ui, sans-serif;
      font-size: 14px;
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
    }

    /* Layout shell */
    .crm-shell   { display: flex; min-height: 100vh; }
    .crm-main    { margin-left: 240px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
    .crm-content { flex: 1; padding: 28px 28px 24px; }

    /* Page header */
    .crm-page-header {
      display: flex; align-items: flex-start; justify-content: space-between;
      margin-bottom: 26px; padding-bottom: 18px;
      border-bottom: 1px solid var(--c-border);
    }
    .crm-page-title {
      font-family: 'Fraunces', Georgia, serif;
      font-size: 22px; font-weight: 500;
      color: var(--c-text); letter-spacing: -0.02em;
    }
    .crm-page-sub { font-size: 12px; color: var(--c-muted); margin-top: 3px; }

    /* ── Buttons ── */
    .btn-primary {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--c-green); color: #fff;
      font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500;
      letter-spacing: 0.01em; padding: 8px 18px;
      border: none; cursor: pointer; border-radius: 7px;
      box-shadow: 0 1px 3px rgba(22,163,74,0.3), 0 3px 10px rgba(22,163,74,0.18);
      transition: background 0.16s, box-shadow 0.16s, transform 0.14s;
    }
    .btn-primary:hover {
      background: var(--c-green-d);
      box-shadow: 0 2px 6px rgba(22,163,74,0.35), 0 6px 20px rgba(22,163,74,0.2);
      transform: translateY(-1px);
    }
    .btn-primary:active { transform: translateY(0); box-shadow: 0 1px 3px rgba(22,163,74,0.25); }

    .btn-secondary {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--c-surface); color: var(--c-muted);
      font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500;
      padding: 8px 16px; cursor: pointer;
      border: 1px solid var(--c-border); border-radius: 7px;
      box-shadow: var(--shadow-xs);
      transition: border-color 0.16s, color 0.16s, background 0.16s, box-shadow 0.16s;
    }
    .btn-secondary:hover {
      border-color: var(--c-bolder); color: var(--c-text);
      background: var(--c-raised); box-shadow: var(--shadow-sm);
    }

    /* ── Inputs ── */
    .crm-input {
      display: block; width: 100%;
      background: var(--c-surface); color: var(--c-text);
      border: 1px solid var(--c-border);
      font-family: 'DM Sans', sans-serif; font-size: 13px;
      padding: 8px 12px; outline: none;
      transition: border-color 0.18s, box-shadow 0.18s;
      border-radius: 7px;
    }
    .crm-input:hover:not(:focus) { border-color: var(--c-bolder); }
    .crm-input:focus  { border-color: var(--c-green); box-shadow: 0 0 0 3px rgba(22,163,74,0.14); }
    .crm-input::placeholder { color: var(--c-faint); }
    select.crm-input  { cursor: pointer; }
    textarea.crm-input { resize: vertical; min-height: 72px; }

    .crm-search {
      background: var(--c-surface); color: var(--c-text);
      border: 1px solid var(--c-border);
      font-family: 'DM Sans', sans-serif; font-size: 13px;
      padding: 8px 12px 8px 34px; outline: none;
      transition: border-color 0.18s, box-shadow 0.18s; border-radius: 7px;
    }
    .crm-search:hover:not(:focus) { border-color: var(--c-bolder); }
    .crm-search:focus { border-color: var(--c-green); box-shadow: 0 0 0 3px rgba(22,163,74,0.14); }
    .crm-search::placeholder { color: var(--c-faint); }

    /* ── Badges ── */
    .badge {
      display: inline-flex; align-items: center; gap: 4px;
      padding: 2px 9px 3px;
      font-size: 11px; font-weight: 600;
      letter-spacing: 0.02em; border-radius: 20px;
    }
    .badge-green { background: var(--c-green-l);  color: #166534; }
    .badge-amber { background: var(--c-amber-l);  color: #78350F; }
    .badge-red   { background: var(--c-red-l);    color: #7F1D1D; }
    .badge-gray  { background: #F1F3F2;            color: #4B5563; }
    .badge-blue  { background: var(--c-blue-l);   color: #1E40AF; }

    /* ── KPI cards ── */
    .kpi-card {
      background: var(--c-surface);
      border: 1px solid var(--c-border);
      border-radius: 12px; padding: 20px 20px 18px;
      position: relative; overflow: hidden;
      box-shadow: var(--shadow-sm);
      transition: box-shadow 0.2s, transform 0.2s;
    }
    .kpi-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
    .kpi-card::before {
      content: ''; position: absolute;
      top: 16px; bottom: 16px; left: 0; width: 3px; border-radius: 0 3px 3px 0;
    }
    .kpi-card::after {
      content: ''; position: absolute;
      top: -40px; right: -40px; width: 100px; height: 100px;
      border-radius: 50%; opacity: 0.05;
    }
    .kpi-green::before  { background: var(--c-green); }
    .kpi-amber::before  { background: var(--c-amber); }
    .kpi-red::before    { background: var(--c-red);   }
    .kpi-blue::before   { background: var(--c-blue);  }
    .kpi-muted::before  { background: var(--c-bolder);}
    .kpi-green::after   { background: var(--c-green); }
    .kpi-amber::after   { background: var(--c-amber); }
    .kpi-red::after     { background: var(--c-red);   }
    .kpi-blue::after    { background: var(--c-blue);  }

    .kpi-label {
      font-size: 11px; color: var(--c-muted);
      letter-spacing: 0.06em; font-weight: 600;
      text-transform: uppercase; margin-bottom: 8px;
    }
    .kpi-value {
      font-family: 'Fraunces', Georgia, serif;
      font-size: 32px; font-weight: 400;
      line-height: 1; letter-spacing: -0.02em;
    }
    .kpi-green .kpi-value { color: var(--c-green-d); }
    .kpi-amber .kpi-value { color: var(--c-amber); }
    .kpi-red .kpi-value   { color: var(--c-red); }
    .kpi-blue .kpi-value  { color: var(--c-blue); }
    .kpi-muted .kpi-value { color: var(--c-text); }

    /* ── Table ── */
    .crm-table-wrap {
      background: var(--c-surface);
      border: 1px solid var(--c-border);
      border-radius: 12px; overflow: hidden;
      box-shadow: var(--shadow-sm);
    }
    .crm-table { width: 100%; border-collapse: collapse; }
    .crm-table thead tr {
      background: var(--c-raised);
      border-bottom: 1px solid var(--c-border);
    }
    .crm-table thead th {
      padding: 11px 14px; text-align: left;
      font-size: 10px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;
      color: var(--c-faint);
    }
    .crm-table thead th:first-child { padding-left: 20px; }
    .crm-table thead th:last-child  { padding-right: 20px; }
    .crm-table tbody tr { border-bottom: 1px solid var(--c-border); transition: background 0.1s; }
    .crm-table tbody tr:last-child  { border-bottom: none; }
    .crm-table tbody tr:hover { background: var(--c-hover); }
    .crm-table tbody td { padding: 13px 14px; color: var(--c-text); font-size: 13px; }
    .crm-table tbody td:first-child { padding-left: 20px; }
    .crm-table tbody td:last-child  { padding-right: 20px; }
    .crm-table .td-mono { font-family: 'DM Mono', monospace; font-size: 12px; color: var(--c-muted); }
    .crm-table .td-right { text-align: right; }

    /* ── Action links ── */
    .link-edit {
      display: inline-flex; align-items: center; gap: 4px;
      color: var(--c-green-d); font-size: 12px; font-weight: 600;
      cursor: pointer; text-decoration: none;
      padding: 3px 8px; border-radius: 4px;
      transition: background 0.12s, color 0.12s;
    }
    .link-edit:hover { background: var(--c-green-l); color: var(--c-green-d); }
    .link-del {
      display: inline-flex; align-items: center; gap: 4px;
      color: var(--c-faint); font-size: 12px; font-weight: 600;
      cursor: pointer; text-decoration: none;
      padding: 3px 8px; border-radius: 4px;
      transition: background 0.12s, color 0.12s;
    }
    .link-del:hover { background: var(--c-red-l); color: var(--c-red); }

    /* ── Toolbar ── */
    .crm-toolbar {
      display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
      padding: 10px 14px; background: var(--c-surface);
      border: 1px solid var(--c-border); border-radius: 10px; margin-bottom: 16px;
      box-shadow: var(--shadow-xs);
    }

    /* ── Panel ── */
    .crm-panel {
      background: var(--c-surface);
      border: 1px solid var(--c-border);
      border-radius: 12px; padding: 22px;
      box-shadow: var(--shadow-sm);
    }

    /* ── Section label ── */
    .section-label {
      font-size: 10px; font-weight: 700; color: var(--c-faint);
      letter-spacing: 0.10em; text-transform: uppercase;
      margin-bottom: 16px;
    }

    /* ── Flash messages ── */
    .flash-success {
      display: flex; align-items: center; gap: 10px;
      background: var(--c-green-l); border: 1px solid rgba(22,163,74,0.25);
      color: var(--c-green-d); padding: 11px 16px; border-radius: 8px;
      font-size: 13px; margin-bottom: 20px; font-weight: 500;
      box-shadow: 0 1px 4px rgba(22,163,74,0.12);
    }
    .flash-error {
      display: flex; align-items: center; gap: 10px;
      background: var(--c-red-l); border: 1px solid rgba(220,38,38,0.25);
      color: var(--c-red); padding: 11px 16px; border-radius: 8px;
      font-size: 13px; margin-bottom: 20px;
    }

    /* ── Modal ── */
    .crm-modal-overlay {
      position: fixed; inset: 0; z-index: 200;
      background: rgba(0,0,0,0.35); backdrop-filter: blur(4px);
      display: flex; align-items: center; justify-content: center; padding: 20px;
    }
    .crm-modal {
      background: var(--c-surface);
      border: 1px solid var(--c-border);
      border-radius: 14px; box-shadow: var(--shadow-xl);
      width: 100%; max-width: 520px; max-height: 90vh; overflow-y: auto;
    }
    .crm-modal-header {
      display: flex; align-items: center; justify-content: space-between;
      padding: 20px 24px; border-bottom: 1px solid var(--c-border);
      position: sticky; top: 0; background: var(--c-surface); z-index: 1;
      border-radius: 14px 14px 0 0;
    }
    .crm-modal-title { font-family: 'Fraunces', serif; font-size: 18px; font-weight: 500; color: var(--c-text); }
    .crm-modal-close {
      width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
      color: var(--c-faint); cursor: pointer; border-radius: 7px; border: 1px solid transparent;
      background: transparent; font-size: 20px; line-height: 1; transition: all 0.15s;
    }
    .crm-modal-close:hover { border-color: var(--c-border); background: var(--c-raised); color: var(--c-text); }
    .crm-modal-body { padding: 24px; }

    /* ── Form label ── */
    .form-label {
      display: block; font-size: 11px; font-weight: 700;
      color: var(--c-muted); letter-spacing: 0.04em;
      text-transform: uppercase; margin-bottom: 6px;
    }

    /* ── Pagination ── */
    nav[aria-label="Pagination"] { display: flex; justify-content: center; margin-top: 18px; gap: 3px; }
    nav[aria-label="Pagination"] span, nav[aria-label="Pagination"] a {
      display: inline-flex; align-items: center; justify-content: center;
      min-width: 34px; height: 34px; padding: 0 10px;
      font-size: 13px; color: var(--c-muted);
      border: 1px solid var(--c-border); border-radius: 7px;
      background: var(--c-surface); text-decoration: none;
      transition: all 0.15s; box-shadow: var(--shadow-xs);
    }
    nav[aria-label="Pagination"] a:hover { background: var(--c-hover); color: var(--c-text); border-color: var(--c-bolder); }
    nav[aria-label="Pagination"] span[aria-current="page"] {
      background: var(--c-green); color: #fff; border-color: var(--c-green);
      font-weight: 600; box-shadow: 0 2px 8px rgba(22,163,74,0.25);
    }
    nav[aria-label="Pagination"] span[aria-disabled="true"] { opacity: 0.35; cursor: not-allowed; }

    /* ── ApexCharts ── */
    .apexcharts-canvas { background: transparent !important; }
    .apexcharts-text, .apexcharts-legend-text { fill: #5E8264 !important; color: #5E8264 !important; }
    .apexcharts-gridline { stroke: #E0E9E1 !important; }

    /* ── Scrollbar ── */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--c-bolder); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--c-muted); }

    /* ── Utility animations ── */
    @keyframes fadeSlideIn {
      from { opacity: 0; transform: translateY(6px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .crm-content > * { animation: fadeSlideIn 0.22s ease both; }
    .crm-content > *:nth-child(2) { animation-delay: 0.04s; }
    .crm-content > *:nth-child(3) { animation-delay: 0.08s; }
    .crm-content > *:nth-child(4) { animation-delay: 0.12s; }
    </style>

    @stack('styles')
</head>
<body>
<div class="crm-shell">
    @include('layouts.sidebar')

    <div class="crm-main">
        @include('layouts.navbar')

        <div class="crm-content">
            @if(isset($header))
                <div class="crm-page-header">
                    {!! $header !!}
                </div>
            @endif

            {{ $slot }}
        </div>

        <footer style="border-top:1px solid var(--c-border); padding:12px 24px; display:flex; align-items:center; justify-content:space-between; background:var(--c-surface);">
            <span style="font-size:12px; color:var(--c-faint);">© {{ date('Y') }} NaturaCorp — Tous droits réservés.</span>
            <a href="{{ route('confidentialite') }}" style="font-size:12px; color:var(--c-muted); text-decoration:none;"
               onmouseover="this.style.color='var(--c-text)'" onmouseout="this.style.color='var(--c-muted)'">
                Politique de confidentialité (RGPD)
            </a>
        </footer>
    </div>
</div>

@stack('scripts')
</body>
</html>
