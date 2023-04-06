<?php

use App\Http\Controllers\MeetingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('meeting')->name('api.meeting.')->middleware(['cors'])->group(function () {

    /**
     * MEETING APIS
     */

    Route::post('create', [MeetingController::class, 'create']);

    Route::post('start', [MeetingController::class, 'start']);

    Route::post('join', [MeetingController::class, 'join']);

    Route::post('status', [MeetingController::class, 'status']);

    Route::post('end', [MeetingController::class, 'end']);
    Route::post('extend', [MeetingController::class, 'extend']);

    Route::get('endCallback', [MeetingController::class, 'endCallback'])->name('endCallback');

    /**
     * RECORDING APIS
     */
    Route::get('recordings/{bbb_meeting_id}', [MeetingController::class, 'recordings']);

    Route::delete('recordings/{bbb_meeting_id}', [MeetingController::class, 'deleteRecording']);

    Route::post('recordingReady', [MeetingController::class, 'recordingReadyCallback']);

    
    Route::post('alert', [MeetingController::class, 'alertMsg'])->name("alert");

});
