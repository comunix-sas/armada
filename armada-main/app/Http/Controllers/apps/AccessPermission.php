<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AccessPermission extends Controller
{
  public function index()
  {
    return view('content.apps.app-access-permission');
  }

  public function getPermissions()
  {
    $permissions = Permission::all()->map(function ($permission) {
      return [
        'id' => $permission->id,
        'name' => $permission->name,
        'created_date' => $permission->created_at->toDateString(),
      ];
    });

    return response()->json(['data' => $permissions]);
  }

  public function store(Request $request)
  {
    try {
      DB::beginTransaction();

      $request->validate([
        'name' => 'required|string|unique:permissions,name'
      ]);

      $permission = Permission::create([
        'name' => $request->input('name'),
        'guard_name' => 'web'
      ]);

      DB::commit();

      return response()->json([
        'status' => 'success',
        'message' => 'Permiso creado exitosamente'
      ]);
    } catch (\Exception $e) {
      DB::rollBack();

      return response()->json([
        'status' => 'error',
        'message' => 'Error al crear el permiso: ' . $e->getMessage()
      ], 500);
    }
  }

  public function update(Request $request, $id): JsonResponse
  {
    try {
      DB::beginTransaction();

      $permission = Permission::findOrFail($id);

      $validatedData = $request->validate([
        'name' => 'required|string|unique:permissions,name,' . $id . '|max:255',
        'description' => 'nullable|string|max:255',
        'guard_name' => 'nullable|string|max:255'
      ]);

      $permission->update([
        'name' => $validatedData['name'],
        'description' => $validatedData['description'] ?? $permission->description,
        'guard_name' => $validatedData['guard_name'] ?? $permission->guard_name
      ]);

      DB::commit();

      return response()->json([
        'status' => 'success',
        'message' => 'Permiso actualizado exitosamente',
        'data' => $permission
      ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
      DB::rollBack();
      return response()->json([
        'status' => 'error',
        'message' => 'Error de validación',
        'errors' => $e->errors()
      ], 422);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'status' => 'error',
        'message' => 'Error al actualizar el permiso',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function destroy($id)
  {

    $permission = Permission::findOrFail($id);
    $permission->delete();

    return response()->json(['status' => 'success', 'message' => 'Rol eliminado correctamente']);
  }

  public function show($id): JsonResponse
  {
    try {
      $permission = Permission::with('roles')->findOrFail($id);
      return response()->json([
        'status' => 'success',
        'data' => [
          'id' => $permission->id,
          'name' => $permission->name,
          'created_date' => $permission->created_at->toDateString(),
          'roles' => $permission->roles->pluck('name')->toArray()
        ]
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Error al obtener el permiso',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
