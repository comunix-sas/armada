<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AccessRoles extends Controller
{
    public function index()
    {
        return view('content.apps.app-access-roles');
    }

    public function getRoles()
    {
        $roles = Role::with('permissions')->get()->map(function($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name'),
                'created_date' => $role->created_at->toDateString(),
            ];
        });

        return response()->json(['data' => $roles]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // Actualizar el nombre del rol
        $role->name = $request->input('name');
        $role->save();

        // Sincronizar los permisos
        if ($request->has('permissions')) {
            $permissions = $request->input('permissions');
            $role->syncPermissions($permissions);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Rol y permisos actualizados correctamente'
        ]);
    }
    public function show($id)
    {
        $role = Role::findOrFail($id);
        $allPermissions = Permission::all();

        $permissions = $allPermissions->map(function($permission) use ($role) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'assigned' => $role->hasPermissionTo($permission->name)
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $permissions
            ]
        ]);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['status' => 'success', 'message' => 'Rol eliminado correctamente']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create([
            'name' => $request->input('name'),
            'guard_name' => 'web'
        ]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('name', $request->permissions)->get();
            $role->syncPermissions($permissions);
        }

        return response()->json(['status' => 'success', 'message' => 'Rol creado correctamente']);
    }

    public function getPermissions()
    {
        $permissions = Permission::all()->map(function($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name
            ];
        });

        return response()->json(['data' => $permissions]);
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => $user->roles->first()->id ?? null,
            'role' => $user->roles->first()->name ?? null
        ]);
    }

}
