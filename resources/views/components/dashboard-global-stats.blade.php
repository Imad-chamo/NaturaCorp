@props(['stats', 'commandes_recentes' => collect()])

<div style="display:flex; flex-direction:column; gap:24px;">

  {{-- ── Row 1 : KPI Cards ── --}}
  <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px;">

    {{-- CA Total --}}
    <div class="kpi-card kpi-green" style="position:relative; overflow:hidden;">
      <div style="position:absolute; top:14px; right:14px; width:36px; height:36px; background:var(--c-green-l); border-radius:9px; display:flex; align-items:center; justify-content:center;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--c-green)" stroke-width="2" stroke-linecap="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
      </div>
      <div class="kpi-label">CA Total</div>
      <div class="kpi-value" style="font-size:26px;">{{ number_format($stats['ca_total'], 0, ',', ' ') }} €</div>
      <div style="font-size:11px; color:var(--c-muted); margin-top:6px;">Toutes commandes confondues</div>
    </div>

    {{-- CA ce mois --}}
    <div class="kpi-card kpi-blue" style="position:relative; overflow:hidden;">
      <div style="position:absolute; top:14px; right:14px; width:36px; height:36px; background:var(--c-blue-l); border-radius:9px; display:flex; align-items:center; justify-content:center;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--c-blue)" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      </div>
      <div class="kpi-label">CA ce mois</div>
      <div class="kpi-value" style="font-size:26px;">{{ number_format($stats['ca_mois'], 0, ',', ' ') }} €</div>
      <div style="font-size:11px; color:var(--c-muted); margin-top:6px;">{{ $stats['commandes_mois'] }} commande(s) ce mois</div>
    </div>

    {{-- Pharmacies --}}
    <div class="kpi-card kpi-muted" style="position:relative; overflow:hidden;">
      <div style="position:absolute; top:14px; right:14px; width:36px; height:36px; background:var(--c-hover); border-radius:9px; display:flex; align-items:center; justify-content:center;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--c-muted)" stroke-width="2" stroke-linecap="round"><path d="M12 13a3 3 0 100-6 3 3 0 000 6z"/><path d="M17.8 13.938h-.011a7 7 0 10-11.464.144h-.016l.14.171.3.371L12 21l5.13-6.248.54-.659.13-.155z"/></svg>
      </div>
      <div class="kpi-label">Pharmacies</div>
      <div class="kpi-value">{{ $stats['pharmacies_total'] }}</div>
      <div style="display:flex; gap:8px; margin-top:6px; flex-wrap:wrap;">
        @php $byStatut = $stats['pharmacies_par_statut']->toArray(); @endphp
        <span style="font-size:10px; color:#16A34A; font-weight:600;">● {{ $byStatut['client_actif'] ?? 0 }} actifs</span>
        <span style="font-size:10px; color:#D97706; font-weight:600;">● {{ $byStatut['prospect'] ?? 0 }} prospects</span>
      </div>
    </div>

    {{-- Commandes retard --}}
    <div class="kpi-card {{ $stats['commandes_retard'] > 0 ? 'kpi-red' : 'kpi-green' }}" style="position:relative; overflow:hidden;">
      <div style="position:absolute; top:14px; right:14px; width:36px; height:36px; background:{{ $stats['commandes_retard'] > 0 ? 'var(--c-red-l)' : 'var(--c-green-l)' }}; border-radius:9px; display:flex; align-items:center; justify-content:center;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="{{ $stats['commandes_retard'] > 0 ? 'var(--c-red)' : 'var(--c-green)' }}" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      <div class="kpi-label">Commandes en retard</div>
      <div class="kpi-value">{{ $stats['commandes_retard'] }}</div>
      <div style="font-size:11px; color:var(--c-muted); margin-top:6px;">
        @if($stats['commandes_retard'] > 0)
          <a href="{{ route('commandes.index') }}" style="color:var(--c-red); text-decoration:none; font-weight:600;">Voir →</a>
        @else
          Tout est à jour ✓
        @endif
      </div>
    </div>

  </div>

  {{-- ── Row 2 : Charts ── --}}
  <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px;">

    {{-- Donut pharmacies --}}
    <div class="crm-panel">
      <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
        <div class="section-label" style="margin-bottom:0;">Pharmacies par statut</div>
        <a href="{{ route('pharmacies.index') }}" style="font-size:11px; color:var(--c-green-d); text-decoration:none; font-weight:600;">Voir →</a>
      </div>
      <div x-data="{
        chart: null,
        init() {
          if (!this.chart) {
            this.chart = new ApexCharts(this.$refs.chart, {
              chart: { type: 'donut', height: 200, background: 'transparent', fontFamily: 'DM Sans, sans-serif', animations: { speed: 400 } },
              series: {{ json_encode(array_values($stats['pharmacies_par_statut']->toArray())) }},
              labels: {{ json_encode(array_values(array_map(fn($k) => match($k) { 'client_actif' => 'Actif', 'client_inactif' => 'Inactif', 'prospect' => 'Prospect', default => ucfirst($k) }, array_keys($stats['pharmacies_par_statut']->toArray())))) }},
              colors: ['#16A34A','#D97706','#C4D6C6'],
              plotOptions: { pie: { donut: { size: '65%', labels: { show: true,
                total: { show: true, label: 'Total', color: '#9DBDA0', fontSize: '10px', fontWeight: '700', fontFamily: 'DM Sans' },
                value: { color: '#18271C', fontSize: '24px', fontFamily: 'Fraunces, serif', fontWeight: '400', offsetY: 4 }
              } } } },
              legend: { position: 'bottom', labels: { colors: '#5E8264' }, fontSize: '11px', fontFamily: 'DM Sans', markers: { radius: 3, width: 7, height: 7 } },
              stroke: { width: 2, colors: ['#fff'] },
              tooltip: { theme: 'light', style: { fontFamily: 'DM Sans' } },
              dataLabels: { enabled: false },
            });
            this.chart.render();
          }
        }
      }" x-init="init()">
        <div x-ref="chart"></div>
      </div>
    </div>

    {{-- Donut commandes --}}
    <div class="crm-panel">
      <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
        <div class="section-label" style="margin-bottom:0;">Commandes par statut</div>
        <a href="{{ route('commandes.index') }}" style="font-size:11px; color:var(--c-green-d); text-decoration:none; font-weight:600;">Voir →</a>
      </div>
      <div x-data="{
        chart: null,
        init() {
          if (!this.chart) {
            this.chart = new ApexCharts(this.$refs.chart, {
              chart: { type: 'donut', height: 200, background: 'transparent', fontFamily: 'DM Sans, sans-serif', animations: { speed: 400 } },
              series: {{ json_encode(array_values($stats['commandes_par_statut']->toArray())) }},
              labels: {{ json_encode(array_values(array_map(fn($k) => match($k) { 'livree' => 'Livrée', 'validee' => 'Validée', 'en_cours' => 'En cours', 'annulee' => 'Annulée', default => ucfirst($k) }, array_keys($stats['commandes_par_statut']->toArray())))) }},
              colors: ['#16A34A','#2563EB','#D97706','#DC2626'],
              plotOptions: { pie: { donut: { size: '65%', labels: { show: true,
                total: { show: true, label: 'Total', color: '#9DBDA0', fontSize: '10px', fontWeight: '700', fontFamily: 'DM Sans' },
                value: { color: '#18271C', fontSize: '24px', fontFamily: 'Fraunces, serif', fontWeight: '400', offsetY: 4 }
              } } } },
              legend: { position: 'bottom', labels: { colors: '#5E8264' }, fontSize: '11px', fontFamily: 'DM Sans', markers: { radius: 3, width: 7, height: 7 } },
              stroke: { width: 2, colors: ['#fff'] },
              tooltip: { theme: 'light', style: { fontFamily: 'DM Sans' } },
              dataLabels: { enabled: false },
            });
            this.chart.render();
          }
        }
      }" x-init="init()">
        <div x-ref="chart"></div>
      </div>
    </div>

    {{-- Top 5 pharmacies --}}
    <div class="crm-panel">
      <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;">
        <div class="section-label" style="margin-bottom:0;">Top 5 pharmacies</div>
        <span style="font-size:10px; color:var(--c-faint); font-family:'DM Mono',monospace;">par commandes</span>
      </div>
      @php $topPharm = $stats['top_pharmacies']; $maxCmd = $topPharm->max('commandes_count') ?: 1; @endphp
      @if($topPharm->isEmpty())
        <div style="text-align:center; padding:32px 0; color:var(--c-faint); font-size:12px;">Aucune donnée</div>
      @else
      <div style="display:flex; flex-direction:column; gap:10px;">
        @foreach($topPharm as $i => $p)
        <div>
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:4px;">
            <div style="display:flex; align-items:center; gap:8px; flex:1; min-width:0;">
              <span style="font-family:'DM Mono',monospace; font-size:10px; color:var(--c-faint); width:14px; flex-shrink:0;">{{ $i+1 }}</span>
              <a href="{{ route('pharmacies.show', $p) }}"
                 style="font-size:12px; font-weight:500; color:var(--c-text); text-decoration:none; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"
                 onmouseover="this.style.color='var(--c-green-d)'" onmouseout="this.style.color='var(--c-text)'">
                {{ $p->nom }}
              </a>
            </div>
            <span style="font-family:'DM Mono',monospace; font-size:11px; color:var(--c-muted); flex-shrink:0; margin-left:8px;">{{ $p->commandes_count }}</span>
          </div>
          <div style="height:4px; background:var(--c-hover); border-radius:4px; overflow:hidden;">
            <div style="height:100%; border-radius:4px; background:{{ $i === 0 ? '#16A34A' : ($i === 1 ? '#2563EB' : ($i === 2 ? '#D97706' : '#C4D6C6')) }}; transition:width 0.5s ease;"
                 style="width:{{ round(($p->commandes_count / $maxCmd) * 100) }}%"
                 :style="{}">
              <div style="height:100%; border-radius:4px; background:{{ $i === 0 ? '#16A34A' : ($i === 1 ? '#2563EB' : ($i === 2 ? '#D97706' : '#C4D6C6')) }}; width:{{ round(($p->commandes_count / $maxCmd) * 100) }}%;"></div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
      @endif
    </div>

  </div>

  {{-- ── Row 4 : Evolution chart full width ── --}}
  <div class="crm-panel">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
      <div class="section-label" style="margin-bottom:0;">Évolution mensuelle</div>
      <div style="display:flex; gap:14px;">
        <div style="display:flex; align-items:center; gap:5px; font-size:11px; color:var(--c-muted);">
          <span style="width:20px; height:2px; background:#16A34A; border-radius:2px; display:inline-block;"></span> Commandes
        </div>
        <div style="display:flex; align-items:center; gap:5px; font-size:11px; color:var(--c-muted);">
          <span style="width:20px; height:2px; background:#2563EB; border-radius:2px; display:inline-block;"></span> Pharmacies
        </div>
      </div>
    </div>
    <div x-data="{
      chart: null,
      init() {
        if (!this.chart) {
          this.chart = new ApexCharts(this.$refs.chart, {
            chart: { type: 'area', height: 200, background: 'transparent', fontFamily: 'DM Sans, sans-serif', toolbar: { show: false }, animations: { speed: 500 } },
            series: [
              { name: 'Commandes',  data: {{ json_encode(array_values($stats['evolution_commandes']->toArray())) }} },
              { name: 'Pharmacies', data: {{ json_encode(array_values($stats['evolution_pharmacies']->toArray())) }} }
            ],
            xaxis: {
              categories: {{ json_encode(array_keys($stats['evolution_commandes']->toArray())) }},
              labels: { rotate: -30, style: { colors: '#9DBDA0', fontSize: '10px', fontFamily: 'DM Mono' } },
              axisBorder: { show: false }, axisTicks: { show: false },
            },
            yaxis: { labels: { style: { colors: '#9DBDA0', fontSize: '11px', fontFamily: 'DM Mono' } }, min: 0 },
            grid: { borderColor: '#E0E9E1', strokeDashArray: 4, padding: { left: 4, right: 4 } },
            colors: ['#16A34A', '#2563EB'],
            fill: { type: 'gradient', gradient: { type: 'vertical', stops: [0, 100], opacityFrom: 0.15, opacityTo: 0.0 } },
            stroke: { curve: 'smooth', width: 2.5 },
            markers: { size: 3, strokeColors: '#fff', strokeWidth: 2 },
            tooltip: { theme: 'light', style: { fontFamily: 'DM Sans' }, shared: true, intersect: false },
            dataLabels: { enabled: false },
            legend: { show: false },
          });
          this.chart.render();
        }
      }
    }" x-init="init()">
      <div x-ref="chart"></div>
    </div>
  </div>

  {{-- ── Row 5 : Commandes récentes + mini stats ── --}}
  <div style="display:grid; grid-template-columns:1fr 280px; gap:16px; align-items:start;">

    {{-- Commandes récentes --}}
    @if($commandes_recentes->isNotEmpty())
    <div>
      <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
        <div class="section-label" style="margin-bottom:0;">Commandes récentes</div>
        <a href="{{ route('commandes.index') }}" style="font-size:11px; color:var(--c-green-d); font-weight:600; text-decoration:none;"
           onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Voir toutes →</a>
      </div>
      <div class="crm-table-wrap">
        <table class="crm-table">
          <thead>
            <tr><th>Réf.</th><th>Pharmacie</th><th>Produit</th><th class="td-right">Total</th><th>Statut</th><th>Date</th></tr>
          </thead>
          <tbody>
            @foreach($commandes_recentes as $c)
            <tr style="cursor:pointer;" onclick="window.location='{{ route('commandes.index') }}'">
              <td class="td-mono" style="font-size:11px;">NC-{{ $c->created_at->format('Y') }}-{{ str_pad($c->id, 4, '0', STR_PAD_LEFT) }}</td>
              <td>
                <div style="display:flex; align-items:center; gap:8px;">
                  <div style="width:26px; height:26px; border-radius:7px; background:var(--c-green-l); display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; color:var(--c-green-d); flex-shrink:0;">
                    {{ strtoupper(substr($c->pharmacie?->nom ?? 'N', 0, 1)) }}
                  </div>
                  <span style="font-weight:500; font-size:13px;">{{ $c->pharmacie?->nom ?? '—' }}</span>
                </div>
              </td>
              <td style="color:var(--c-muted); font-size:12px;">{{ $c->produit?->nom ?? '—' }}</td>
              <td class="td-right td-mono" style="color:var(--c-green-d); font-size:12px;">
                {{ number_format($c->tarif_unitaire * $c->quantite, 2, ',', ' ') }} €
              </td>
              <td>
                @php
                  $s = $c->statut?->value ?? $c->statut;
                  $b = match($s) {
                    'livree'   => ['badge-green', 'Livrée'],
                    'validee'  => ['badge-blue',  'Validée'],
                    'en_cours' => ['badge-amber', 'En cours'],
                    'annulee'  => ['badge-red',   'Annulée'],
                    default    => ['badge-gray',  ucfirst($s ?? '')],
                  };
                @endphp
                <span class="badge {{ $b[0] }}">{{ $b[1] }}</span>
              </td>
              <td style="font-size:11px; color:var(--c-faint); font-family:'DM Mono',monospace;">{{ $c->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    {{-- Mini stats sidebar --}}
    <div style="display:flex; flex-direction:column; gap:10px;">
      <div class="section-label" style="margin-bottom:0;">Indicateurs</div>

      <div class="crm-panel" style="padding:16px; display:flex; flex-direction:column; gap:14px;">

        <div style="display:flex; align-items:center; justify-content:space-between;">
          <span style="font-size:12px; color:var(--c-muted);">Commandes totales</span>
          <span style="font-family:'DM Mono',monospace; font-size:14px; font-weight:600; color:var(--c-text);">{{ $stats['commandes_total'] }}</span>
        </div>
        <div style="height:1px; background:var(--c-border);"></div>

        <div style="display:flex; align-items:center; justify-content:space-between;">
          <span style="font-size:12px; color:var(--c-muted);">Ce mois</span>
          <span style="font-family:'DM Mono',monospace; font-size:14px; font-weight:600; color:var(--c-blue);">{{ $stats['commandes_mois'] }}</span>
        </div>
        <div style="height:1px; background:var(--c-border);"></div>

        <div style="display:flex; align-items:center; justify-content:space-between;">
          <span style="font-size:12px; color:var(--c-muted);">Prospects ce mois</span>
          <span style="font-family:'DM Mono',monospace; font-size:14px; font-weight:600; color:var(--c-amber);">{{ $stats['prospects_mois'] }}</span>
        </div>
        <div style="height:1px; background:var(--c-border);"></div>

        <div style="display:flex; align-items:center; justify-content:space-between;">
          <span style="font-size:12px; color:var(--c-muted);">Moy. cmd/pharmacie</span>
          <span style="font-family:'DM Mono',monospace; font-size:14px; font-weight:600; color:var(--c-text);">{{ $stats['commande_moyenne_par_pharmacie'] }}</span>
        </div>
        <div style="height:1px; background:var(--c-border);"></div>

        <div style="display:flex; align-items:center; justify-content:space-between;">
          <span style="font-size:12px; color:var(--c-muted);">Sans commande (&gt;60j)</span>
          <span style="font-family:'DM Mono',monospace; font-size:14px; font-weight:600; color:{{ $stats['pharmacies_inactives'] > 0 ? 'var(--c-red)' : 'var(--c-green)' }};">{{ $stats['pharmacies_inactives'] }}</span>
        </div>

      </div>

      {{-- Quick actions --}}
      <div class="section-label" style="margin-bottom:0;">Actions rapides</div>
      <div style="display:flex; flex-direction:column; gap:6px;">
        <a href="{{ route('commandes.index') }}"
           style="display:flex; align-items:center; gap:10px; padding:11px 14px; background:var(--c-surface); border:1px solid var(--c-border); border-radius:9px; text-decoration:none; transition:all 0.15s;"
           onmouseover="this.style.background='var(--c-hover)'; this.style.borderColor='var(--c-bolder)'"
           onmouseout="this.style.background='var(--c-surface)'; this.style.borderColor='var(--c-border)'">
          <div style="width:28px; height:28px; background:var(--c-green-l); border-radius:7px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--c-green)" stroke-width="2" stroke-linecap="round"><path d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 100 4 2 2 0 000-4zm8 0a2 2 0 100 4 2 2 0 000-4zm.75-3H7.5"/></svg>
          </div>
          <span style="font-size:12px; font-weight:500; color:var(--c-text);">Nouvelle commande</span>
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="var(--c-faint)" stroke-width="2" stroke-linecap="round" style="margin-left:auto;"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ route('relances.index') }}"
           style="display:flex; align-items:center; gap:10px; padding:11px 14px; background:var(--c-surface); border:1px solid var(--c-border); border-radius:9px; text-decoration:none; transition:all 0.15s;"
           onmouseover="this.style.background='var(--c-hover)'; this.style.borderColor='var(--c-bolder)'"
           onmouseout="this.style.background='var(--c-surface)'; this.style.borderColor='var(--c-border)'">
          <div style="width:28px; height:28px; background:var(--c-amber-l); border-radius:7px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.95 12a19.79 19.79 0 01-3.07-8.67A2 2 0 012.86 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.83a16 16 0 006.07 6.07l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
          </div>
          <span style="font-size:12px; font-weight:500; color:var(--c-text);">Relances commerciales</span>
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="var(--c-faint)" stroke-width="2" stroke-linecap="round" style="margin-left:auto;"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ route('carte.index') }}"
           style="display:flex; align-items:center; gap:10px; padding:11px 14px; background:var(--c-surface); border:1px solid var(--c-border); border-radius:9px; text-decoration:none; transition:all 0.15s;"
           onmouseover="this.style.background='var(--c-hover)'; this.style.borderColor='var(--c-bolder)'"
           onmouseout="this.style.background='var(--c-surface)'; this.style.borderColor='var(--c-border)'">
          <div style="width:28px; height:28px; background:var(--c-blue-l); border-radius:7px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--c-blue)" stroke-width="2" stroke-linecap="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
          </div>
          <span style="font-size:12px; font-weight:500; color:var(--c-text);">Carte des pharmacies</span>
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="var(--c-faint)" stroke-width="2" stroke-linecap="round" style="margin-left:auto;"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>

  </div>

</div>
