<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('api-docs', function () {
    return view('swagger.index');
});

Route::get('/swagger-api', function () {
    return file_get_contents(base_path("resources/views/swagger/api.yml"));
})->name('swagger-api');

Route::get('images/{filename}', function ($filename) {
    return response()->file(storage_path('app/public/images/' . $filename));
})->name("images.product");

