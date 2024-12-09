<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\users\UserManagement;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\dashboard\Crm;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\layouts\CollapsedMenu;
use App\Http\Controllers\layouts\ContentNavbar;
use App\Http\Controllers\layouts\ContentNavSidebar;
use App\Http\Controllers\layouts\NavbarFull;
use App\Http\Controllers\layouts\NavbarFullSidebar;
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
use App\Http\Controllers\apps\Email;
use App\Http\Controllers\apps\Chat;
use App\Http\Controllers\apps\Calendar;
use App\Http\Controllers\apps\Kanban;
use App\Http\Controllers\apps\EcommerceDashboard;
use App\Http\Controllers\apps\EcommerceProductList;
use App\Http\Controllers\apps\EcommerceProductAdd;
use App\Http\Controllers\apps\EcommerceProductCategory;
use App\Http\Controllers\apps\EcommerceOrderList;
use App\Http\Controllers\apps\EcommerceOrderDetails;
use App\Http\Controllers\apps\EcommerceCustomerAll;
use App\Http\Controllers\apps\EcommerceCustomerDetailsOverview;
use App\Http\Controllers\apps\EcommerceCustomerDetailsSecurity;
use App\Http\Controllers\apps\EcommerceCustomerDetailsBilling;
use App\Http\Controllers\apps\EcommerceCustomerDetailsNotifications;
use App\Http\Controllers\apps\EcommerceManageReviews;
use App\Http\Controllers\apps\EcommerceReferrals;
use App\Http\Controllers\apps\EcommerceSettingsDetails;
use App\Http\Controllers\apps\EcommerceSettingsPayments;
use App\Http\Controllers\apps\EcommerceSettingsCheckout;
use App\Http\Controllers\apps\EcommerceSettingsShipping;
use App\Http\Controllers\apps\EcommerceSettingsLocations;
use App\Http\Controllers\apps\EcommerceSettingsNotifications;
use App\Http\Controllers\apps\AcademyDashboard;
use App\Http\Controllers\apps\AcademyCourse;
use App\Http\Controllers\apps\AcademyCourseDetails;
use App\Http\Controllers\apps\LogisticsDashboard;
use App\Http\Controllers\apps\LogisticsFleet;
use App\Http\Controllers\apps\InvoiceList;
use App\Http\Controllers\apps\InvoicePreview;
use App\Http\Controllers\apps\InvoicePrint;
use App\Http\Controllers\apps\InvoiceEdit;
use App\Http\Controllers\apps\InvoiceAdd;
use App\Http\Controllers\apps\UserList;
use App\Http\Controllers\apps\UserViewAccount;
use App\Http\Controllers\apps\UserViewSecurity;
use App\Http\Controllers\apps\UserViewBilling;
use App\Http\Controllers\apps\UserViewNotifications;
use App\Http\Controllers\apps\UserViewConnections;
use App\Http\Controllers\apps\AccessRoles;
use App\Http\Controllers\apps\AccessPermission;
use App\Http\Controllers\pages\UserProfile;
use App\Http\Controllers\pages\UserTeams;
use App\Http\Controllers\pages\UserProjects;
use App\Http\Controllers\pages\UserConnections;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsSecurity;
use App\Http\Controllers\pages\AccountSettingsBilling;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\Faq;
use App\Http\Controllers\pages\Pricing as PagesPricing;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\pages\MiscComingSoon;
use App\Http\Controllers\pages\MiscNotAuthorized;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\LoginCover;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\RegisterCover;
use App\Http\Controllers\authentications\RegisterMultiSteps;
use App\Http\Controllers\authentications\VerifyEmailBasic;
use App\Http\Controllers\authentications\VerifyEmailCover;
use App\Http\Controllers\authentications\ResetPasswordBasic;
use App\Http\Controllers\authentications\ResetPasswordCover;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\authentications\ForgotPasswordCover;
use App\Http\Controllers\authentications\TwoStepsBasic;
use App\Http\Controllers\authentications\TwoStepsCover;
use App\Http\Controllers\wizard_example\Checkout as WizardCheckout;
use App\Http\Controllers\wizard_example\PropertyListing;
use App\Http\Controllers\wizard_example\CreateDeal;
use App\Http\Controllers\modal\ModalExample;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\cards\CardAdvance;
use App\Http\Controllers\cards\CardStatistics;
use App\Http\Controllers\cards\CardAnalytics;
use App\Http\Controllers\cards\CardGamifications;
use App\Http\Controllers\cards\CardActions;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\Avatar;
use App\Http\Controllers\extended_ui\BlockUI;
use App\Http\Controllers\extended_ui\DragAndDrop;
use App\Http\Controllers\extended_ui\MediaPlayer;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\StarRatings;
use App\Http\Controllers\extended_ui\SweetAlert;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\extended_ui\TimelineBasic;
use App\Http\Controllers\extended_ui\TimelineFullscreen;
use App\Http\Controllers\extended_ui\Tour;
use App\Http\Controllers\extended_ui\Treeview;
use App\Http\Controllers\extended_ui\Misc;
use App\Http\Controllers\icons\Tabler;
use App\Http\Controllers\icons\FontAwesome;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_elements\CustomOptions;
use App\Http\Controllers\form_elements\Editors;
use App\Http\Controllers\form_elements\FileUpload;
use App\Http\Controllers\form_elements\Picker;
use App\Http\Controllers\form_elements\Selects;
use App\Http\Controllers\form_elements\Sliders;
use App\Http\Controllers\form_elements\Switches;
use App\Http\Controllers\form_elements\Extras;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\form_layouts\StickyActions;
use App\Http\Controllers\form_wizard\Numbered as FormWizardNumbered;
use App\Http\Controllers\form_wizard\Icons as FormWizardIcons;
use App\Http\Controllers\form_validation\Validation;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\tables\DatatableBasic;
use App\Http\Controllers\tables\DatatableAdvanced;
use App\Http\Controllers\tables\DatatableExtensions;
use App\Http\Controllers\form_validation\PlanAdquisicionController;
use App\Http\Controllers\precontractual\PreContractualController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\TestApiSecopIIController;
use App\Http\Controllers\precontractual\ValidacionPlanController;



