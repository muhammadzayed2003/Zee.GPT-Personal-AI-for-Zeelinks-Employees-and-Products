<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LLMController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ApiKeyController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {

        if (!auth()->user()->ai_workspace_access) {
            return redirect()->route('access.pending');
        }

        return view('chat.index');

    })->name('dashboard');

    // Chat
    Route::post('/chat', [LLMController::class, 'chat'])
        ->name('chat');

    // Conversation history
    Route::get('/conversations', [LLMController::class, 'conversations'])
        ->name('conversations');

    Route::get('/conversations/{id}', [LLMController::class, 'showConversation'])
        ->name('conversation.show');

    // Access pending
    Route::get('/access-pending', function () {
        return view('access-pending');
    })->name('access.pending');

    // API Keys
    Route::middleware('api.access')->group(function () {

        Route::get('/api-keys', [ApiKeyController::class, 'index'])
            ->name('api-keys');

        Route::post('/api-keys', [ApiKeyController::class, 'store'])
            ->name('api-keys.store');

        Route::delete('/api-keys/{apiKey}', [ApiKeyController::class, 'destroy'])
            ->name('api-keys.destroy');
    });
});

// Permissions — admin only
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/permissions', [PermissionController::class, 'index'])
        ->name('permissions');

    Route::put('/permissions/{user}', [PermissionController::class, 'update'])
        ->name('permissions.update');
});

require __DIR__.'/auth.php';