<?php

namespace App\Http\Controllers;

use App\Models\ModalidadSeleccion;
use Illuminate\Http\Request;

class ModalidadSeleccionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = ModalidadSeleccion::query();

            if ($request->has('q')) {
                $searchTerm = $request->q;
                $query->where('descripcion', 'LIKE', "%{$searchTerm}%");
            }

            $modalidades = $query->get();

            return response()->json([
                'results' => $modalidades->map(function($modalidad) {
                    return [
                        'id' => $modalidad->id,
                        'text' => $modalidad->descripcion
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener modalidades: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $modalidad = ModalidadSeleccion::findOrFail($id);
            return response()->json([
                'id' => $modalidad->id,
                'text' => $modalidad->descripcion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener la modalidad: ' . $e->getMessage()
            ], 500);
        }
    }
} 
