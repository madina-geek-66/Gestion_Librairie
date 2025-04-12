<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Paiement;
use App\Models\Livre;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Récupération des filtres
        $periode = $request->input('periode', 'month');
        $annee = $request->input('annee', Carbon::now()->year);
        $mois = $request->input('mois', Carbon::now()->month);

        // Déterminer les dates de début et fin selon la période
        if ($periode === 'year') {
            $startDate = Carbon::createFromDate($annee, 1, 1)->startOfDay();
            $endDate = Carbon::createFromDate($annee, 12, 31)->endOfDay();
            $compareStartDate = Carbon::createFromDate($annee - 1, 1, 1)->startOfDay();
            $compareEndDate = Carbon::createFromDate($annee - 1, 12, 31)->endOfDay();
        } elseif ($periode === 'month') {
            $startDate = Carbon::createFromDate($annee, $mois, 1)->startOfDay();
            $endDate = Carbon::createFromDate($annee, $mois, 1)->endOfMonth()->endOfDay();
            $compareStartDate = Carbon::createFromDate($annee, $mois, 1)->subMonth()->startOfDay();
            $compareEndDate = Carbon::createFromDate($annee, $mois, 1)->subMonth()->endOfMonth()->endOfDay();
        } else {
            // Par défaut, aujourd'hui vs hier
            $startDate = Carbon::today();
            $endDate = Carbon::today()->endOfDay();
            $compareStartDate = Carbon::yesterday();
            $compareEndDate = Carbon::yesterday()->endOfDay();
        }

        // Commandes du jour et statistiques
        $commandesJour = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $commandesComparaison = Order::whereBetween('created_at', [$compareStartDate, $compareEndDate])->count();
        $pourcentageCommandes = $commandesComparaison > 0
            ? round((($commandesJour - $commandesComparaison) / $commandesComparaison) * 100)
            : 100;

        // Commandes validées (statut "Payée" ou "Expédiée")
        $commandesValidees = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('statut', ['Payée', 'Expédiée'])
            ->count();
        $commandesValideesComparaison = Order::whereBetween('created_at', [$compareStartDate, $compareEndDate])
            ->whereIn('statut', ['Payée', 'Expédiée'])
            ->count();
        $pourcentageValidees = $commandesValideesComparaison > 0
            ? round((($commandesValidees - $commandesValideesComparaison) / $commandesValideesComparaison) * 100)
            : 100;

        // Recettes journalières
        $recettesJour = Paiement::whereBetween('date_paiement', [$startDate, $endDate])->sum('montant');
        $recettesComparaison = Paiement::whereBetween('date_paiement', [$compareStartDate, $compareEndDate])->sum('montant');
        $pourcentageRecettes = $recettesComparaison > 0
            ? round((($recettesJour - $recettesComparaison) / $recettesComparaison) * 100)
            : 100;

        // Livres vendus
        $livresVendus = OrderItem::whereHas('order', function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->sum('quantite');

        $livresVendusComparaison = OrderItem::whereHas('order', function($query) use ($compareStartDate, $compareEndDate) {
            $query->whereBetween('created_at', [$compareStartDate, $compareEndDate]);
        })->sum('quantite');

        $pourcentageLivres = $livresVendusComparaison > 0
            ? round((($livresVendus - $livresVendusComparaison) / $livresVendusComparaison) * 100)
            : 100;

        // Commandes par mois pour l'année en cours
        $commandesParMois = $this->getCommandesParMois($annee);

        // Ventes par catégorie pour la période sélectionnée
        $ventesParCategorie = $this->getVentesParCategorie($startDate, $endDate);

        // Top 5 des livres les plus vendus pour la période
        $topLivres = $this->getTopLivres($startDate, $endDate);

        // Revenus par jour pour le mois en cours (pour graphique)
        $revenusParJour = [];
        if ($periode === 'month') {
            $revenusParJour = $this->getRevenusParJour($annee, $mois);
        }

        // Commandes récentes
        $commandesRecentes = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Années disponibles pour le filtre
        $annees = Order::selectRaw('EXTRACT(YEAR FROM created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->map(function($year) {
                return (int)$year;
            })
            ->toArray();

        // Si aucune année n'est disponible, ajouter l'année en cours
        if (empty($annees)) {
            $annees = [Carbon::now()->year];
        }

        return view('gestionnaire.rapport.dashboard', compact(
            'commandesJour',
            'pourcentageCommandes',
            'commandesValidees',
            'pourcentageValidees',
            'recettesJour',
            'pourcentageRecettes',
            'livresVendus',
            'pourcentageLivres',
            'commandesParMois',
            'ventesParCategorie',
            'topLivres',
            'revenusParJour',
            'commandesRecentes',
            'periode',
            'annee',
            'mois',
            'annees'
        ));
    }

    private function getCommandesParMois($annee)
    {
        $commandesData = Order::selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count')
            ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [$annee])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $result = array_fill(0, 12, 0); // Initialiser un tableau de 12 mois avec des zéros

        foreach ($commandesData as $data) {
            $monthIndex = (int)$data->month - 1; // Les indices de tableau commencent à 0
            $result[$monthIndex] = (int)$data->count;
        }

        return $result;
    }

    private function getVentesParCategorie($startDate, $endDate)
    {
        $categories = Categorie::all();
        $result = [
            'labels' => [],
            'data' => [],
            'colors' => [
                '#FF85A2', '#5DADE2', '#FFDA83', '#70D6BF', '#BF7CFF',
                '#FF6B6B', '#4ECDC4', '#FFD166', '#577590', '#F2CC8F',
                '#E07A5F', '#81B29A', '#F4A261', '#6D597A', '#B5838D'
            ]
        ];

        foreach ($categories as $index => $categorie) {
            $ventesCategorie = OrderItem::join('livres', 'order_items.livre_id', '=', 'livres.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('livres.categorie_id', $categorie->id)
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->sum('order_items.quantite');

            $result['labels'][] = $categorie->nom;
            $result['data'][] = (int)$ventesCategorie;
        }

        return $result;
    }

    private function getTopLivres($startDate, $endDate, $limit = 5)
    {
        return OrderItem::select('livres.id', 'livres.titre', DB::raw('SUM(order_items.quantite) as total_vendu'))
            ->join('livres', 'order_items.livre_id', '=', 'livres.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('livres.id', 'livres.titre')
            ->orderByDesc('total_vendu')
            ->limit($limit)
            ->get();
    }

    private function getRevenusParJour($annee, $mois)
    {
        $startOfMonth = Carbon::createFromDate($annee, $mois, 1)->startOfDay();
        $endOfMonth = Carbon::createFromDate($annee, $mois, 1)->endOfMonth()->endOfDay();
        $daysInMonth = $startOfMonth->daysInMonth;

        $revenusData = Paiement::selectRaw('EXTRACT(DAY FROM date_paiement) as day, SUM(montant) as revenue')
            ->whereBetween('date_paiement', [$startOfMonth, $endOfMonth])
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $result = [
            'labels' => range(1, $daysInMonth),
            'data' => array_fill(0, $daysInMonth, 0)
        ];

        foreach ($revenusData as $data) {
            $dayIndex = (int)$data->day - 1;
            $result['data'][$dayIndex] = (float)$data->revenue;
        }

        return $result;
    }
}
