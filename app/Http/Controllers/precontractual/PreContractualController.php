<?php

namespace App\Http\Controllers\precontractual;

use App\Http\Controllers\Controller;
use App\Models\PreContractual;
use App\Models\PlanAdquisicion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class PreContractualController extends Controller
{
  public function index()
  {
    $planes = PlanAdquisicion::all();
    return view('content.precontractual.precontractual', compact('planes'));
  }

  public function validarPlanAdquisicion($id)
  {
    try {
      $plan = PlanAdquisicion::findOrFail($id);
      return response()->json([
        'existe' => true,
        'detalles' => $plan
      ]);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'existe' => false,
        'message' => 'Plan no encontrado'
      ], 404);
    }
  }

  public function actualizarEstudioPrevio(Request $request, $id)
  {
    try {
      $preContractual = PreContractual::findOrFail($id);
      $preContractual->update([
        'estudio_previo' => $request->estudio_previo,
        'estado_estudio_previo' => $request->estado
      ]);

      return response()->json(['message' => 'Estudio previo actualizado']);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'message' => 'Precontractual no encontrado'
      ], 404);
    }
  }

  public function store(Request $request)
  {
    try {
      $request->validate([
        'planes' => 'required|array',
        'planes.*' => 'exists:sgc_plan_adquisicion,idPlan',
        'estudioPrevio' => 'required|file|mimes:pdf,doc,docx,xlsx,xls',
        'estadoEstudio' => 'required|in:pendiente,en_revision,aprobado,rechazado',
        'notaAdicional' => 'required_if:estadoEstudio,rechazado'
      ]);

      $planesCreados = [];

      foreach ($request->planes as $planId) {
        $estudioPrevioPath = $request->file('estudioPrevio')
          ->storeAs('estudios_previos', 'estudio_previo_' . $planId . '.' . $request->file('estudioPrevio')->getClientOriginalExtension(), 'public');

        $preContractual = PreContractual::create([
          'plan_adquisicion_id' => $planId,
          'titulo' => 'Estudio previo para plan ' . $planId,
          'estudio_previo_path' => $estudioPrevioPath,
          'estado_estudio_previo' => 'pendiente',
          'created_by' => Auth::user()->id,
          'estado_proceso' => 'en_curso'
        ]);

        $planesCreados[] = $preContractual->idPrecontractual;
      }

      return redirect()->route('precontractual.index')
        ->with('success', 'Planes precontractuales registrados exitosamente');

    } catch (\Illuminate\Validation\ValidationException $e) {
      return redirect()->back()
        ->withErrors($e->errors())
        ->withInput();
    } catch (ModelNotFoundException $e) {
      return redirect()->back()
        ->with('error', 'Recurso no encontrado');
    } catch (\Exception $e) {
      return redirect()->back()
        ->with('error', 'Error al registrar los planes precontractuales');
    }
  }
  public function obtenerPlanesValidacionId($id)
  {
    try {
      $plan = PreContractual::with([
        'planAdquisicion',
        'createdBy',
        'updatedBy',
        'historial.usuario'
      ])->findOrFail($id);

      $documentoPath = $plan->estudio_previo_path;
        $documentoUrl = null;

      $documentoUrl = $documentoPath ? asset('storage/app/public/' . $documentoPath) : null;


      $planFormateado = [
        'id' => $plan->idPrecontractual,
        'nombrePlan' => $plan->planAdquisicion ? $plan->planAdquisicion->nombrePlan : 'Plan no encontrado',
        'estado' => $plan->estado_estudio_previo ?? 'Sin estado',
        'fechaInicio' => $plan->created_at->format('Y-m-d'),
        'ultimaActualizacion' => $plan->updated_at->format('Y-m-d'),
        'documentoUrl' => $documentoUrl,
        'creadoPor' => $plan->createdBy ? $plan->createdBy->name : 'Usuario no disponible',
        'actualizadoPor' => $plan->updatedBy ? $plan->updatedBy->name : 'Usuario no disponible',
        'historial' => $plan->historial->map(function ($registro) {
          return [
            'id' => $registro->id,
            'tipo_cambio' => $registro->tipo_cambio,
            'estado_anterior' => $registro->estado_anterior,
            'estado_nuevo' => $registro->estado_nuevo,
            'comentarios' => $registro->comentarios,
            'usuario' => $registro->usuario ? $registro->usuario->name : 'Usuario no disponible',
            'fecha_cambio' => Carbon::parse($registro->fecha_cambio)->format('Y-m-d H:i:s')
          ];
        })
      ];

      return response()->json([
        'success' => true,
        'data' => $planFormateado
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ], 500);
    }
  }
  public function obtenerPlanesValidacion()
  {
    try {
      $planes = PreContractual::with(['planAdquisicion'])
        ->orderBy('created_at', 'desc')
        ->get();

      $planesFormateados = $planes->map(function ($plan) {
        $documentoPath = $plan->estudio_previo_path;
        $documentoUrl = $documentoPath ? URL('storage/' . $documentoPath) : null;
        try {
          return [
            'id' => $plan->idPrecontractual,
            'nombrePlan' => $plan->planAdquisicion ? $plan->planAdquisicion->nombrePlan : 'Plan no encontrado',
            'estado' => $plan->estado_estudio_previo ?? 'Sin estado',
            'fechaInicio' => optional($plan->created_at)->format('Y-m-d') ?? 'Fecha no disponible',
            'ultimaActualizacion' => optional($plan->updated_at)->format('Y-m-d') ?? 'Fecha no disponible',
            'documentoUrl' => $documentoUrl,
          ];
        } catch (\Exception $e) {
          return null;
        }
      })->filter();

      return response()->json([
        'success' => true,
        'data' => $planesFormateados
      ]);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ], 404);
    }
  }
}
