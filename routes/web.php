<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MusicController;

Route::get('/', [MusicController::class, 'showMusicFiles']);
Route::get('/open-folder', [MusicController::class, 'openFolder'])->name('open-folder');

/*Route::get('/', function () {
    return view('welcome');
});*/
