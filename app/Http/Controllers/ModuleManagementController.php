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
                    'description' => $item->description ?? null,
                    'isMainMenu' => true
                ];
            }

            $moduleData = [
                'type' => 'item',
                'name' => $item->name ?? '',
                'disabled' => $item->disabled ?? false,
                'icon' => $item->icon ?? null,
                'description' => $item->description ?? null,
                'isMainMenu' => !isset($item->submenu)
            ];

            // Si tiene submenús, los incluimos con sus iconos
            if (isset($item->submenu)) {
                $moduleData['submenu'] = collect($item->submenu)->map(function($subitem) {
                    return [
                        'type' => 'subitem',
                        'name' => $subitem->name ?? '',
                        'disabled' => $subitem->disabled ?? false,
                        'icon' => $subitem->icon ?? null,
                        'description' => $subitem->description ?? null,
                        'isMainMenu' => false
                    ];
                })->toArray();
            }

            return $moduleData;
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
        $moduleFound = false;

        // Función recursiva para buscar y actualizar módulos y submenús
        $updateModule = function(&$items) use ($request, &$moduleFound) {
            foreach ($items as &$item) {
                // Verificar si es un menú principal o un header
                if ((isset($item['menuHeader']) && $item['menuHeader'] === $request->moduleName) ||
                    (isset($item['name']) && $item['name'] === $request->moduleName)) {
                    $item['disabled'] = $request->disabled;
                    $moduleFound = true;

                    // Si tiene submenu, actualizar todos los subitems
                    if (isset($item['submenu'])) {
                        foreach ($item['submenu'] as &$subitem) {
                            $subitem['disabled'] = $request->disabled;
                        }
                    }
                    break;
                }

                // Buscar en submenús
                if (isset($item['submenu'])) {
                    foreach ($item['submenu'] as &$subitem) {
                        if (isset($subitem['name']) && $subitem['name'] === $request->moduleName) {
                            $subitem['disabled'] = $request->disabled;
                            $moduleFound = true;
                            break 2;
                        }
                    }
                }
            }
        };

        // Aplicar la función de actualización
        $updateModule($menuData['menu']);

        if (!$moduleFound) {
            return response()->json([
                'success' => false,
                'message' => 'Módulo no encontrado'
            ], 404);
        }

        // Guardar los cambios en el archivo
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
