@php
    use Illuminate\Support\Facades\Route;
    $configData = Helper::appClasses();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- ! Hide app brand if navbar-full -->
    @if (!isset($navbarFull))
        <div class="app-brand demo">
            <a href="{{ url('/') }}" class="app-brand-link">
                <img src="{{ asset('assets/img/illustrations/logo-armada-pequeño.png') }}" alt="logo"
                    style="max-width: 150px;">
                <div class="d-flex flex-column ms-2">

                    <span class="app-brand-text demo menu-text fw-bold">{{ config('variables.templateName') }}</span>
                    <small class="text-muted ms-3" style="font-size: 0.75rem; margin-top: 3px;">Armada Nacional de
                        Colombia</small>
                </div>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
                <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
            </a>
        </div>
    @endif

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @if(isset($menuData[0]) && isset($menuData[0]->menu))
            @foreach ($menuData[0]->menu as $menu)
                {{-- Si es un encabezado --}}
                @if (isset($menu->menuHeader))
                    <li class="menu-header small {{ $menu->disabled ?? false ? 'disabled' : '' }}">
                        <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
                    </li>
                @else
                    {{-- active menu method --}}
                    @php
                        $activeClass = null;
                        $currentRouteName = Route::currentRouteName();

                        if ($currentRouteName === $menu->slug) {
                            $activeClass = 'active';
                        } elseif (isset($menu->submenu)) {
                            if (gettype($menu->slug) === 'array') {
                                foreach ($menu->slug as $slug) {
                                    if (str_contains($currentRouteName, $slug) and strpos($currentRouteName, $slug) === 0) {
                                        $activeClass = 'active open';
                                    }
                                }
                            } else {
                                if (
                                    str_contains($currentRouteName, $menu->slug) and
                                    strpos($currentRouteName, $menu->slug) === 0
                                ) {
                                    $activeClass = 'active open';
                                }
                            }
                        }
                    @endphp

                    {{-- Menu item --}}
                    <li class="menu-item {{ $activeClass }} {{ $menu->disabled ?? false ? 'disabled' : '' }}">
                        <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
                            class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                            @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif
                            @if($menu->disabled ?? false) style="pointer-events: none; opacity: 0.6;" @endif>
                            @isset($menu->icon)
                                <i class="{{ $menu->icon }}"></i>
                            @endisset
                            <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
                        </a>

                        {{-- Submenu --}}
                        @if(isset($menu->submenu) && !($menu->disabled ?? false))
                            <ul class="menu-sub">
                                @foreach ($menu->submenu as $submenu)
                                    @if(!($submenu->disabled ?? false))
                                        <li class="menu-item {{ $submenu->disabled ?? false ? 'disabled' : '' }}">
                                            <a href="{{ isset($submenu->url) ? url($submenu->url) : 'javascript:void(0);' }}"
                                                class="menu-link"
                                                @if (isset($submenu->target) and !empty($submenu->target)) target="_blank" @endif
                                                @if($submenu->disabled ?? false) style="pointer-events: none; opacity: 0.6;" @endif>
                                                @isset($submenu->icon)
                                                    <i class="{{ $submenu->icon }}"></i>
                                                @endisset
                                                <div>{{ isset($submenu->name) ? __($submenu->name) : '' }}</div>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endif
            @endforeach
        @else
            <li class="menu-item">
                <a href="{{ url('/') }}" class="menu-link">
                    <i class="ti ti-home"></i>
                    <div>Inicio</div>
                </a>
            </li>
        @endif
    </ul>

    <div class="text-center p-3">
        <small class="text-muted menu-expanded-only">Protegemos el Azul de la bandera</small>

        <!-- Opción con imagen -->
        <img src="{{ asset('assets/img/illustrations/colombia-icono.png') }}" class="menu-collapsed-only"
            alt="Bandera Colombia" style="height: 2rem; display: none; margin: 0 auto;">
        <link rel="stylesheet" href="{{ asset('assets/css/menu-collapse.css') }}">
    </div>

    <style>
        .menu-header.disabled,
        .menu-item.disabled {
            opacity: 0.6;
            pointer-events: none;
        }

        /* O si prefieres ocultarlos completamente */
        .menu-header.disabled,
        .menu-item.disabled {
            display: none;
        }

        /* Si quieres mantener visible pero deshabilitar la interacción */
        .menu-item.disabled a {
            cursor: not-allowed;
        }
    </style>
</aside>
