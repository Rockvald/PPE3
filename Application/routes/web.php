<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CommandesController;
use App\Http\Controllers\DemandesSpecifiquesController;
use App\Http\Controllers\EtatController;
use App\Http\Controllers\FamillesFournituresController;
use App\Http\Controllers\FournituresController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\ServiceController;

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

// Page d'accueil
Route::get('/', function () {
    return view('connexion');
});


// Gestion du personnel
Route::post('/connexion', [PersonnelController::class, 'connexion']);

Route::get('/inscription', [PersonnelController::class, 'creer']);

Route::post('/inscription', [PersonnelController::class, 'verif_creer']);

Route::get('/deconnexion', [PersonnelController::class, 'deconnexion']);

Route::get('/accueil', [PersonnelController::class, 'afficher'])->name('accueil');

Route::post('/message', [PersonnelController::class, 'message']);

Route::post('/supprimer', [PersonnelController::class, 'supprimer']);

Route::get('/suppressionmessages', [PersonnelController::class, 'suppressionmessages']);

Route::post('/modificationlogo', [PersonnelController::class, 'modificationlogo']);

Route::get('/suppressionlogo', [PersonnelController::class, 'suppressionlogo']);

Route::get('/messagerie', [PersonnelController::class, 'messagerie'])->name('messagerie');

Route::get('/statistique', [PersonnelController::class, 'statistique'])->name('statistique');

Route::get('/personnalisationducompte', [PersonnelController::class, 'personnalisationducompte'])->name('personnalisationducompte');

Route::post('/modificationPersonnalisation', [PersonnelController::class, 'modificationPersonnalisation']);


// Gestion des fournitures
Route::get('/fournitures', [FournituresController::class, 'afficher'])->name('fournitures');

Route::post('/rechercher', [FournituresController::class, 'rechercher']);

Route::post('/creationfourniture', [FournituresController::class, 'creationfourniture']);

Route::post('/majquantite', [FournituresController::class, 'majquantite']);

Route::post('/majquantitemin', [FournituresController::class, 'majquantitemin']);


// Gestion des familles des fournitures
Route::get('/famillesfournitures', [FamillesFournituresController::class, 'afficher'])->name('famillesfournitures');

Route::post('/creationfamille', [FamillesFournituresController::class, 'creationfamille']);

Route::post('/modificationfamille', [FamillesFournituresController::class, 'modificationfamille']);


// Gestion des demandes spécifiques
Route::get('/demandesspecifiques', [DemandesSpecifiquesController::class, 'afficher'])->name('demandesspecifiques');

Route::post('/creationdemande', [DemandesSpecifiquesController::class, 'creation']);


// Gestion des commandes
Route::get('/suivi', [CommandesController::class, 'afficher'])->name('suivi');

Route::post('/commander', [CommandesController::class, 'commander']);


// Gestion des d'états
Route::post('/majetatdemande', [EtatController::class, 'majetatdemande']);

Route::post('/majetatcommande', [EtatController::class, 'majetatcommande']);


// Gestion des départements
Route::get('/departements', [ServiceController::class, 'afficher'])->name('departements');

Route::post('/creationdepartement', [ServiceController::class, 'creationdepartement']);

Route::post('/modificationvalideur', [ServiceController::class, 'modificationvalideur']);

Route::post('/modificationservice', [ServiceController::class, 'modificationservice']);
