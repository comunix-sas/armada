<?php

namespace App\Http\Controllers\precontractual;

use App\Http\Controllers\Controller;
use App\Models\PreContractual;
use App\Models\PlanAdquisicion;
use App\Models\PreContractualHistorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

  public function store(Request $request)
  {
    try {
      $request->validate([
        'planes' => 'required|exists:sgc_plan_adquisicion,idPlan',
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

      return redirect()->back()->with('success', 'Planes precontractuales registrados exitosamente.');

    } catch (\Illuminate\Validation\ValidationException $e) {
      return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
      Log::error('Error al registrar los planes precontractuales: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Error al registrar los planes precontractuales: ' . $e->getMessage());
    }
  }
  public function update(Request $request, $id)
  {
  
    try {
        // Validación inicial
        $request->validate([
            'estadoEstudio' => 'required|in:pendiente,en_revision,aprobado,rechazado',
            'estudioPrevio' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls',
            'nota_adicional' => 'required_if:estadoEstudio,rechazado'
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        DB::beginTransaction();

        $preContractual = PreContractual::findOrFail($id);
        $estadoAnterior = $preContractual->estado_estudio_previo;
        $documentoUrl = $preContractual->estudio_previo_path;

        // Manejar el archivo si se proporciona uno nuevo
        if ($request->hasFile('estudioPrevio')) {
            $estudiosPreviosPaths = json_decode($preContractual->estudio_previo_path, true) ?? [];
            foreach ($request->file('estudioPrevio') as $file) {
                // Eliminar archivos anteriores si es necesario y agregar nuevos
                $estudiosPreviosPaths[] = $file->store('estudios_previos', 'public');
            }
            $preContractual->estudio_previo_path = json_encode($estudiosPreviosPaths);
        }

        // Actualizar el precontractual
        $preContractual->estado_estudio_previo = $request->estadoEstudio;
        $preContractual->updated_by = Auth::id();
        $preContractual->save();

        // Registrar en el historial
        PreContractualHistorial::create([
            'precontractual_id' => $id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $request->estadoEstudio,
            'tipo_cambio' => $request->tipo_cambio,
            'comentarios' => $request->estadoEstudio === 'rechazado' ? $request->nota_adicional : null,
            'usuario_id' => Auth::id(),
            'fecha_cambio' => now()
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Plan precontractual actualizado exitosamente',
            'data' => [
                'id' => $preContractual->idPrecontractual,
                'estado' => $preContractual->estado_estudio_previo,
                'documentoUrl' => $documentoUrl ? url('storage/' . $documentoUrl) : null
            ]
        ]);

    } catch (ModelNotFoundException $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Plan precontractual no encontrado'
        ], 404);
    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el plan precontractual',
            'error' => $e->getMessage()
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
        
        // Construir URL completa para el documento usando url() helper
        $documentoUrl = $documentoPath ? url('storage/' . $documentoPath) : null;

        return [
          'id' => $plan->idPrecontractual,
          'nombrePlan' => $plan->planAdquisicion ? $plan->planAdquisicion->nombrePlan : 'Plan no encontrado',
          'estado' => $plan->estado_estudio_previo ?? 'Sin estado',
          'fechaInicio' => $plan->created_at->format('Y-m-d'),
          'ultimaActualizacion' => $plan->updated_at->format('Y-m-d'),
          'documentoUrl' => $documentoUrl
        ];
      });

      return response()->json([
        'success' => true,
        'data' => $planesFormateados
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ], 500);
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
      // Construir URL completa para el documento usando url() helper
      $documentoUrl = $documentoPath ? url('storage/' . $documentoPath) : null;

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

  public function destroy($id)
  {
    try {
        DB::beginTransaction();
        
        $preContractual = PreContractual::findOrFail($id);
        
        // Eliminar el archivo físico si existe
        if ($preContractual->estudio_previo_path) {
            Storage::disk('public')->delete($preContractual->estudio_previo_path);
        }
        
        // Registrar en el historial
        PreContractualHistorial::create([
            'precontractual_id' => $id,
            'estado_anterior' => $preContractual->estado_estudio_previo,
            'estado_nuevo' => 'eliminado',
            'comentarios' => 'Plan eliminado del sistema',
            'usuario_id' => Auth::id(),
            'fecha_cambio' => now()
        ]);
        
        // Eliminar el registro
        $preContractual->delete();
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Plan eliminado correctamente'
        ]);
        
    } catch (ModelNotFoundException $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Plan no encontrado'
        ], 404);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar el plan: ' . $e->getMessage()
        ], 500);
    }
  }
}