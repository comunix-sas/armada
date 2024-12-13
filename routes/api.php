<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authentications\LoginCover;
use App\Http\Controllers\apps\AccessPermission;
use App\Http\Controllers\apps\AccessRoles;
use App\Http\Controllers\form_validation\PlanAdquisicionController;
use App\Http\Controllers\MesController;
use App\Http\Controllers\ModalidadSeleccionController;
use App\Http\Controllers\UbicacionesController;

Route::middleware(['auth:sanctum'])->group(function () {

  Route::get('logout', [LoginCover::class, 'logout']);
  Route::post('custom-register', [LoginCover::class, 'register']);
  Route::get('permissions', [AccessPermission::class, 'getPermissions']);
  Route::get('permissions/{id}', [AccessPermission::class, 'show']);
  Route::put('permissions/{id}', [AccessPermission::class, 'update']);
  Route::delete('permissions/{id}', [AccessPermission::class, 'destroy']);
  Route::post('permissions', [AccessPermission::class, 'store']);

  Route::get('adquisitions', [PlanAdquisicionController::class, 'index']);
  Route::get('adquisitions/{id}', [PlanAdquisicionController::class, 'show']);
  Route::post('adquisitions/import', [PlanAdquisicionController::class, 'importExcel']);
  Route::post('adquisitions/validate-import', [PlanAdquisicionController::class, 'validateImport']);
  Route::put('adquisitions/{id}', [PlanAdquisicionController::class, 'update']);
  Route::delete('adquisitions/{id}', [PlanAdquisicionController::class, 'destroy']);

  Route::get('roles', [AccessRoles::class, 'getRoles']);
  Route::get('roles/{id}', [AccessRoles::class, 'show']);
  Route::post('roles', [AccessRoles::class, 'store']);
  Route::put('roles/{id}', [AccessRoles::class, 'update']);
  Route::delete('roles/{id}', [AccessRoles::class, 'destroy']);

  Route::get('meses', [MesController::class, 'index']);
  Route::get('meses/{id}', [MesController::class, 'show']);

  Route::get('modalidades-seleccion', [ModalidadSeleccionController::class, 'index']);
  Route::get('modalidades-seleccion/{id}', [ModalidadSeleccionController::class, 'show']);

  Route::get('ubicaciones', [UbicacionesController::class, 'index']);
  Route::get('ubicaciones/{id}', [UbicacionesController::class, 'show']);
});
