<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fournitures;
use App\Models\FamillesFournitures;
use App\Models\Personnel;

class FournituresController extends Controller
{
    public function afficher()
    {
        if (session_status() == 1) {
            session_start();
        }

        if (!isset($_SESSION['mail'])) {
            header('Refresh: 0; url='.url("?page=fournitures"));
            exit;
        }

        $Personnel = Personnel::donneesPersonnel()[0];

        $commande = Personnel::donneesPersonnel()[1];

        $commande_fini = Personnel::donneesPersonnel()[2];

        $Fournitures = Fournitures::join('familles_fournitures', 'fournitures.idFamille', 'familles_fournitures.id')->select('fournitures.*', 'nomFamille')->orderby('fournitures.id', 'asc')->get();

        $Familles = FamillesFournitures::select('*')->get();

        foreach ($Familles as $lignes => $famille) {
            $nomFamille = $famille->nomFamille;
            $$nomFamille = Fournitures::join('familles_fournitures', 'fournitures.idFamille', 'familles_fournitures.id')->where('nomFamille', $famille->nomFamille)->select('fournitures.*', 'nomFamille')->get();
            $donnesFamille["$nomFamille"] = $$nomFamille;
        }

        //$_SESSION['famillesfournitures'] = $Familles;
        //$_SESSION['fournitures'] = $Fournitures;

        return view('fournitures', ['Personnel' => $Personnel, 'commande' => $commande, 'commandes_fini' => $commande_fini, 'fournitures' => $Fournitures, 'famillesfournitures' => $Familles, 'donnesFamille' => $donnesFamille]);
    }

    public function rechercher(Request $request)
    {
        $validatedData = $request->validate([
            'recherche' => 'required',
        ]);

        session_start();

        $rechercheExplode = explode(' ', $request->recherche);

        $recherche = implode('%', $rechercheExplode);

        $resultats = Fournitures::join('familles_fournitures', 'fournitures.idFamille', 'familles_fournitures.id')->select('fournitures.*', 'nomFamille')->where('nomFournitures', 'like', '%'.$recherche.'%')->orWhere('nomFamille', 'like', '%'.$recherche.'%')->orderby('fournitures.id', 'asc')->get();

        $Personnel = Personnel::donneesPersonnel()[0];

        $commande = Personnel::donneesPersonnel()[1];

        $commande_fini = Personnel::donneesPersonnel()[2];

        $Fournitures = Fournitures::join('familles_fournitures', 'fournitures.idFamille', 'familles_fournitures.id')->select('fournitures.*', 'nomFamille')->orderby('fournitures.id', 'asc')->get();

        $Familles = FamillesFournitures::select('*')->get();

        foreach ($Familles as $lignes => $famille) {
            $nomFamille = $famille->nomFamille;
            $$nomFamille = Fournitures::join('familles_fournitures', 'fournitures.idFamille', 'familles_fournitures.id')->where('nomFamille', $famille->nomFamille)->select('fournitures.*', 'nomFamille')->get();
            $donnesFamille["$nomFamille"] = $$nomFamille;
        }

        //$_SESSION['recherche'] = $resultat;

        if (isset($resultats[0])) {
            $reponse = true;
        } else {
            $reponse = false;
        }

        return view('fournitures', ['reponse' => $reponse, 'Personnel' => $Personnel, 'commande' => $commande, 'commandes_fini' => $commande_fini, 'fournitures' => $Fournitures, 'famillesfournitures' => $Familles, 'donnesFamille' => $donnesFamille, 'resultats' => $resultats]);
    }

    public function creationfourniture(Request $request)
    {
        $validatedData = $request->validate([
            'photo_fournitures' => 'required',
            'nom_fourniture'=> 'required|max50',
            'description_fourniture' => 'required|max:50',
            'quantite_disponible' => 'required|min:1|max:100',
        ]);

        session_start();

        $nomMinuscules = strtolower($request->nom_fourniture);

        $nomExplode = explode(' ', $nomMinuscules);

        $nomPhoto = implode('-', $nomExplode);

        if ($request->file('photo_fournitures')->getsize() > 500000) {

            $tropgros = true;

            return view('fournitures', ['tropgros' => $tropgros, 'requete' => $request]);
        }

        $fichierTelecharger = $request->file('photo_fournitures');
        switch (exif_imagetype($fichierTelecharger)) {
            case IMAGETYPE_JPEG:
                $photo = imagecreatefromjpeg($fichierTelecharger);
                break;
            case IMAGETYPE_GIF:
                $photo = imagecreatefromgif($fichierTelecharger);
                break;
            case IMAGETYPE_BMP:
                $photo = imagecreatefrombmp($fichierTelecharger);
                break;
            case IMAGETYPE_PNG:
                $photo = imagecreatefrompng($fichierTelecharger);
                break;
            default:
                $invalide = true;
                return view('fournitures', ['invalide' => $invalide, 'requete' => $request]);
                break;
        }

        $nomChemin = '/var/www/html/PPE3/Application/storage/app/public/'.$nomPhoto.'.jpg';

        imagejpeg($photo, $nomChemin);

        $idFamille = FamillesFournitures::select('id')->where('nomFamille', $request->nom_famille)->get();

        $Fournitures = new Fournitures;

        $Fournitures->idFamille = $idFamille[0]->id;
        $Fournitures->nomFournitures = $request->nom_fourniture;
        $Fournitures->nomPhoto = $nomPhoto;
        $Fournitures->descriptionFournitures = $request->description_fourniture;
        $Fournitures->quantiteDisponible = $request->quantite_disponible;

        $Fournitures->save();

        $Fournitures = Fournitures::join('familles_fournitures', 'fournitures.idFamille', 'familles_fournitures.id')->select('fournitures.*', 'nomFamille')->orderby('fournitures.id', 'asc')->get();

        $_SESSION['fournitures'] = $Fournitures;

        $cree = true;

        return view('fournitures', ['cree' => $cree]);
    }

    public function majquantite(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'quantite_disponible' => 'required|min:0|max:100',
        ]);

        session_start();

        $majquantite = Fournitures::where('id', $request->id)->update(['quantiteDisponible' => $request->quantite_disponible]);

        $Fournitures = Fournitures::join('familles_fournitures', 'fournitures.idFamille', 'familles_fournitures.id')->select('fournitures.*', 'nomFamille')->orderby('fournitures.id', 'asc')->get();

        $_SESSION['fournitures'] = $Fournitures;

        $valider = true;

        return view('fournitures', ['valider' => $valider]);
    }
}
