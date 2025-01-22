<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Resources\ProductsResource\Pages\ViewDetails;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products/{product}/view', [ViewDetails::class, 'render'])->name('filament.resources.products.view');