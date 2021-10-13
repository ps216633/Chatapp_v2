<?php

use App\Events\OrderStatusUpdated;
use App\Events\TaskCreated;
use App\Http\Controllers\HomeController;
use App\Models\friend;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\Environment\Console;


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

Route::get('/projects/{project}', [HomeController::class, 'chat'])->name('chat')->middleware('auth');
Route::get('/groupchat/{project}', [HomeController::class, 'chatg'])->name('chatg')->middleware('auth');

Route::get('/toevoegen', [HomeController::class, 'friends'])->name('friends');
Route::get('/groep', [HomeController::class, 'groep'])->name('groep');
Route::get('/toevoegen/vried/{id}', [HomeController::class, 'addfriend'])->name('voegvriend_toe');
Route::post('/toevoegen/group', [HomeController::class, 'addgroup'])->name('addgroup');
Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

// API

Route::get('/api/projects/{project}', function (Project $project){
   return $project->tasks->pluck('body');
});
Route::post('/api/projects/{project}/tasks', function (Project $project) {
    
    $task = $project->tasks()->create([
        'body' => request('body'),
        'user_id' =>  request('user_id')
    ]);
    event(new TaskCreated($task));
    

    return $task;
});
