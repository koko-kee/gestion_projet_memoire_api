<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\api\ProjetController;
use App\Http\Controllers\api\UserController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\api\TacheController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});


Route::middleware('auth:api')->group(function () {
   
    Route::get('/search-users/{query}', [UserController::class, 'search'])->name('users.search');

    // ajoute les routes pour les projets

    Route::get('/projets', [ProjetController::class, 'index'])->name('projets.index');
    Route::get('/projets/{projet}', [ProjetController::class, 'show'])->name('projets.show');
    Route::post('/projets', [ProjetController::class, 'store'])->name('projets.store');
    Route::put('/projets/{id}', [ProjetController::class, 'update'])->name('projets.update');
    Route::delete('/projets/{projet}', [ProjetController::class, 'destroy'])->name('projets.destroy');
    Route::get('/projets/{id}/tasks/count', [ProjetController::class, 'countTasks'])->name('projets.countTasks');
    Route::get('/projets/{id}/tasks/count/status', [ProjetController::class, 'countTaskByStatus'])->name('projets.countTaskByStatus');
    Route::get('/projets/count', [ProjetController::class, 'countAllProjet'])->name('projets.countAllProjet');
    Route::get('/projets/{projet}/progress', [ProjetController::class, 'getProjectProgress']);
    Route::get('/projets/count/status', [ProjetController::class, 'countAllProjetByStatus'])->name('projets.countAllProjetByStatus');
    Route::put('/projets/{id}/status', [ProjetController::class, 'setStatus'])->name('projets.setStatus');
    Route::post('/projets/{id}/invite', [ProjetController::class, 'inviteUser'])->name('projets.inviteUser');
    Route::post('/invitations/{id}/accept', [ProjetController::class, 'acceptInvitation'])->name('invitations.acceptInvitation');
    Route::post('/invitations/{id}/decline', [ProjetController::class, 'declineInvitation'])->name('invitations.declineInvitation');
    Route::get('/projets/{id}/users', [ProjetController::class, 'getUserInProject'])->name('projets.getUserInProject');



    // ajoute les route pour les taches
    
    Route::post('/projets/{id}/tasks', [TacheController::class, 'assignTaskToUser'])->name('taches.assignToProject');
    Route::put('/tasks/{task_id}/decommission/{user_id}', [TacheController::class, 'decommissionTaskToUser'])->name('taches.decommissionToUser');
    Route::delete('/tasks/{task_id}', [TacheController::class, 'deleteTask'])->name('taches.delete');
    Route::put('/tasks/{task_id}/status', [TacheController::class, 'changeStatus'])->name('taches.changeStatus');
    Route::put('/tasks/{task_id}/priority', [TacheController::class, 'changePriority'])->name('taches.changePriority');
    Route::post('/tasks/{task_id}/comments', [TacheController::class, 'addComment'])->name('taches.addComment');
    Route::get('/tasks/{task_id}/comments', [TacheController::class, 'getAllComments'])->name('taches.getAllComments');
    Route::delete('/tasks/{task_id}/comments/{comment_id}', [TacheController::class, 'deleteComment'])->name('taches.deleteComment');
    Route::post('/tasks/{task_id}/attachments', [TacheController::class, 'addAttachment'])->name('taches.addAttachment');
    Route::delete('/tasks/{task_id}/attachments/{attachment_id}', [TacheController::class, 'deleteAttachment'])->name('taches.deleteAttachment');

    Route::get('/projets/{id}/tasks', [TacheController::class, 'getTaskByProjet'])->name('taches.getTaskByProjet');
    Route::get('/my-assigned', [ProjetController::class, 'getMyAssignedProjects'])->name('projets.getMyAssignedProjects');

});
