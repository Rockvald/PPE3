<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\CategorieResource;
use App\Http\Resources\CommandesResource;
use App\Http\Resources\DemandesSpecifiquesResource;
use App\Http\Resources\EtatResource;
use App\Http\Resources\FamillesFournituresResource;
use App\Http\Resources\FournituresResource;
use App\Http\Resources\PersonnelResource;
use App\Http\Resources\ServiceResource;
use App\Models\Categorie;
use App\Models\Commandes;
use App\Models\DemandesSpecifiques;
use App\Models\Etat;
use App\Models\FamillesFournitures;
use App\Models\Fournitures;
use App\Models\Personnel;
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

// Gestion des catégories
Route::get('/categorie/{id}', function ($id) {
    return new CategorieResource(Categorie::findOrFail($id));
});

Route::get('/liste/categories', function () {
    return CategorieResource::collection(Categorie::all());
});

Route::post('/creer/categorie', function () {
    return CategorieResource::creer();
});

Route::put('/modifier/categorie/{id}', function ($id) {
    return CategorieResource::modifier($id);
});

Route::delete('/supprimer/categorie/{id}', function ($id) {
    Categorie::findOrFail($id)->delete();
    return ['message' => 'La suppression a bien été effectué'];
});


// Gestion des commandes
Route::get('/commande/{id}', function ($id) {
    return new CommandesResource(Commandes::findOrFail($id));
});

Route::get('/liste/commandes', function () {
    return CommandesResource::collection(Commandes::all());
});

Route::post('/creer/commande', function () {
    return CommandesResource::creer();
});

Route::put('/modifier/commande/{id}', function ($id) {
    return CommandesResource::modifier($id);
});

Route::delete('/supprimer/commande/{id}', function ($id) {
    Commandes::findOrFail($id)->delete();
    return ['message' => 'La suppression a bien été effectué'];
});


// Gestion des demandes spécifiques
Route::get('/demande/{id}', function ($id) {
    return new DemandesSpecifiquesResource(DemandesSpecifiques::findOrFail($id));
});

Route::get('/liste/demandes', function () {
    return DemandesSpecifiquesResource::collection(DemandesSpecifiques::all());
});

Route::post('/creer/demande', function () {
    return DemandesSpecifiquesResource::creer();
});

Route::put('/modifier/demande/{id}', function ($id) {
    return DemandesSpecifiquesResource::modifier($id);
});

Route::delete('/supprimer/demande/{id}', function ($id) {
    DemandesSpecifiques::findOrFail($id)->delete();
    return ['message' => 'La suppression a bien été effectué'];
});


// Gestion des états
Route::get('/etat/{id}', function ($id) {
    return new EtatResource(Etat::findOrFail($id));
});

Route::get('/liste/etats', function () {
    return EtatResource::collection(Etat::all());
});

Route::post('/creer/etat', function () {
    return EtatResource::creer();
});

Route::put('/modifier/etat/{id}', function ($id) {
    return EtatResource::modifier($id);
});

Route::delete('/supprimer/etat/{id}', function ($id) {
    Etat::findOrFail($id)->delete();
    return ['message' => 'La suppression a bien été effectué'];
});


// Gestion des familles de fournitures
Route::get('/famille/{id}', function ($id) {
    return new FamillesFournituresResource(FamillesFournitures::findOrFail($id));
});

Route::get('/liste/familles', function () {
    return FamillesFournituresResource::collection(FamillesFournitures::all());
});

Route::post('/creer/famille', function () {
    return FamillesFournituresResource::creer();
});

Route::put('/modifier/famille/{id}', function ($id) {
    return FamillesFournituresResource::modifier($id);
});

Route::delete('/supprimer/famille/{id}', function ($id) {
    FamillesFournitures::findOrFail($id)->delete();
    return ['message' => 'La suppression a bien été effectué'];
});


// Gestion des fournitures
Route::get('/fourniture/{id}', function ($id) {
    return new FournituresResource(Fournitures::findOrFail($id));
});

Route::get('/liste/fournitures', function () {
    return FournituresResource::collection(Fournitures::all());
});

Route::post('/creer/fourniture', function () {
    return FournituresResource::creer();
});

Route::put('/modifier/fourniture/{id}', function ($id) {
    return FournituresResource::modifier($id);
});

Route::delete('/supprimer/fourniture/{id}', function ($id) {
    Fournitures::findOrFail($id)->delete();
    return ['message' => 'La suppression a bien été effectué'];
});


// Gestion des personnels
Route::get('/personnel/{id}', function ($id) {
    return new PersonnelResource(Personnel::findOrFail($id));
});

Route::get('/liste/personnels', function () {
    return PersonnelResource::collection(Personnel::all());
});

Route::post('/creer/personnel', function () {
    return PersonnelResource::creer();
});

Route::put('/modifier/personnel/{id}', function ($id) {
    return PersonnelResource::modifier($id);
});

Route::delete('/supprimer/personnel/{id}', function ($id) {
    Personnel::findOrFail($id)->delete();
    return ['message' => 'La suppression a bien été effectué'];
});


// Gestion des services
Route::get('/service/{id}', function ($id) {
    return new ServiceResource(Service::findOrFail($id));
});

Route::get('/liste/services', function () {
    return ServiceResource::collection(Service::all());
});

Route::post('/creer/service', function () {
    return ServiceResource::creer();
});

Route::put('/modifier/service/{id}', function ($id) {
    return ServiceResource::modifier($id);
});

Route::delete('/supprimer/service/{id}', function ($id) {
    Service::findOrFail($id)->delete();
    return ['message' => 'La suppression a bien été effectué'];
});
