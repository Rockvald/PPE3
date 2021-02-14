<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FamillesFournitures;
use App\Models\Fournitures;
use App\Models\Personnel;

class FamillesFournituresController extends Controller
{
    public function afficher()
    {
        if (session_status() == 1) {
            session_start();
        }

        if (!isset($_SESSION['mail'])) {
            header('Refresh: 0; url='.url('?page=famillesfournitures'));
            exit;
        }

        $donneesAccueil = Personnel::donneesAccueil();
        $donneesPersonnel = Personnel::donneesPersonnel();
        $famillesFournitures = FamillesFournitures::listeFamilles();

        if ($donneesPersonnel['Personnel'][0]->nomCategorie != 'Administrateur') {
            $droitinsuf = true;
            return view('accueil', ['droitinsuf' => $droitinsuf, 'donneesAccueil' => $donneesAccueil, 'donneesPersonnel' => $donneesPersonnel]);
        } else {
            return view('famillesfournitures', ['donneesPersonnel' => $donneesPersonnel, 'famillesFournitures' => $famillesFournitures]);
        }
    }

    public function creationfamille(Request $request)
    {
        $validatedData = $request->validate([
            'nom_famille' => 'required|max:50',
            'description_famille' => 'required|max:50',
        ]);

        session_start();

        $CreationFamillesFournitures = new FamillesFournitures;
        $CreationFamillesFournitures->nomFamille = $request->nom_famille;
        $CreationFamillesFournitures->descriptionFamille = $request->description_famille;
        $CreationFamillesFournitures->save();

        $donneesPersonnel = Personnel::donneesPersonnel();
        $famillesFournitures = FamillesFournitures::listeFamilles();

        $cree = true;

        return view('famillesfournitures', ['cree' => $cree, 'donneesPersonnel' => $donneesPersonnel, 'famillesFournitures' => $famillesFournitures]);
    }

    public function modificationfamille(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'nom_famille' => 'required|max:50',
        ]);

        session_start();

        $idFamille = FamillesFournitures::select('id')->where('nomFamille', $request->nom_famille)->get();

        Fournitures::where('id', $request->id)->update(['idFamille' => $idFamille[0]->id]);

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesFourniture = Fournitures::donneesFourniture();

        $valider = true;

        return view('fournitures', ['valider' => $valider, 'donneesPersonnel' => $donneesPersonnel, 'donneesFourniture' => $donneesFourniture]);
    }
}
