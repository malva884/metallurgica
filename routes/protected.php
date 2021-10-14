<?php

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\AdvisoryController;
use App\Http\Controllers\AdvisoryHeadController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\GenericController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\MaterialsCostController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\WorkflowCategoryController;
use App\Http\Controllers\WorkflowController;
use App\Http\Controllers\WorkStatusController;
use App\Http\Controllers\WorkStatusHeadController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LanguageController;


Route::get('/', 'StaterkitController@home')->name('home');
//Route::get('home', 'StaterkitController@home')->name('home');
// Route Components
Route::get('layouts/collapsed-menu', 'StaterkitController@collapsed_menu')->name('collapsed-menu');
Route::get('layouts/boxed', 'StaterkitController@layout_boxed')->name('layout-boxed');
Route::get('layouts/without-menu', 'StaterkitController@without_menu')->name('without-menu');
Route::get('layouts/empty', 'StaterkitController@layout_empty')->name('layout-empty');
Route::get('layouts/blank', 'StaterkitController@layout_blank')->name('layout-blank');

//User
/* Route Apps */

Route::group(['prefix' => 'management','middleware' => ['role:super-admin']], function () {
    Route::get('role',  [ManagementController::class, 'index'] )->name('management.role');
    Route::get('list_role',  [ManagementController::class, 'list_role'] )->name('management.list.role');
    Route::get('list',  [ManagementController::class, 'list_permission'] )->name('management.list.permission');
    Route::post('add/permission',  [ManagementController::class, 'add_permission'] )->name('management.add.permission');

});

Route::group(['prefix' => 'document','middleware' => ['role:super-admin|admin|user']], function () {
    Route::get('index',  [DocumentController::class, 'index'] )->name('document.index');
    Route::get('list',  [DocumentController::class, 'list'] )->name('document.list');
    Route::get('create',  [DocumentController::class, 'create'] )->name('document.create');
    Route::post('store',  [DocumentController::class, 'store'] )->name('document.store');
    Route::get('edit/{id}',  [DocumentController::class, 'edit'] )->name('document.edit');
    Route::get('clone/{id}',  [DocumentController::class, 'clone'] )->name('document.clone');
    Route::get('revision/{id}',  [DocumentController::class, 'revision'] )->name('document.revision');
    Route::get('show/{id}',  [DocumentController::class, 'show'] )->name('document.show');
    Route::get('print/{id}',  [DocumentController::class, 'print'] )->name('document.print');
    Route::post('update',  [DocumentController::class, 'update'] )->name('document.update');
    Route::post('image',  [DocumentController::class, 'image_upload'] )->name('document.image');
    Route::post('workflow',  [DocumentController::class, 'workflow'] )->name('document.workflow');
});

Route::group(['prefix' => 'advisorys','middleware' => ['role:super-admin|admin|user']], function () {
    Route::get('index',  [AdvisoryHeadController::class, 'index'] )->name('advisorys.index');
    Route::get('head/list',  [AdvisoryHeadController::class, 'list'] )->name('advisorys.head.list');
    Route::get('list/{id}',  [AdvisoryController::class, 'list'] )->name('advisorys.list');
    Route::get('import',  [AdvisoryHeadController::class, 'import'] )->name('advisory.import');
    Route::post('load',  [AdvisoryHeadController::class, 'load'] )->name('advisory.load');
    Route::get('detail/{id}',  [AdvisoryHeadController::class, 'show'] )->name('advisory.detail');
    Route::get('summary/{id}',  [AdvisoryController::class, 'summary'] )->name('advisorys.summary');
    Route::get('list_summary/{id}',  [AdvisoryController::class, 'list_summary'] )->name('advisorys.list_summary');
    Route::delete('destroy/{id}',  [AdvisoryHeadController::class, 'destroy'] )->name('advisory.destroy');
    Route::post('final/{id}',  [AdvisoryController::class, 'finalWork'] )->name('advisorys.finalWork');
    Route::post('edit',  [AdvisoryController::class, 'update'] )->name('advisorys.edit');
});

Route::group(['prefix' => 'workstatus','middleware' => ['role:super-admin|admin|user']], function () {
    Route::get('index',  [WorkStatusHeadController::class, 'index'] )->name('workstatus.index');
    Route::get('head/list',  [WorkStatusHeadController::class, 'list'] )->name('workstatus.head.list');
    Route::get('import',  [WorkStatusHeadController::class, 'import'] )->name('workstatus.import');
    Route::post('load',  [WorkStatusHeadController::class, 'load'] )->name('workstatus.load');
    Route::get('detail/{id}',  [WorkStatusController::class, 'show'] )->name('workstatus.detail');
    Route::get('list/{id}',  [WorkStatusController::class, 'list'] )->name('workstatus.list');
    Route::get('detail/check/{id}',  [WorkStatusController::class, 'show_check'] )->name('workstatus.check');
    Route::get('check/{id}',  [WorkStatusController::class, 'check'] )->name('workstatus.list_check');
    Route::post('edit',  [WorkStatusController::class, 'update'] )->name('workstatus.edit');
    Route::post('edit/check',  [WorkStatusController::class, 'update_check'] )->name('workstatus.edit_check');
    Route::get('setting',  [WorkStatusController::class, 'setting'] )->name('workstatus.setting');
    Route::post('final/{id}',  [WorkStatusController::class, 'finalWork'] )->name('workstatus.finalWork');
    Route::get('summary/{id}',  [WorkStatusController::class, 'summary'] )->name('workstatus.summary');
    Route::get('list_summary/{id}',  [WorkStatusController::class, 'list_summary'] )->name('workstatus.list_summary');
    Route::post('edit/Summary',  [WorkStatusController::class, 'update_summary'] )->name('workstatus.edit_summary');
    Route::delete('destroy/{id}',  [WorkStatusHeadController::class, 'destroy'] )->name('workstatus.destroy');
    Route::get('setting/costs',  [WorkStatusController::class, 'setting_costs'] )->name('workstatus.setting.costs');
});

