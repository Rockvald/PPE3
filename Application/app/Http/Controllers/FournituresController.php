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
            header('Refresh: 0; url='.url('?page=fournitures'));
            exit;
        }

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesFourniture = Fournitures::donneesFourniture();

        return view('fournitures', ['donneesPersonnel' => $donneesPersonnel, 'donneesFourniture' => $donneesFourniture]);
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

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesFourniture = Fournitures::donneesFourniture();

        if (isset($resultats[0])) {
            $reponse = true;
        } else {
            $reponse = false;
        }

        return view('fournitures', ['reponse' => $reponse, 'donneesPersonnel' => $donneesPersonnel, 'donneesFourniture' => $donneesFourniture, 'resultats' => $resultats]);
    }

    public function creationfourniture(Request $request)
    {
        $validatedData = $request->validate([
            'photo_fournitures' => 'required',
            'nom_fourniture'=> 'required|max:50',
            'description_fourniture' => 'required|max:50',
            'quantite_disponible' => 'required|min:1|max:100',
            'quantite_minimum' => 'required|min:0|max:100',
        ]);

        session_start();

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesFourniture = Fournitures::donneesFourniture();

        $nomMinuscules = strtolower($request->nom_fourniture);

        $nomExplode = explode(' ', $nomMinuscules);

        $nomPhoto = implode('-', $nomExplode);

        if ($request->file('photo_fournitures')->getsize() > 500000) {

            $tropgros = true;

            return view('fournitures', ['tropgros' => $tropgros, 'requete' => $request, 'donneesPersonnel' => $donneesPersonnel, 'donneesFourniture' => $donneesFourniture]);
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
                return view('fournitures', ['invalide' => $invalide, 'requete' => $request, 'donneesPersonnel' => $donneesPersonnel, 'donneesFourniture' => $donneesFourniture]);
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
        $Fournitures->quantiteMinimum = $request->quantite_minimum;

        $Fournitures->save();

        $cree = true;

        return view('fournitures', ['cree' => $cree, 'donneesPersonnel' => $donneesPersonnel, 'donneesFourniture' => $donneesFourniture]);
    }

    public function majquantite(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'quantite_disponible' => 'required|min:0|max:100',
        ]);

        session_start();

        $majquantite = Fournitures::where('id', $request->id)->update(['quantiteDisponible' => $request->quantite_disponible]);

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesFourniture = Fournitures::donneesFourniture();

        $valider = true;

        return view('fournitures', ['valider' => $valider, 'donneesPersonnel' => $donneesPersonnel, 'donneesFourniture' => $donneesFourniture]);
    }

    public function majquantitemin(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'quantite_minimum' => 'required|min:0|max:100',
        ]);

        session_start();

        $majquantitemin = Fournitures::where('id', $request->id)->update(['quantiteMinimum' => $request->quantite_minimum]);

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesFourniture = Fournitures::donneesFourniture();

        $valider = true;

        return view('fournitures', ['valider' => $valider, 'donneesPersonnel' => $donneesPersonnel, 'donneesFourniture' => $donneesFourniture]);
    }
}
