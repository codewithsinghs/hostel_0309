<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// SPA entry (catch-all)
Route::get('/{any}', function () {
    return view('app'); // Vue entry point
})->where('any', '.*');
