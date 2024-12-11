<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\users\UserManagement;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\dashboard\Crm;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\layouts\CollapsedMenu;
use App\Http\Controllers\layouts\ContentNavbar;
use App\Http\Controllers\layouts\ContentNavSidebar;
use App\Http\Controllers\layouts\Horizontal;
use App\Http\Controllers\layouts\Vertical;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\front_pages\Landing;
use App\Http\Controllers\front_pages\Pricing;
use App\Http\Controllers\front_pages\Payment;
use App\Http\Controllers\front_pages\Checkout;
use App\Http\Controllers\front_pages\HelpCenter;
use App\Http\Controllers\front_pages\HelpCenterArticle;
use App\Http\Controllers\apps\UserList;
use App\Http\Controllers\apps\UserViewAccount;
use App\Http\Controllers\apps\UserViewSecurity;
use App\Http\Controllers\apps\UserViewBilling;
use App\Http\Controllers\apps\UserViewNotifications;
use App\Http\Controllers\apps\UserViewConnections;
use App\Http\Controllers\apps\AccessRoles;
use App\Http\Controllers\apps\AccessPermission;
use App\Http\Controllers\authentications\LoginCover;
use App\Http\Controllers\authentications\ResetPasswordCover;
use App\Http\Controllers\authentications\ForgotPasswordCover;
use App\Http\Controllers\form_validation\Validation;
use App\Http\Controllers\tables\DatatableAdvanced;
use App\Http\Controllers\form_validation\PlanAdquisicionController;
use App\Http\Controllers\precontractual\PreContractualController;
use App\Http\Controllers\TestApiSecopIIController;
use App\Http\Controllers\ModuleManagementController;

Route::get('/login', [LoginCover::class, 'index'])->name('login');
Route::post('/login', [LoginCover::class, 'login']);

Route::get('/auth/forgot-password-cover', [ForgotPasswordCover::class, 'index'])->name('auth-forgot-password-cover');
Route::post('/auth/forgot-password-cover', [ForgotPasswordCover::class, 'sendResetLinkEmail']);
Route::get('/auth/reset-password-cover', [ResetPasswordCover::class, 'index'])->name('auth-reset-password-cover');
Route::post('/auth/reset-password-cover', [ResetPasswordCover::class, 'reset']);

Route::get('/tables/datatables-advanced', [DatatableAdvanced::class, 'index'])->name('tables-datatables-advanced');

Route::middleware(['auth'])->group(function () {

  Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');
  Route::get('/dashboard/analytics', [Analytics::class, 'index'])->name('dashboard-analytics-alt'); // Cambié el nombre aquí
  Route::get('/dashboard/crm', [Crm::class, 'index'])->name('dashboard-crm');

  // layout
  Route::get('/layouts/collapsed-menu', [CollapsedMenu::class, 'index'])->name('layouts-collapsed-menu');
  Route::get('/layouts/content-navbar', [ContentNavbar::class, 'index'])->name('layouts-content-navbar');
  Route::get('/layouts/content-nav-sidebar', [ContentNavSidebar::class, 'index'])->name('layouts-content-nav-sidebar');
  Route::get('/layouts/horizontal', [Horizontal::class, 'index'])->name('layouts-horizontal'); // Cambié el nombre aquí
  Route::get('/layouts/vertical', [Vertical::class, 'index'])->name('alyouts-vertical');
  Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
  Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
  Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
  Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
  Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

  // Front Pages
  Route::get('/front-pages/landing', [Landing::class, 'index'])->name('front-pages-landing');
  Route::get('/front-pages/pricing', [Pricing::class, 'index'])->name('front-pages-pricing');
  Route::get('/front-pages/payment', [Payment::class, 'index'])->name('front-pages-payment');
  Route::get('/front-pages/checkout', [Checkout::class, 'index'])->name('front-pages-checkout');
  Route::get('/front-pages/help-center', [HelpCenter::class, 'index'])->name('front-pages-help-center');
  Route::get('/front-pages/help-center-article', [HelpCenterArticle::class, 'index'])->name('front-pages-help-center-article');


  Route::get('/app/user/list', [UserList::class, 'index'])->name('app-user-list');
  Route::get('/app/user/view/account', [UserViewAccount::class, 'index'])->name('app-user-view-account');
  Route::get('/app/user/view/security', [UserViewSecurity::class, 'index'])->name('app-user-view-security');
  Route::get('/app/user/view/billing', [UserViewBilling::class, 'index'])->name('app-user-view-billing');
  Route::get('/app/user/view/notifications', [UserViewNotifications::class, 'index'])->name('app-user-view-notifications');
  Route::get('/app/user/view/connections', [UserViewConnections::class, 'index'])->name('app-user-view-connections');

  Route::get('/app/access-roles', [AccessRoles::class, 'index'])->name('app-access-roles');
  Route::get('/app/access-permission', [AccessPermission::class, 'index'])->name('app-access-permission');

  // laravel example
  Route::get('/laravel/user-management', [UserManagement::class, 'UserManagement'])->name('users-user-management');
  Route::resource('/user-list', UserManagement::class);
  Route::get('/users/create', [UserManagement::class, 'create'])->name('users.create');

  Route::post('/logout', [LoginCover::class, 'logout'])->name('logout');
  Route::get('/form/validation', [Validation::class, 'index'])->name('form-validation');
  Route::get('/search-codigo', [Validation::class, 'searchCodigo']);
  Route::post('/plan-adquisicion', [PlanAdquisicionController::class, 'store'])->name('PlanAdquisicion.store');

  Route::get('/precontractual', [PreContractualController::class, 'index'])->name('precontractual.index');
  Route::post('/precontractual', [PreContractualController::class, 'store'])->name('precontractual.store');

  Route::prefix('precontractual')->group(function () {

    Route::get('/validar-plan/{id}', [PreContractualController::class, 'validarPlanAdquisicion']);
    Route::put('/estudio-previo/{id}', [PreContractualController::class, 'actualizarEstudioPrevio']);
    Route::post('/aprobar-estudio/{id}', [PreContractualController::class, 'aprobarEstudioPrevio']);
    Route::post('/iniciar-proceso/{id}', [PreContractualController::class, 'iniciarProcesoContratacion']);
    Route::get('/seguimiento/{id}', [PreContractualController::class, 'seguimientoProceso']);
    Route::get('/auditoria/{id}', [PreContractualController::class, 'auditoriaProcess']);
    Route::get('/planes-validacion', [PreContractualController::class, 'obtenerPlanesValidacion']);
  });

  Route::get('/test', [TestApiSecopIIController::class, 'index']);
  Route::post('/send-reset-password', [LoginCover::class, 'sendPasswordResetEmail']);
  Route::post('/reset-password', [LoginCover::class, 'resetPassword']);

  Route::get('/admin/module-management', [ModuleManagementController::class, 'index'])->name('admin-module-management');
  Route::post('/admin/module-management/toggle', [ModuleManagementController::class, 'toggleModule']);
});