Route::get('/login', [LoginCover::class, 'index'])->name('login');
Route::post('/login', [LoginCover::class, 'login']);

Route::get('/auth/forgot-password-cover', [ForgotPasswordCover::class, 'index'])->name('auth-forgot-password-cover');
Route::post('/auth/forgot-password-cover', [ForgotPasswordCover::class, 'sendResetLinkEmail']);
Route::get('/auth/reset-password-cover', [ResetPasswordCover::class, 'index'])->name('auth-reset-password-cover');
Route::post('/auth/reset-password-cover', [ResetPasswordCover::class, 'reset']);

// table
Route::get('/tables/datatables-advanced', [DatatableAdvanced::class, 'index'])->name('tables-datatables-advanced');

Route::middleware(['auth'])->group(function () {


  Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');
  Route::get('/dashboard/analytics', [Analytics::class, 'index'])->name('dashboard-analytics-alt'); // Cambié el nombre aquí
  Route::get('/dashboard/crm', [Crm::class, 'index'])->name('dashboard-crm');


  // form wizards
  Route::get('/form/wizard-numbered', [FormWizardNumbered::class, 'index'])->name('form-wizard-numbered');
  Route::get('/form/wizard-icons', [FormWizardIcons::class, 'index'])->name('form-wizard-icons');
  // Route::get('/form/validation', [Validation::class, 'index'])->name('form-validation');

  // locale
  Route::get('/lang/{locale}', [LanguageController::class, 'swap']);

  // layout
  Route::get('/layouts/collapsed-menu', [CollapsedMenu::class, 'index'])->name('layouts-collapsed-menu');
  Route::get('/layouts/content-navbar', [ContentNavbar::class, 'index'])->name('layouts-content-navbar');
  Route::get('/layouts/content-nav-sidebar', [ContentNavSidebar::class, 'index'])->name('layouts-content-nav-sidebar');
  // Route::get('/layouts/navbar-full', [NavbarFull::class, 'index'])->name('layouts-navbar-full');
  // Route::get('/layouts/navbar-full-sidebar', [NavbarFullSidebar::class, 'index'])->name('layouts-navbar-full-sidebar');
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

  // apps
  // Route::get('/app/email', [Email::class, 'index'])->name('app-email');
  // Route::get('/app/calendar', [Calendar::class, 'index'])->name('app-calendar');
  // Route::get('/app/kanban', [Kanban::class, 'index'])->name('app-kanban');
  // Route::get('/app/ecommerce/dashboard', [EcommerceDashboard::class, 'index'])->name('app-ecommerce-dashboard');
  // Route::get('/app/ecommerce/product/list', [EcommerceProductList::class, 'index'])->name('app-ecommerce-product-list');
  // Route::get('/app/ecommerce/product/add', [EcommerceProductAdd::class, 'index'])->name('app-ecommerce-product-add');
  // Route::get('/app/ecommerce/product/category', [EcommerceProductCategory::class, 'index'])->name('app-ecommerce-product-category');
  // Route::get('/app/ecommerce/order/list', [EcommerceOrderList::class, 'index'])->name('app-ecommerce-order-list');
  // Route::get('/app/ecommerce/order/details', [EcommerceOrderDetails::class, 'index'])->name('app-ecommerce-order-details');
  // Route::get('/app/ecommerce/customer/all', [EcommerceCustomerAll::class, 'index'])->name('app-ecommerce-customer-all');
  // Route::get('/app/ecommerce/customer/details/overview', [EcommerceCustomerDetailsOverview::class, 'index'])->name('app-ecommerce-customer-details-overview');
  // Route::get('/app/ecommerce/customer/details/security', [EcommerceCustomerDetailsSecurity::class, 'index'])->name('app-ecommerce-customer-details-security');
  // Route::get('/app/ecommerce/customer/details/billing', [EcommerceCustomerDetailsBilling::class, 'index'])->name('app-ecommerce-customer-details-billing');
  // Route::get('/app/ecommerce/customer/details/notifications', [EcommerceCustomerDetailsNotifications::class, 'index'])->name('app-ecommerce-customer-details-notifications');
  // Route::get('/app/ecommerce/manage/reviews', [EcommerceManageReviews::class, 'index'])->name('app-ecommerce-manage-reviews');
  // Route::get('/app/ecommerce/referrals', [EcommerceReferrals::class, 'index'])->name('app-ecommerce-referrals');
  // Route::get('/app/ecommerce/settings/details', [EcommerceSettingsDetails::class, 'index'])->name('app-ecommerce-settings-details');
  // Route::get('/app/ecommerce/settings/payments', [EcommerceSettingsPayments::class, 'index'])->name('app-ecommerce-settings-payments');
  // Route::get('/app/ecommerce/settings/checkout', [EcommerceSettingsCheckout::class, 'index'])->name('app-ecommerce-settings-checkout');
  // Route::get('/app/ecommerce/settings/shipping', [EcommerceSettingsShipping::class, 'index'])->name('app-ecommerce-settings-shipping');
  // Route::get('/app/ecommerce/settings/locations', [EcommerceSettingsLocations::class, 'index'])->name('app-ecommerce-settings-locations');
  // Route::get('/app/ecommerce/settings/notifications', [EcommerceSettingsNotifications::class, 'index'])->name('app-ecommerce-settings-notifications');
  // Route::get('/app/academy/dashboard', [AcademyDashboard::class, 'index'])->name('app-academy-dashboard');
  // Route::get('/app/academy/course', [AcademyCourse::class, 'index'])->name('app-academy-course');
  // Route::get('/app/academy/course-details', [AcademyCourseDetails::class, 'index'])->name('app-academy-course-details');
  // Route::get('/app/logistics/dashboard', [LogisticsDashboard::class, 'index'])->name('app-logistics-dashboard');
  // Route::get('/app/logistics/fleet', [LogisticsFleet::class, 'index'])->name('app-logistics-fleet');
  // Route::get('/app/invoice/list', [InvoiceList::class, 'index'])->name('app-invoice-list');
  // Route::get('/app/invoice/preview', [InvoicePreview::class, 'index'])->name('app-invoice-preview');
  // Route::get('/app/invoice/print', [InvoicePrint::class, 'index'])->name('app-invoice-print');
  // Route::get('/app/invoice/edit', [InvoiceEdit::class, 'index'])->name('app-invoice-edit');
  // Route::get('/app/invoice/add', [InvoiceAdd::class, 'index'])->name('app-invoice-add');
  Route::get('/app/user/list', [UserList::class, 'index'])->name('app-user-list');
  Route::get('/app/user/view/account', [UserViewAccount::class, 'index'])->name('app-user-view-account');
  Route::get('/app/user/view/security', [UserViewSecurity::class, 'index'])->name('app-user-view-security');
  Route::get('/app/user/view/billing', [UserViewBilling::class, 'index'])->name('app-user-view-billing');
  Route::get('/app/user/view/notifications', [UserViewNotifications::class, 'index'])->name('app-user-view-notifications');
  Route::get('/app/user/view/connections', [UserViewConnections::class, 'index'])->name('app-user-view-connections');

  //---------------------------------------------------------

  // pages
  Route::get('/pages/profile-user', [UserProfile::class, 'index'])->name('pages-profile-user');
  Route::get('/pages/profile-teams', [UserTeams::class, 'index'])->name('pages-profile-teams');
  Route::get('/pages/profile-projects', [UserProjects::class, 'index'])->name('pages-profile-projects');
  Route::get('/pages/profile-connections', [UserConnections::class, 'index'])->name('pages-profile-connections');
  Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
  Route::get('/pages/account-settings-security', [AccountSettingsSecurity::class, 'index'])->name('pages-account-settings-security');
  Route::get('/pages/account-settings-billing', [AccountSettingsBilling::class, 'index'])->name('pages-account-settings-billing');
  Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
  Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
  Route::get('/pages/faq', [Faq::class, 'index'])->name('pages-faq');
  Route::get('/pages/pricing', [PagesPricing::class, 'index'])->name('pages-pricing');

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

  // Nuevas rutas precontractual
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

  Route::post('user-list/delete-multiple', [UserManagement::class, 'deleteMultiple'])->name('user.deleteMultiple');

});
