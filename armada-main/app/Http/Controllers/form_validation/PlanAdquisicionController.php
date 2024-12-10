<?php

namespace App\Http\Controllers\form_validation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlanAdquisicion;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class PlanAdquisicionController extends Controller
{
  public function store(Request $request)
  {
    try {
        // Obtener la última versión
        $ultimaVersion = PlanAdquisicion::max('version') ?? 0;

        $validatedData = $request->validate([
            'nombrePlan' => 'required|string|max:255',
            'modalidadPago' => 'required|integer',
            'presupuesto' => 'required|numeric',
            'currency' => 'required|string',
            'trm' => 'required|numeric',
            'cdp' => 'required|integer',
            'conversion' => 'required|numeric',
            'codigo_unspsc_id' => 'required|array',
            'codigo_unspsc_id.*' => 'exists:Sgc_codigo_unspsc,id',
            'rp' => 'required|integer',
            'modalidad_seleccion_id' => 'required|exists:Sgc_modalidades_seleccion,id',
            'mes_id' => 'required|exists:sgc_meses,id',
            'duracionContrato' => 'required|integer|min:1',
            'tipoDuracion' => 'required|integer',
            'fuenteRecursos' => 'required|string',
            'vigencia' => 'required|boolean',
            'estado' => 'required|boolean',
            'unidadContratacion' => 'required|integer',
            'ubicacion_id' => 'required|exists:Sgc_ubicaciones,id',
            'nombreResponsable' => 'required|string|max:255',
            'telefonoResponsable' => 'required|string|max:20',
            'emailResponsable' => 'required|email|max:255',
            'notasAdicionales' => 'nullable|string',
        ]);

        // Asignamos la nueva versión
        $validatedData['version'] = $ultimaVersion + 1;

        $codigosUnspsc = $request->codigo_unspsc_id;

        unset($validatedData['codigo_unspsc_id']);

        $planAdquisicion = PlanAdquisicion::create($validatedData);

        $planAdquisicion->codigosUnspsc()->attach($codigosUnspsc);

        if ($request->hasFile('adjuntos')) {
            foreach ($request->file('adjuntos') as $file) {
                $filename = $planAdquisicion->idPlan . '_' . $file->getClientOriginalName();
                $file->storeAs('public/adjuntos', $filename);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Plan de adquisición creado exitosamente',
            'data' => $planAdquisicion->load('codigosUnspsc')
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $e->errors()
        ], 422);

    } catch (\Illuminate\Database\QueryException $e) {
        $errorMessage = 'Error en la base de datos';
        if (str_contains($e->getMessage(), 'Duplicate entry')) {
            $errorMessage = 'Ya existe un registro con estos datos';
        }

        return response()->json([
            'success' => false,
            'message' => $errorMessage,
            'type' => 'database_error'
        ], 500);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al crear el plan de adquisición',
            'type' => 'general_error'
        ], 500);
    }
  }

  public function index()
  {
    $adquisiciones = PlanAdquisicion::with('codigosUnspsc')->get()->map(function($adquisicion) {
        return [
            'idPlan' => $adquisicion->idPlan,
            'nombrePlan' => $adquisicion->nombrePlan,
            'version' => $adquisicion->version,
            'modalidadPago' => $adquisicion->modalidadPago,
            'currency' => $adquisicion->currency,
            'trm' => $adquisicion->trm,
            'presupuesto' => $adquisicion->presupuesto,
            'cdp' => $adquisicion->cdp,
            'conversion' => $adquisicion->conversion,
            'rp' => $adquisicion->rp,
            'mes_id' => $adquisicion->mes_id,
            'modalidad_seleccion_id' => $adquisicion->modalidad_seleccion_id,
            'ubicacion_id' => $adquisicion->ubicacion_id,
            'codigo_unspsc_id' => $adquisicion->codigosUnspsc->pluck('codigo')->join(', '),
            'duracionContrato' => $adquisicion->duracionContrato,
            'tipoDuracion' => $adquisicion->tipoDuracion,
            'fuenteRecursos' => $adquisicion->fuenteRecursos,
            'vigencia' => $adquisicion->vigencia,
            'estado' => $adquisicion->estado,
            'unidadContratacion' => $adquisicion->unidadContratacion,
            'nombreResponsable' => $adquisicion->nombreResponsable,
            'telefonoResponsable' => $adquisicion->telefonoResponsable,
            'emailResponsable' => $adquisicion->emailResponsable,
            'notasAdicionales' => $adquisicion->notasAdicionales,
            'created_at' => $adquisicion->created_at->toDateTimeString(),
            'updated_at' => $adquisicion->updated_at->toDateTimeString(),
        ];
    });

    return response()->json(['data' => $adquisiciones]);
  }

  public function show($id)
  {
      $adquisicion = PlanAdquisicion::findOrFail($id);
      return response()->json($adquisicion);
  }

  public function update(Request $request, $id)
  {
      $adquisicion = PlanAdquisicion::findOrFail($id);
      $adquisicion->update($request->all());
      return response()->json(['message' => 'Adquisición actualizada con éxito']);
  }

  public function destroy($id)
  {
      $adquisicion = PlanAdquisicion::findOrFail($id);
      $adquisicion->delete();
      return response()->json(['message' => 'Adquisición eliminada con éxito']);
  }

  public function importExcel(Request $request)
  {
      try {
          // 1. Validación inicial del archivo
          if (!$request->hasFile('file')) {
              return response()->json([
                  'success' => false,
                  'message' => 'No se ha seleccionado ningún archivo'
              ]);
          }

          $file = $request->file('file');

          // Validar extensión del archivo
          $extension = $file->getClientOriginalExtension();
          if (!in_array($extension, ['xlsx', 'xls'])) {
              return response()->json([
                  'success' => false,
                  'message' => 'El archivo debe ser de tipo Excel (xlsx o xls)'
              ]);
          }

          // 2. Cargar y procesar el Excel
          $spreadsheet = IOFactory::load($file);
          $worksheet = $spreadsheet->getActiveSheet();
          $rows = $worksheet->toArray();

          if (count($rows) <= 1) {
              return response()->json([
                  'success' => false,
                  'message' => 'El archivo Excel está vacío o solo contiene encabezados'
              ]);
          }

          // Mapeo de nombres de columnas del Excel a nombres del sistema
          $columnMapping = [
              'Nombre del Plan' => 'nombreplan',
              'Versión' => 'version',
              'Modalidad de Pago' => 'modalidadpago',
              'TRM' => 'trm',
              'Presupuesto' => 'presupuesto',
              'CDP' => 'cdp',
              'Conversión' => 'conversion',
              'RP' => 'rp',
              'Duración del Contrato' => 'duracioncontrato',
              'Tipo de Duración' => 'tipoduracion',
              'Mes ID' => 'mes_id',
              'Modalidad Selección ID' => 'modalidad_seleccion_id',
              'Ubicación ID' => 'ubicacion_id',
              'Código UNSPSC ID' => 'codigo_unspsc_id',
              'Fuente de Recursos' => 'fuenterecursos',
              'Vigencia' => 'vigencia',
              'Estado' => 'estado',
              'Unidad de Contratación' => 'unidadcontratacion',
              'Nombre del Responsable' => 'nombreresponsable',
              'Teléfono del Responsable' => 'telefonoresponsable',
              'Email del Responsable' => 'emailresponsable',
              'Notas Adicionales' => 'notasadicionales',
              'Currency' => 'currency'
          ];

          // Obtener encabezados del Excel
          $excelHeaders = array_map('trim', $rows[0]);

          // Mapear los encabezados del Excel a los nombres del sistema
          $mappedHeaders = [];
          foreach ($excelHeaders as $index => $header) {
              if (isset($columnMapping[$header])) {
                  $mappedHeaders[$index] = $columnMapping[$header];
              } else {
                  // Si no encuentra el mapeo, usar el encabezado original
                  $mappedHeaders[$index] = strtolower(str_replace(' ', '_', $header));
              }
          }

          // Procesar filas
          $importedData = [];
          $errors = [];
          $nombresPlanes = [];

          // Primero, validamos nombres duplicados en el archivo
          for ($i = 1; $i < count($rows); $i++) {
              $rowData = [];
              foreach ($rows[$i] as $index => $value) {
                  if (isset($mappedHeaders[$index])) {
                      $rowData[$mappedHeaders[$index]] = $value;
                  }
              }

              $nombrePlan = $rowData['nombreplan'] ?? '';

              // Verificar si el nombre está vacío
              if (empty($nombrePlan)) {
                  $errors[] = "Error en la fila " . ($i + 1) . ": El nombre del plan es obligatorio";
                  continue;
              }

              // Verificar duplicados en el archivo actual
              if (in_array(strtolower($nombrePlan), $nombresPlanes)) {
                  $errors[] = "Error en la fila " . ($i + 1) . ": El nombre del plan '$nombrePlan' está duplicado en el archivo";
                  continue;
              }

              // Verificar duplicados en la base de datos
              if (PlanAdquisicion::where('nombrePlan', $nombrePlan)->exists()) {
                  $errors[] = "Error en la fila " . ($i + 1) . ": El nombre del plan '$nombrePlan' ya existe en la base de datos";
                  continue;
              }

              $nombresPlanes[] = strtolower($nombrePlan);
          }

          // Si hay errores, detener la importación
          if (!empty($errors)) {
              return response()->json([
                  'success' => false,
                  'message' => 'No se pudo procesar el archivo debido a las siguientes validaciones:',
                  'errors' => $errors,
                  'type' => 'validation_error',
                  'summary' => count($errors) . ' error(es) encontrado(s). Por favor, revise los nombres de los planes y asegúrese de que no estén duplicados.'
              ], 422);
          }

          // Si no hay errores, proceder con la importación
          for ($i = 1; $i < count($rows); $i++) {
              $rowData = [];
              foreach ($rows[$i] as $index => $value) {
                  if (isset($mappedHeaders[$index])) {
                      $rowData[$mappedHeaders[$index]] = $value;
                  }
              }

              try {
                  // Validar y crear el registro
                  $validatedData = [
                      'nombrePlan' => $rowData['nombreplan'],
                      'version' => (int)($rowData['version'] ?? 0),
                      'modalidadPago' => (int)($rowData['modalidadpago'] ?? 0),
                      'presupuesto' => (float)($rowData['presupuesto'] ?? 0),
                      'currency' => $rowData['currency'] ?? 'COP',
                      'trm' => (float)($rowData['trm'] ?? 0),
                      'cdp' => $rowData['cdp'] ? (int)$rowData['cdp'] : null,
                      'conversion' => (float)($rowData['conversion'] ?? 0),
                      'codigo_unspsc_id' => (int)($rowData['codigo_unspsc_id'] ?? 0),
                      'rp' => (int)($rowData['rp'] ?? 0),
                      'modalidad_seleccion_id' => (int)($rowData['modalidad_seleccion_id'] ?? 0),
                      'mes_id' => (int)($rowData['mes_id'] ?? 0),
                      'duracionContrato' => (int)($rowData['duracioncontrato'] ?? 0),
                      'tipoDuracion' => (int)($rowData['tipoduracion'] ?? 0),
                      'fuenteRecursos' => $rowData['fuenterecursos'] ?? '',
                      'vigencia' => $rowData['vigencia'] ?? false,
                      'estado' => $rowData['estado'] ?? false,
                      'unidadContratacion' => (int)($rowData['unidadcontratacion'] ?? 0),
                      'ubicacion_id' => (int)($rowData['ubicacion_id'] ?? 0),
                      'nombreResponsable' => $rowData['nombreresponsable'] ?? '',
                      'telefonoResponsable' => $rowData['telefonoresponsable'] ?? '',
                      'emailResponsable' => $rowData['emailresponsable'] ?? '',
                      'notasAdicionales' => $rowData['notasadicionales'] ?? null
                  ];

                  $planAdquisicion = PlanAdquisicion::create($validatedData);
                  $importedData[] = $planAdquisicion;
              } catch (\Exception $e) {
                  $errors[] = "Error en la fila " . ($i + 1) . ": " . $e->getMessage();
              }
          }

          return response()->json([
              'success' => count($errors) === 0,
              'message' => count($errors) === 0 ? 'Importación exitosa' : 'La importación se completó con errores',
              'data' => $importedData,
              'errors' => $errors
          ]);

      } catch (\Exception $e) {
          return response()->json([
              'success' => false,
              'message' => 'Error en la importación: ' . $e->getMessage()
          ], 500);
      }
  }



}
