<?php

use Hito\Admin\Http\Controllers\AdminController;
use Hito\Admin\Http\Controllers\AnnouncementController;
use Hito\Admin\Http\Controllers\ClientController;
use Hito\Admin\Http\Controllers\DepartmentController;
use Hito\Admin\Http\Controllers\GroupController;
use Hito\Admin\Http\Controllers\ImportController;
use Hito\Admin\Http\Controllers\LocationController;
use Hito\Admin\Http\Controllers\ModuleController;
use Hito\Admin\Http\Controllers\ProcedureController;
use Hito\Admin\Http\Controllers\ProjectController;
use Hito\Admin\Http\Controllers\RoleController;
use Hito\Admin\Http\Controllers\TeamController;
use Hito\Admin\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

Route::name('modules.')->prefix('modules')->group(static function() {
    Route::get('/', [ModuleController::class, 'index'])->name('index');
    Route::post('/', [ModuleController::class, 'toggle'])->name('toggle');
    Route::get('/available', [ModuleController::class, 'available'])
        ->name('available');
    Route::post('/action', [ModuleController::class, 'action'])
        ->name('action');
});

Route::get('/announcements/{announcement}/delete', [AnnouncementController::class, 'delete'])
    ->can('delete,announcement')
    ->name('announcements.delete');
Route::resource('announcements', AnnouncementController::class);

Route::get('/procedures/{procedure}/delete', [ProcedureController::class, 'delete'])
    ->can('delete,procedure')
    ->name('procedures.delete');
Route::resource('procedures', ProcedureController::class);

Route::get('/roles/{role}/delete', [RoleController::class, 'delete'])
    ->can('delete,role')
    ->name('roles.delete');
Route::resource('roles', RoleController::class);

Route::get('/teams/{team}/delete', [TeamController::class, 'delete'])
    ->can('delete,team')
    ->name('teams.delete');
Route::resource('teams', TeamController::class);

Route::get('/projects/{project}/delete', [ProjectController::class, 'delete'])
    ->can('delete,project')
    ->name('projects.delete');
Route::resource('projects', ProjectController::class);

Route::get('/clients/{client}/delete', [ClientController::class, 'delete'])
    ->can('delete,client')
    ->name('clients.delete');
Route::resource('clients', ClientController::class);

Route::get('/departments/{department}/delete', [DepartmentController::class, 'delete'])
    ->can('delete,department')
    ->name('departments.delete');
Route::resource('departments', DepartmentController::class);

Route::get('/locations/{location}/delete', [LocationController::class, 'delete'])
    ->can('delete,location')
    ->name('locations.delete');
Route::resource('locations', LocationController::class);

Route::get('/users/{user}/delete', [UserController::class, 'delete'])
    ->can('delete,user')
    ->name('users.delete');
Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])
    ->can('update,user')
    ->name('users.reset-password');
Route::resource('users', UserController::class);

Route::get('/groups/{group}/delete', [GroupController::class, 'delete'])
    ->can('delete,group')
    ->name('groups.delete');
Route::resource('groups', GroupController::class);

Route::resource('import', ImportController::class);
