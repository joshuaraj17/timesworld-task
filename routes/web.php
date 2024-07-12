<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });



Route::resource('', ProductController::class);
Route::resource('products', ProductController::class);

// Add a route for AJAX image upload
Route::post('products/upload_image', [ProductController::class, 'uploadImage'])->name('products.upload_image');

