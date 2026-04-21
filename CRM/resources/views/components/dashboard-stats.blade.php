<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
    <x-dashboard-card label="Pharmacies totales" :value="$stats['pharmacies_total']" color="blue" />
    <x-dashboard-card label="Commandes totales" :value="$stats['commandes_total']" color="green" />
    <x-dashboard-card label="Commandes en cours" :value="$stats['commandes_en_cours']" color="yellow" />
    <x-dashboard-card label="Commandes ce mois" :value="$stats['commandes_mois']" color="indigo" />
    <x-dashboard-card label="Prospects ce mois" :value="$stats['prospects_mois']" color="gray" />
    <x-dashboard-card label="Clients inactifs (>60j)" :value="$stats['clients_inactifs']" color="red" />
</div>

{{-- Section graphique --}}

<div class="bg-white rounded-xl shadow p-4">
    <h3 class="text-sm text-gray-600 font-semibold mb-3">Répartition des pharmacies par statut</h3>
    <div x-data="{
        chart: null,
        init() {
            this.chart = new ApexCharts(this.$refs.chart, {
                chart: {
                    type: 'donut',
                    height: 300
                },
                series: {{ json_encode(array_values($stats['pharmacies_par_statut']->toArray())) }},
                labels: {{ json_encode(array_keys($stats['pharmacies_par_statut']->toArray())) }},
                legend: {
                    position: 'bottom'
                },
                responsive: [{
                    breakpoint: 768,
                    options: {
                        chart: { width: '100%' },
                        legend: { position: 'bottom' }
                    }
                }]
            });
            this.chart.render();
        }
    }" x-init="init()">
        <div x-ref="chart" class="w-full max-w-md mx-auto"></div>
    </div>
</div>
