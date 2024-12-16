<?php

namespace App\Http\Controllers\form_validation;

use App\Http\Controllers\Controller;
use App\Models\CodigoUnspsc;
use Illuminate\Http\Request;
use App\Models\Mes;
use App\Models\ModalidadSeleccion;
use App\Models\Ubicaciones;



class Validation extends Controller
{
  public function index()
{
    $meses = Mes::all();
    $modalidades = ModalidadSeleccion::all();
    $ubicaciones = Ubicaciones::all();
    return view('content.form-validation.form-validation', compact('meses', 'modalidades', 'ubicaciones'));
}

public function searchCodigo(Request $request)
{
    $search = $request->input('q');

    $results = CodigoUnspsc::where('descripcion', 'like', '%' . $search . '%')->paginate(1000);

    return response()->json([
        'results' => $results->items(),
        'pagination' => ['more' => $results->hasMorePages()]
    ]);
}



}
