<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FamillesFournitures;
use App\Models\Fournitures;

class FamillesFournituresController extends Controller
{
    public function afficher()
    {
        session_start();

        if (!isset($_SESSION['mail'])) {
            header('Refresh: 0; url=http://localhost/PPE-3/Application/server.php?page=famillesfournitures');
            exit;
        }

        $FamillesFournitures = FamillesFournitures::select('*')->get();

        $_SESSION['famillesfournitures'] = $FamillesFournitures;

        if ($_SESSION['categorie'] != 'Administrateur') {
            $droitinsuf = true;
            return view('accueil', ['droitinsuf' => $droitinsuf]);
        } else {
            return view('famillesfournitures');
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

        $FamillesFournitures = FamillesFournitures::select('*')->get();

        $_SESSION['famillesfournitures'] = $FamillesFournitures;

        $cree = true;

        return view('famillesfournitures', ['cree' => $cree]);
    }

    public function modificationfamille(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'nom_famille' => 'required|max:50',
        ]);

        session_start();

        $idFamille = FamillesFournitures::select('id')->where('nomFamille', $request->nom_famille)->get();

        $majFamilleFournitures = Fournitures::where('id', $request->id)->update(['idFamille' => $idFamille[0]->id]);

        $Fournitures = Fournitures::join('familles_fournitures', 'fournitures.idFamille', 'familles_fournitures.id')->select('fournitures.*', 'nomFamille')->orderby('fournitures.id', 'asc')->get();

        $_SESSION['fournitures'] = $Fournitures;

        $valider = true;

        return view('fournitures', ['valider' => $valider]);
    }
}
