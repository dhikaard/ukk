<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Filament\Actions\Exports\Models\Export;

Route::get('/', function () {
    return view('welcome');
});
