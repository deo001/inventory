<?php

use App\Livewire\Pages\Index;
use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Posts\ShowPost;
use App\Livewire\Pages\Posts\PostsIndex;

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



Route::view('profile', 'profile')
    ->middleware(['auth', 'verified'])
    ->name('profile');

//require __DIR__.'/auth.php';
