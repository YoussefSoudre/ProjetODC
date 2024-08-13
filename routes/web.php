<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StructureController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TicketController;

use App\Http\Controllers\Admin\AdminController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::post('/user/save', [UsersController::class, 'save'])->name('user.save');
Route::post('/user/check', [UsersController::class, 'check'])->name('user.check');
Route::get('/user/logout', [UsersController::class, 'logout'])->name('user.logout');



Route::get('/user/profile', [UsersController::class, 'profile'])->name('user.profile');
Route::get('/user/login', [UsersController::class, 'login'])->name('user.login');
Route::get('/user/profile', [UsersController::class, 'profile'])->name('user.profile');
Route::get('/user/register', [UsersController::class, 'register'])->name('user.register');
Route::get('/user/profileview', [UsersController::class, 'profile'])->name('user.profileview');
Route::get('/user/profileedit', [UsersController::class, 'edit'])->name('user.profileedit');
Route::put('/user/updateProfile', [UsersController::class, 'updateProfile'])->name('user.updateProfile');

Route::get('/user/dashboard', [UsersController::class, 'dashboard'])->name('user.dashboard');


Route::get('/post', [UsersController::class, 'post'])->name('post');

 Route::post('/send-message', [UsersController::class, 'sendMessage'])->name('send-message');
 
 Route::get('/dashboard', [AdminController::class, 'index'])->name('admin');

 Route::get('/agent', [TicketController::class, 'agent'])->name('admin-agent');
 Route::get('/next/{action}/{ticket}', [TicketController::class, 'next']);

 //Admin
 Route::get('/list-tickets/{day}', [AdminController::class, 'listTickets'])->name('admin-list-tickets');
 Route::get('/list-structures', [AdminController::class, 'listStructures'])->name('admin-list-structures');
 Route::get('/list-services', [AdminController::class, 'listServices'])->name('admin-list-services');
 Route::get('/list-notes/{day}', [AdminController::class, 'listNotes'])->name('admin-list-notes');

 //Service
 Route::post('/service', [ServiceController::class, 'create']);

 Route::post('/service/{service}', [ServiceController::class, 'update']);

 //Structure
 Route::post('/structure', [StructureController::class, 'create']);
 Route::post('/structure/{struture}', [StructureController::class, 'update']);


 //user
 Route::get('/admin-profil', [AdminUserController::class, 'profil'])->name('admin-profil');
 Route::get('/list-users', [AdminUserController::class, 'list']);
 Route::get('/register', [AdminUserController::class, 'register'])->name('admin-register');
 Route::post('/register', [AdminUserController::class, 'create'])->name('admin-create');
 Route::post('/admin-user/{user}', [AdminUserController::class, 'update']);
 Route::post('/admin-userpassword/{user}', [AdminUserController::class, 'updatePassword']);
 Route::post('/admin-userrole/{user}', [AdminUserController::class, 'updateRole']);
