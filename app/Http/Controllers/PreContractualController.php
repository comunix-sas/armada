<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PreContractual;
use Illuminate\Support\Facades\Auth;

class PreContractualController extends Controller
{
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
            $file = $request->file('estudioPrevio');
            $fileName = $file->getClientOriginalName(); // Keep original filename

            foreach ($request->planes as $planId) {
                $estudioPrevioPath = $file->storeAs(
                    'estudios_previos',
                    $fileName,
                    'public'
                );

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

            // ... rest of the code remains the same ...
        } catch (\Exception $e) {
            // Handle exception
        }
    }
}