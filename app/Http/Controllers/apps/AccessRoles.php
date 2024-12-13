<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

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
        $role->name = $request->input('name');
        $role->save();

        return response()->json(['status' => 'success', 'message' => 'Rol actualizado correctamente']);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $role]);
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

}
