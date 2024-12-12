<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ModuleManagementController extends Controller
{
    protected $menuPath;

    public function __construct()
    {
        $this->menuPath = resource_path('menu/verticalMenu.json');
    }

    public function index()
    {
        $menuData = json_decode(File::get($this->menuPath));
        $modules = collect($menuData->menu)->map(function($item) {
            if (isset($item->menuHeader)) {
                return [
                    'type' => 'header',
                    'name' => $item->menuHeader,
                    'disabled' => $item->disabled ?? false,
                    'icon' => $item->icon ?? null,
                    'description' => $item->description ?? null
                ];
            }
            return [
                'type' => 'item',
                'name' => $item->name ?? '',
                'disabled' => $item->disabled ?? false,
                'icon' => $item->icon ?? null,
                'description' => $item->description ?? null
            ];
        });

        return view('content.admin.module-management', compact('modules'));
    }

    public function toggleModule(Request $request)
    {
        $request->validate([
            'moduleName' => 'required|string',
            'disabled' => 'required|boolean'
        ]);

        $menuData = json_decode(File::get($this->menuPath), true);

        foreach ($menuData['menu'] as &$item) {
            if (
                (isset($item['menuHeader']) && $item['menuHeader'] === $request->moduleName) ||
                (isset($item['name']) && $item['name'] === $request->moduleName)
            ) {
                $item['disabled'] = $request->disabled;
                // Si es un módulo con submenu, también actualiza sus items
                if (isset($item['submenu'])) {
                    foreach ($item['submenu'] as &$subitem) {
                        $subitem['disabled'] = $request->disabled;
                    }
                }
                break;
            }
        }

        File::put($this->menuPath, json_encode($menuData, JSON_PRETTY_PRINT));

        return response()->json([
            'success' => true,
            'message' => 'Módulo actualizado correctamente'
        ]);
    }

    public function getMenu()
    {
        $menuData = json_decode(File::get($this->menuPath));
        return response()->json($menuData);
    }
}
