<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Administration - {{ Auth::user()->name }}</h1>
                    <p class="mb-8 text-gray-600">Interface d'administration complète</p>
                    
                    <!-- Graphiques (plus petits) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Graphique circulaire (camembert) -->
                        <div class="bg-white rounded-lg shadow p-4 flex flex-col items-center">
                            <h3 class="text-base font-semibold mb-2">Répartition des rôles utilisateurs</h3>
                            <div class="w-[220px] h-[220px]">
                                <canvas id="rolesChart" width="220" height="220"></canvas>
                            </div>
                        </div>
                        <!-- Graphique en courbe (commandes par mois) -->
                        <div class="bg-white rounded-lg shadow p-4">
                            <h3 class="text-base font-semibold mb-2 text-center">Commandes par mois (12 derniers mois)</h3>
                            <canvas id="commandesChart" style="width:100%;max-width:100%;height:220px;"></canvas>
                        </div>
                    </div>

                    <!-- Chart.js -->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        // Camembert des rôles
                        const rolesChart = new Chart(document.getElementById('rolesChart'), {
                            type: 'doughnut',
                            data: {
                                labels: {!! json_encode(array_keys($roles)) !!},
                                datasets: [{
                                    data: {!! json_encode(array_values($roles)) !!},
                                    backgroundColor: [
                                        '#3b82f6', '#22c55e', '#f59e42', '#a78bfa'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                cutout: '70%',
                                plugins: {
                                    legend: { position: 'bottom' }
                                }
                            }
                        });

                        // Courbe des commandes par mois
                        const commandesChart = new Chart(document.getElementById('commandesChart'), {
                            type: 'line',
                            data: {
                                labels: {!! json_encode($labelsMois) !!},
                                datasets: [{
                                    label: 'Commandes',
                                    data: {!! json_encode($commandesParMois) !!},
                                    fill: true,
                                    borderColor: '#3b82f6',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    tension: 0.3,
                                    pointRadius: 3,
                                    pointBackgroundColor: '#3b82f6'
                                }]
                            },
                            options: {
                                plugins: {
                                    legend: { display: false }
                                },
                                elements: {
                                    line: { borderWidth: 2 },
                                    point: { radius: 3 }
                                }
                            }
                        });
                    </script>

                    <!-- Dernières demandes d'entrepreneurs en attente -->
                    <div class="mt-8 mb-8">
                        <h3 class="text-lg font-semibold text-blue-800 mb-4">Dernières demandes d'entrepreneurs en attente</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg shadow">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Nom</th>
                                        <th class="px-4 py-2">Entreprise</th>
                                        <th class="px-4 py-2">Email</th>
                                        <th class="px-4 py-2">Date d'inscription</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dernieresDemandes as $demande)
                                        <tr class="border-t">
                                            <td class="px-4 py-2">{{ $demande->name }}</td>
                                            <td class="px-4 py-2">{{ $demande->nom_entreprise }}</td>
                                            <td class="px-4 py-2">{{ $demande->email }}</td>
                                            <td class="px-4 py-2">{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">Aucune demande récente</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Dernières commandes -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-green-800 mb-4">Dernières commandes</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg shadow">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">ID</th>
                                        <th class="px-4 py-2">Client</th>
                                        <th class="px-4 py-2">Date</th>
                                        <th class="px-4 py-2">Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dernieresCommandes as $commande)
                                        <tr class="border-t">
                                            <td class="px-4 py-2">{{ $commande->id }}</td>
                                            <td class="px-4 py-2">{{ $commande->nom_client ?? ($commande->user->name ?? '-') }}</td>
                                            <td class="px-4 py-2">{{ $commande->created_at ? $commande->created_at->format('d/m/Y H:i') : '-' }}</td>
                                            <td class="px-4 py-2">{{ $commande->total_prix ?? $commande->calculerTotal() }} €</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">Aucune commande récente</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Gestion des Produits -->
                        <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                            <h3 class="text-lg font-semibold text-orange-800 mb-3">Gestion des Produits</h3>
                            <p class="text-orange-600 mb-4">Administration complète des produits</p>
                            <a href="{{ route('produits.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-700 text-white font-bold rounded transition-colors duration-200"
                               style="background-color: #f97316 !important;">
                                Gérer les Produits
                            </a>
                        </div>

                        <!-- Gestion des Commandes -->
                        <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                            <h3 class="text-lg font-semibold text-green-800 mb-3">Gestion des Commandes</h3>
                            <p class="text-green-600 mb-4">Suivi et administration des commandes</p>
                            <a href="{{ route('admin.commandes') }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-700 text-white font-bold rounded transition-colors duration-200"
                               style="background-color: #22c55e !important;">
                                Gérer les Commandes
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>