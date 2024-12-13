<?php

namespace App\Http\Controllers\precontractual;

use App\Http\Controllers\Controller;
use App\Models\PlanAdquisicion;
use App\Models\ValidacionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidacionPlanController extends Controller
{
  public function index()
  {
    $planes = PlanAdquisicion::with('validacion')->get();
    return view('content.precontractual.validacion-plan', compact('planes'));
  }

  public function validar(Request $request, $id)
  {
    $request->validate([
      'estado' => 'required|in:aprobado,rechazado,pendiente',
      'observaciones' => 'required_if:estado,rechazado'
    ]);

    $validacion = ValidacionPlan::updateOrCreate(
      ['plan_adquisicion_id' => $id],
      [
        'estado' => $request->estado,
        'observaciones' => $request->observaciones,
        'usuario_id' => Auth::user()->idUsuario,
        'fecha_validacion' => now()
      ]
    );

    return response()->json([
      'success' => true,
      'message' => 'Plan validado correctamente',
      'validacion' => $validacion
    ]);
  }

  public function obtenerDetalles($id)
  {
    $plan = PlanAdquisicion::with('validacion')->findOrFail($id);
    return response()->json($plan);
  }
}