Route::group(['prefix' => 'workflow','middleware' => ['role:super-admin|admin|user']], function () {
    Route::get('index',  [WorkflowController::class, 'index'] )->name('workflow.index');
    Route::get('list',  [WorkflowController::class, 'list'] )->name('workflow.list');
    Route::get('create',  [WorkflowController::class, 'create'] )->name('workflow.create');
    Route::get('check',  [WorkflowController::class, 'check'] )->name('workflow.check');
    Route::post('store',  [WorkflowController::class, 'store'] )->name('workflow.store');
    Route::delete('destroy/{id}',  [WorkflowController::class, 'destroy'] )->name('workflow.destroy');
    Route::get('show/{id}',  [WorkflowController::class, 'show'] )->name('workflow.show');
    Route::post('sing/{id}',  [WorkflowController::class, 'sing'] )->name('workflow.sing');
    Route::get('log/{id}',  [WorkflowController::class, 'createPDF'] )->name('workflow.log');

});

Route::group(['prefix' => 'notification','middleware' => ['role:super-admin|admin|user']], function () {
    Route::get('show/{id}',  [NotificationController::class, 'show'] )->name('notification.show');
    Route::get('refresh',  [NotificationController::class, 'refresh'] )->name('notification.refresh');
    Route::get('list',  [NotificationController::class, 'list'] )->name('notification.list');
});

Route::group(['prefix' => 'materials','middleware' => ['role:super-admin|admin|user']], function () {
    Route::post('stored',  [MaterialsCostController::class, 'import'] )->name('materials.import');
});

Route::group(['prefix' => 'machines','middleware' => ['role:super-admin|admin|user']], function () {
    Route::post('stored',  [MachineController::class, 'import'] )->name('machines.import');
});

Route::group(['prefix' => 'generic','middleware' => ['role:super-admin|admin|user']], function () {
    Route::post('manpower',  [GenericController::class, 'manpower'] )->name('generic.manpower');
});


Route::group(['prefix' => 'user','middleware' => ['role:super-admin|admin|user']], function () {
    #TODO

    Route::post('reset',  [UserController::class, 'reset'] )->name('resetPassword');
    Route::get('create',  [UserController::class, 'create'] )->name('user.create');
    Route::post('store',  [UserController::class, 'store'] )->name('user.store');
    Route::get('show/{id}',  [UserController::class, 'show'] )->name('user.show');
    Route::get('index',  [UserController::class, 'index'] )->name('user.index');
    Route::get('list',  [UserController::class, 'list'] )->name('user.list');
    Route::get('setting/{id?}',  [UserController::class, 'account_settings_account'] )->name('user.account');
    Route::get('permissions/{id}',  [UserController::class, 'account_settings_permissions'] )->name('user.permissions');
    Route::get('security',  [UserController::class, 'account_settings_security'] )->name('user.security');
    Route::post('change_password/{id}',  [UserController::class, 'changePassword'] )->name('user.change.password');
    Route::post('update/{id}',  [UserController::class, 'update'] )->name('user.update');
    Route::post('set_permission/{id}',  [UserController::class, 'set_permissions'] )->name('permessi.upload');
    Route::post('export',  [UserController::class, 'exportCsv'] )->name('users.export');
    Route::post('export-pdf',  [UserController::class, 'createPDF'] )->name('users.export.pdf');
});

Route::group(['prefix' => 'category','middleware' => ['role:super-admin']], function () {
    Route::get('index',  [WorkflowCategoryController::class, 'index'] )->name('category.index');
    Route::get('list',  [WorkflowCategoryController::class, 'list'] )->name('category.list');
    Route::get('create',  [WorkflowCategoryController::class, 'create'] )->name('category.create');
    Route::post('store',  [WorkflowCategoryController::class, 'store'] )->name('category.store');
    Route::get('edit/{id}',  [WorkflowCategoryController::class, 'edit'] )->name('category.edit');
    Route::post('update/{id}',  [WorkflowCategoryController::class, 'update'] )->name('category.update');

});


Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
   // Route::resource('products', ProductController::class);
});




// locale Route
Route::get('lang/{locale}', [LanguageController::class, 'swap']);
Route::get('not-authorized', [GenericController::class, 'notAuthorized'])->name('notAuthorized');

/* Route Tables */
Route::get('/logout', 'Auth\LoginController@logout');

