<?php

use App\Http\Controllers\PelangganController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('pelanggan.index');
});

Route::resource('pelanggan', PelangganController::class);
