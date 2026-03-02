<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LivreurController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}/souscategories', [CategoryController::class, 'sousCategories']);
Route::get('/souscategories/{id}/subtypes', [CategoryController::class, 'subTypes']);


Route::middleware('auth:sanctum')->post('/livreur/location', [LivreurController::class, 'updateLocation']);


Route::middleware('auth:sanctum')->group(function () {

    // Le livreur envoie sa position GPS
    Route::post(
        '/livreur/location',
        [LivreurController::class, 'updateLocation']
    );
    
    // Raffraichir le js ==> Real time
    Route::middleware('auth:sanctum')->get(
        '/livraisons/{livraison}/livreur-position',
        [LivreurController::class, 'position']
    );


});
