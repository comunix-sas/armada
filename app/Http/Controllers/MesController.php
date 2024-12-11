<?php

namespace App\Http\Controllers;

use App\Models\Mes;
use Illuminate\Http\Request;

class MesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Mes::query();

            if ($request->has('q')) {
                $searchTerm = $request->q;
                $query->where('nombre', 'LIKE', "%{$searchTerm}%");
            }

            $meses = $query->orderBy('id', 'asc')->get();

            return response()->json([
                'results' => $meses->map(function($mes) {
                    return [
                        'id' => $mes->id,
                        'text' => $mes->nombre
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener meses',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $mes = Mes::findOrFail($id);
            return response()->json([
                'id' => $mes->id,
                'text' => $mes->nombre
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el mes',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 
