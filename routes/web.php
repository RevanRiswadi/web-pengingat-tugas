<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PushNotificationController;

Route::redirect('/', '/tasks');

Route::resource('tasks', TaskController::class);

Route::prefix('push')->name('push.')->group(function () {
    Route::get('/public-key', [PushNotificationController::class, 'getPublicKey'])->name('key');
    Route::post('/subscribe', [PushNotificationController::class, 'subscribe'])->name('subscribe');
    Route::post('/unsubscribe', [PushNotificationController::class, 'unsubscribe'])->name('unsubscribe');
    Route::post('/send-test', [PushNotificationController::class, 'sendTest'])->name('test');
    Route::post('/send-reminders', [PushNotificationController::class, 'sendReminders'])->name('reminders');
});