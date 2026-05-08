<?php

use App\Livewire\User\Profile;
use Illuminate\Support\Facades\Route;
use App\Livewire\Users\Index;

Route::view('/', 'welcome')->name('systems');

Route::view('/storages', 'welcome')->name('storages');

require __DIR__.'/auth.php';
