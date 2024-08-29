<?php

namespace App\Modules\Consommations\Http\Controllers;

use App\Modules\Consommations\Models\Consommations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Modules\Consommateurs\Models\Consommateurs;
use Illuminate\Support\Facades\Log;


class ConsommationsController
{
    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    
    /**
     * Récupérer tous les consommations
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $consommations = Consommations::all();
        return response()->json($consommations);
    }

    /**
     * Créer une nouvelle consommation
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'consommateur_id' => 'required|exists:consommateurs,id',
            'date' => 'required|date',
            'heure' => 'required|date_format:H:i',
            'anomalie' => 'boolean',
            'absence' => 'boolean',
            'date_controle' => 'nullable|date',
            'qte' => 'required|integer',
            'description' => 'nullable|string',
            'mt_sub' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation error',
                'details' => $validator->errors()
            ], 400);
        }

        $consommation = Consommations::create($request->all());

        return response()->json($consommation, 201);
    }

    /**
     * Récupérer les détails d'une consommation spécifique
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $consommation = Consommations::findOrFail($id);
        return response()->json($consommation);
    }

    /**
     * Mettre à jour une consommation spécifique
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'consommateur_id' => 'sometimes|exists:consommateurs,id',
            'date' => 'sometimes|date',
            'heure' => 'sometimes|date_format:H:i',
            'anomalie' => 'sometimes|boolean',
            'absence' => 'sometimes|boolean',
            'date_controle' => 'nullable|date',
            'qte' => 'sometimes|integer',
            'description' => 'nullable|string',
            'mt_sub' => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation error',
                'details' => $validator->errors()
            ], 400);
        }

        $consommation = Consommations::findOrFail($id);
        $consommation->update($request->all());

        return response()->json($consommation, 200);
    }

    /**
     * Supprimer une consommation spécifique
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $consommation = Consommations::findOrFail($id);
        $consommation->delete();

        return response()->json(['message' => 'Consommation deleted'], 200);
    }

    public function store(Request $request)
    {
        // Log des données reçues
        Log::info('Données reçues:', $request->all());
    
        // Valider les données envoyées
        $data = $request->validate([
            'anomalies.*.matricule' => 'required|string',
            'anomalies.*.date' => 'required|date_format:d/m/Y',
            'anomalies.*.heure' => 'required|date_format:H:i',
            'anomalies.*.description' => 'nullable|string',
            'anomalies.*.quantite' => 'required|integer',
            'anomalies.*.mt_sub' => 'required|numeric',
            'anomalies.*.dateControle' => 'nullable|date_format:d/m/Y',
            'anomalies.*.anomalie' => 'nullable|boolean',
        ]);
    
        // Log des données validées
        Log::info('Données validées:', $data);
    
        // Récupérer tous les matricules et les consommateurs associés
        $matricules = array_column($data['anomalies'], 'matricule');
        $consommateurs = Consommateurs::whereIn('username', $matricules)->pluck('id', 'username')->toArray();
    
        $consommationsToInsert = [];
        $existingConsumptions = [];
    
        foreach ($data['anomalies'] as $anomaly) {
            $consommateurId = $consommateurs[$anomaly['matricule']] ?? null;
            if ($consommateurId) {
                $date = $this->convertDateFormat($anomaly['date']);
                
                // Vérifier si la consommation existe déjà
                $exists = Consommations::where('consommateur_id', $consommateurId)
                                       ->whereDate('date', $date)
                                       ->exists();
                
                if (!$exists) {
                    $consommationsToInsert[] = [
                        'consommateur_id' => $consommateurId,
                        'date' => $date,
                        'heure' => $anomaly['heure'],
                        'anomalie' => $anomaly['anomalie'] ?? false,
                        'absence' => false,
                        'date_controle' => $this->convertDateFormat($anomaly['dateControle']) ?? null,
                        'qte' => $anomaly['quantite'],
                        'description' => $anomaly['description'] ?? null,
                        'mt_sub' => $anomaly['mt_sub'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                } else {
                    // Log ou gérer le cas où la consommation existe déjà
                    Log::warning('Consommation déjà existante pour le matricule: ' . $anomaly['matricule'] . ' à la date: ' . $anomaly['date']);
                }
            } else {
                // Si le consommateur n'existe pas, vous pouvez gérer l'erreur ici
                Log::warning('Consommateur non trouvé pour matricule: ' . $anomaly['matricule']);
            }
        }
    
        // Insertion en masse des consommations
        if (!empty($consommationsToInsert)) {
            Consommations::insert($consommationsToInsert);
        }
    
        return response()->json(['message' => 'Anomalies traitées avec succès.']);
    }
    

private function convertDateFormat($date)
{
    return \DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');
}
public function getStatistics()
{
    // Total counts
    $totalConsommateurs = Consommateurs::count();
    $totalConsommations = Consommations::count();
    $anomaliesCount = Consommations::where('anomalie', true)->count();
    $quantitesTotales = Consommations::sum('qte');
    $prixTotalAnomalies = Consommations::where('anomalie', true)->sum('mt_sub');

    // Monthly consumption data
    $monthlyConsumptions = Consommations::selectRaw('MONTH(date) as month, SUM(qte) as total_quantity')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total_quantity', 'month')
        ->toArray();

    // Ensure all months are represented
    for ($i = 1; $i <= 12; $i++) {
        if (!isset($monthlyConsumptions[$i])) {
            $monthlyConsumptions[$i] = 0;
        }
    }

    ksort($monthlyConsumptions);

    return response()->json([
        'total_consommateurs' => $totalConsommateurs,
        'total_consommations' => $totalConsommations,
        'quantites_totales' => $quantitesTotales,
        'prix_total_anomalies' => $prixTotalAnomalies,
        'monthly_data' => array_values($monthlyConsumptions), // Ordered data for the chart
    ]);
}



public function getMonthlyAnomaliesForMonth($year, $month)
{
    try {
        $anomaliesCount = Consommations::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('anomalie', true)
            ->count();

        return response()->json([
            'year' => $year,
            'month' => $month,
            'anomalies_count' => $anomaliesCount
        ]);
    } catch (\Exception $e) {
        Log::error('Erreur lors de la récupération des anomalies pour le mois spécifique : ' . $e->getMessage());
        return response()->json(['error' => 'Erreur interne du serveur'], 500);
    }
}








public function getMonthlyConsumption($month)
{
    $consumption = Consommations::with('consommateur')
        ->whereMonth('date', $month)
        ->get()
        ->map(function ($item) {
            return [
                'matricule' => $item->consommateur->username,
                'nomPrenom' => $item->consommateur->firstname . ' ' . $item->consommateur->lastname, // Combiner prénom et nom
                'date' => $item->date,
                'heure' => $item->heure,
                'description' => $item->description,
                'quantite' => $item->qte,
                'mt_sub' => $item->mt_sub,
                'dateControle' => $item->date_controle,
                'anomalie' => $item->anomalie
            ];
        });

    return response()->json($consumption);
}
public function getMonthlyConsumptions()
{
    // Monthly consumption data
    $monthlyConsumptions = Consommations::selectRaw('MONTH(date) as month, SUM(qte) as total_quantity')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total_quantity', 'month')
        ->toArray();

    // Ensure all months are represented
    for ($i = 1; $i <= 12; $i++) {
        if (!isset($monthlyConsumptions[$i])) {
            $monthlyConsumptions[$i] = 0;
        }
    }

    ksort($monthlyConsumptions);

    return response()->json([
        'monthly_data' => array_values($monthlyConsumptions) // Ordered data for the chart
    ]);
}






}