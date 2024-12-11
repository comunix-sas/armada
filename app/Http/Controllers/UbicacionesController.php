<?php

namespace App\Http\Controllers;

use App\Models\Ubicaciones;
use Illuminate\Http\Request;

class UbicacionesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Ubicaciones::query();

            // Si hay un término de búsqueda
            if ($request->has('q')) {
                $searchTerm = $request->q;
                $query->where('ubicacion', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('codigo', 'LIKE', "%{$searchTerm}%");
            }

            // Obtener todas las ubicaciones sin paginación
            $ubicaciones = $query->get();

            // Formato esperado por Select2
            return response()->json([
                'results' => $ubicaciones->map(function($ubicacion) {
                    return [
                        'id' => $ubicacion->id,
                        'text' => $ubicacion->codigo . ' - ' . $ubicacion->ubicacion
                    ];
                }),
                'pagination' => [
                    'more' => false
                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'error' => 'Error al obtener ubicaciones',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $ubicacion = Ubicaciones::findOrFail($id);
            return response()->json([
                'id' => $ubicacion->id,
                'text' => $ubicacion->codigo . ' - ' . $ubicacion->ubicacion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener la ubicación',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
