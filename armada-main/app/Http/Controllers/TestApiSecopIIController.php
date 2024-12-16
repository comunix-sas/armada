<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestApiSecopIIController extends Controller
{
    public function index()
    {
        return view('content.apps.test');
    }

    public function getSecopData(Request $request)
    {
        try {
            $response = Http::timeout(60)
                ->retry(3, 5000)
                ->get('https://www.datos.gov.co/resource/p6dx-8zbt.json');

            if (!$response->successful()) {
                return response()->json([
                    'error' => 'Error al obtener datos del servidor'
                ], 500);
            }

            $data = $response->json();
            $filteredData = collect($data)->filter(function ($item) {
                return $item['nit_entidad'] === '800141644';
            });

            return response()->json([
                'data' => $filteredData->values()->all()
            ]);
        } catch (\Exception $e) {
            Log::error('Error en getSecopData: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error interno del servidor'
            ], 500);
        }
    }
}
