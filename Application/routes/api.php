<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ServiceResource;
use App\Models\Service;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
|--------------------------------------------------------------------------
|
| Route::middleware('auth:api')->get('/user', function (Request $request) {
|    return $request->user();
| });
|
|--------------------------------------------------------------------------
*/

// Gestion des services
Route::get('/service/{id}', function ($id) {
    return new ServiceResource(Service::findOrFail($id));
});

Route::get('/services/{nom}', function ($nom) {
    return new ServiceResource(Service::where('nomService', $nom)->firstOrFail());
});

Route::get('/services', function () {
    return ServiceResource::collection(Service::all());
});

Route::post('/creerService', function () {
    return ServiceResource::creer();
});

Route::put('/modifierService/{id}', function ($id) {
    return ServiceResource::modifier($id);
});

Route::delete('/supprimerService/{id}', function ($id) {
    Service::findOrFail($id)->delete();
    return ['message' => 'La suppression a bien été effectué'];
});
