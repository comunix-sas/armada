<?php

namespace App\Http\Controllers\precontractual;

use App\Models\PlanAdquisicion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\precontractual\PreContractual;

class PreContractualController extends Controller
{
    public function index()
    {
        $planes = PlanAdquisicion::all();
        return view('content.precontractual.precontractual', compact('planes'));
    }

    public function validarPlanAdquisicion($id)
    {
        $plan = PlanAdquisicion::findOrFail($id);
        return response()->json([
            'existe' => true,
            'detalles' => $plan
        ]);
    }

    public function actualizarEstudioPrevio(Request $request, $id)
    {
        $preContractual = PreContractual::findOrFail($id);
        $preContractual->update([
            'estudio_previo' => $request->estudio_previo,
            'estado_estudio_previo' => $request->estado
        ]);

        // Registrar historial
        $preContractual->historialCambios()->create([
            'tipo_cambio' => 'actualizacion_estudio',
            'estado_anterior' => $preContractual->getOriginal('estado_estudio_previo'),
            'estado_nuevo' => $request->estado,
            'usuario_id' => auth()->id()
        ]);

        // Enviar notificación si es necesario
        if($request->estado === 'aprobado') {
            $this->notificarAreaAdquisiciones($preContractual);
        }

        return response()->json(['message' => 'Estudio previo actualizado']);
    }

    private function notificarAreaAdquisiciones($preContractual)
    {
        // Implementar lógica de notificación
        // Puede ser por correo electrónico o notificación interna
    }

    // Otros métodos del controlador...
}
