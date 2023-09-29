<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Content\ContentController;
use App\Http\Controllers\Pixbay\PixbayController;
use App\Http\Controllers\Replicate\DubController;
use App\Http\Controllers\Replicate\ReplicateController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::post('/register', [LoginController::class, 'register']);

Route::middleware(['auth:sanctum', 'update_letters_count'])->group(function () {

    Route::prefix('/user')->group(function () {
        Route::get('/', [UserController::class, 'get']);
        Route::put('/letters', [UserController::class, 'updateLetter']);
        Route::put('/ad', [UserController::class, 'watchAd']);
        Route::post('/logout', [LoginController::class, 'logout']);

    });

    Route::prefix('/script')->group(function () {
        Route::get('/options', [ContentController::class, 'scriptGeneratorOptions']);
        Route::post('/generate', [ContentController::class, 'scriptGenerator']);
        Route::get('/generated', [ContentController::class, 'Generated']);
    });
    Route::prefix('/voice')->group(function () {
        Route::get('/', [ContentController::class, 'voices']);
        Route::post('/premium/generate', [ContentController::class, 'generateVoice']);
        Route::post('/clone', [ContentController::class, 'cloneVoice']);
        Route::get('/get', [ContentController::class, 'getUserVoices']);
        Route::post('/simple/generate', [ContentController::class, 'simpleVoiceGeneration']);
        Route::delete('/clone/delete/{id}', [ContentController::class, 'deleteCloneVoice']);
        Route::post('/clone/edit/{id}', [ContentController::class, 'editCloneVoice']);
        Route::get('/clone/example', [ContentController::class, 'getSampleFromVoice']);

    });
    Route::post('/clone', [ReplicateController::class, 'cloneVoice']);
    Route::post('/generate/srt', [ReplicateController::class, 'generateSubtitle']);
    Route::post('/url-generate/srt', [ReplicateController::class, 'generateSubtitleWithURL']);
    Route::get('/generated/srt', [ReplicateController::class, 'generatedSubtitle']);
    Route::put('/generated/srt/update', [ReplicateController::class, 'generateSrtFormat']);
    Route::get('/generated/srt/translate', [ReplicateController::class, 'translateSrt']);
    Route::get('/translate/languages', [ReplicateController::class, 'supportedLanguages']);

    Route::prefix('/pixebay')->group(function () {
        Route::post('/ideas', [PixbayController::class, 'getMainIdeas']);
        Route::get('/images', [PixbayController::class, 'scarp']);
    });
    Route::prefix('/dub')->group(function () {
        Route::post('/generate', [DubController::class, 'generateDub']);
        Route::get('/generated', [DubController::class, 'getGeneratedDub']);
        Route::post('/add-music', [DubController::class, 'addMusic']);
        Route::post('/add-music-url', [DubController::class, 'addMusicUrl']);
        Route::get('/get-music', [DubController::class, 'getMusic']);
    });
    Route::post('/upload/audio', [DubController::class, 'uploadFile']);
    Route::get('/get/days30x', [ContentController::class, 'getCloneVoiceLast30Days']);
    Route::delete('/del/days30x', [ContentController::class, 'deleteCloneVoiceLast30Days']);
});
