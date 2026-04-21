<div style="display:flex; flex-direction:column; gap:28px;">

  <!-- KPI -->
  <div>
    <div class="section-label">Indicateurs clés</div>
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:14px;">
      <x-dashboard-card label="Pharmacies totales"           :value="$stats['pharmacies_total']"               color="blue"   icon="pharmacies" />
      <x-dashboard-card label="Commandes totales"            :value="$stats['commandes_total']"                color="green"  icon="commandes" />
      <x-dashboard-card label="Commandes ce mois"            :value="$stats['commandes_mois']"                 color="green"  icon="mois" />
      <x-dashboard-card label="Prospects ce mois"            :value="$stats['prospects_mois']"                 color="muted"  icon="prospect" />
      <x-dashboard-card label="Commandes / pharmacie (moy.)" :value="$stats['commande_moyenne_par_pharmacie']" color="blue"   icon="moyenne" />
      <x-dashboard-card label="Commandes en retard"          :value="$stats['commandes_retard']"               color="yellow" icon="retard" />
    </div>
  </div>

  <!-- Répartitions -->
  <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:16px;">
    <div class="crm-panel">
      <div class="section-label">Pharmacies par statut</div>
      <div x-data="{
        chart: null,
        init() {
          if (!this.chart) {
            this.chart = new ApexCharts(this.$refs.chart, {
              chart: { type: 'donut', height: 240, background: 'transparent', fontFamily: 'DM Sans, sans-serif', animations: { speed: 400 } },
              series: {{ json_encode(array_values($stats['pharmacies_par_statut']->toArray())) }},
              labels: {{ json_encode(array_values(array_map(fn($k) => match($k) { 'client_actif' => 'Actif', 'client_inactif' => 'Inactif', 'prospect' => 'Prospect', default => $k }, array_keys($stats['pharmacies_par_statut']->toArray())))) }},
              colors: ['#16A34A','#D97706','#C4D6C6'],
              plotOptions: { pie: { donut: { size: '68%', labels: { show: true,
                total: { show: true, label: 'Total', color: '#5E8264', fontSize: '11px', fontWeight: '600', fontFamily: 'DM Sans' },
                value: { color: '#18271C', fontSize: '26px', fontFamily: 'Fraunces, serif', fontWeight: '400', offsetY: 4 }
              } } } },
              legend: { position: 'bottom', labels: { colors: '#5E8264' }, fontSize: '12px', fontFamily: 'DM Sans', markers: { radius: 4, width: 8, height: 8 }, itemMargin: { horizontal: 10 } },
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

    <div class="crm-panel">
      <div class="section-label">Commandes par statut</div>
      <div x-data="{
        chart: null,
        init() {
          if (!this.chart) {
            this.chart = new ApexCharts(this.$refs.chart, {
              chart: { type: 'donut', height: 240, background: 'transparent', fontFamily: 'DM Sans, sans-serif', animations: { speed: 400 } },
              series: {{ json_encode(array_values($stats['commandes_par_statut']->toArray())) }},
              labels: {{ json_encode(array_values(array_map(fn($k) => match($k) { 'livree' => 'Livrée', 'validee' => 'Validée', 'en_cours' => 'En cours', 'annulee' => 'Annulée', default => $k }, array_keys($stats['commandes_par_statut']->toArray())))) }},
              colors: ['#16A34A','#2563EB','#D97706','#DC2626'],
              plotOptions: { pie: { donut: { size: '68%', labels: { show: true,
                total: { show: true, label: 'Total', color: '#5E8264', fontSize: '11px', fontWeight: '600', fontFamily: 'DM Sans' },
                value: { color: '#18271C', fontSize: '26px', fontFamily: 'Fraunces, serif', fontWeight: '400', offsetY: 4 }
              } } } },
              legend: { position: 'bottom', labels: { colors: '#5E8264' }, fontSize: '12px', fontFamily: 'DM Sans', markers: { radius: 4, width: 8, height: 8 }, itemMargin: { horizontal: 10 } },
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
  </div>

  <!-- Évolutions -->
  <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:16px;">
    <div class="crm-panel">
      <div class="section-label">Évolution des commandes</div>
      <div x-data="{
        chart: null,
        init() {
          if (!this.chart) {
            this.chart = new ApexCharts(this.$refs.chart, {
              chart: { type: 'area', height: 190, background: 'transparent', fontFamily: 'DM Sans, sans-serif', toolbar: { show: false }, sparkline: { enabled: false }, animations: { speed: 500 } },
              series: [{ name: 'Commandes', data: {{ json_encode(array_values($stats['evolution_commandes']->toArray())) }} }],
              xaxis: {
                categories: {{ json_encode(array_keys($stats['evolution_commandes']->toArray())) }},
                labels: { rotate: -40, style: { colors: '#9DBDA0', fontSize: '10px', fontFamily: 'DM Mono' } },
                axisBorder: { show: false }, axisTicks: { show: false },
              },
              yaxis: { labels: { style: { colors: '#9DBDA0', fontSize: '11px', fontFamily: 'DM Mono' } }, min: 0 },
              grid: { borderColor: '#E0E9E1', strokeDashArray: 4, padding: { left: 0, right: 0 } },
              colors: ['#16A34A'],
              fill: { type: 'gradient', gradient: { shade: 'light', type: 'vertical', stops: [0, 100], opacityFrom: 0.2, opacityTo: 0.0 } },
              stroke: { curve: 'smooth', width: 2.5 },
              markers: { size: 3, colors: ['#16A34A'], strokeColors: '#fff', strokeWidth: 2 },
              tooltip: { theme: 'light', style: { fontFamily: 'DM Sans' }, x: { show: true } },
              dataLabels: { enabled: false },
            });
            this.chart.render();
          }
        }
      }" x-init="init()">
        <div x-ref="chart"></div>
      </div>
    </div>

    <div class="crm-panel">
      <div class="section-label">Évolution des pharmacies</div>
      <div x-data="{
        chart: null,
        init() {
          if (!this.chart) {
            this.chart = new ApexCharts(this.$refs.chart, {
              chart: { type: 'area', height: 190, background: 'transparent', fontFamily: 'DM Sans, sans-serif', toolbar: { show: false }, animations: { speed: 500 } },
              series: [{ name: 'Pharmacies', data: {{ json_encode(array_values($stats['evolution_pharmacies']->toArray())) }} }],
              xaxis: {
                categories: {{ json_encode(array_keys($stats['evolution_pharmacies']->toArray())) }},
                labels: { rotate: -40, style: { colors: '#9DBDA0', fontSize: '10px', fontFamily: 'DM Mono' } },
                axisBorder: { show: false }, axisTicks: { show: false },
              },
              yaxis: { labels: { style: { colors: '#9DBDA0', fontSize: '11px', fontFamily: 'DM Mono' } }, min: 0 },
              grid: { borderColor: '#E0E9E1', strokeDashArray: 4, padding: { left: 0, right: 0 } },
              colors: ['#2563EB'],
              fill: { type: 'gradient', gradient: { shade: 'light', type: 'vertical', stops: [0, 100], opacityFrom: 0.18, opacityTo: 0.0 } },
              stroke: { curve: 'smooth', width: 2.5 },
              markers: { size: 3, colors: ['#2563EB'], strokeColors: '#fff', strokeWidth: 2 },
              tooltip: { theme: 'light', style: { fontFamily: 'DM Sans' }, x: { show: true } },
              dataLabels: { enabled: false },
            });
            this.chart.render();
          }
        }
      }" x-init="init()">
        <div x-ref="chart"></div>
      </div>
    </div>
  </div>

</div>
