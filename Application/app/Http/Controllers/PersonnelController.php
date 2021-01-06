<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personnel;
use App\Models\Fournitures;
use App\Models\Categorie;
use App\Models\Commandes;
use App\Models\Etat;
use App\Models\Service;
use App\Models\DemandesSpecifiques;

class PersonnelController extends Controller
{
    public function creer()
    {
        $Services = Service::select('services.*')->orderby('id', 'asc')->get();

        return view('inscription', ['Services'=>$Services]);
    }

    public function verif_creer(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|max:50',
            'prenom' => 'required|max:50',
            'email' => 'required|max:50|email',
            'mdp' => 'required',
            'categorie' => 'required',
            'service' => 'required',
        ]);

        $Personnel = new Personnel;

        $Personnel->nom = $request->nom;
        $Personnel->prenom = $request->prenom;
        $Personnel->mail = $request->email;
        $Personnel->pass = password_hash($request->mdp, PASSWORD_DEFAULT);
        $Personnel->idCategorie = $request->categorie;
        $Personnel->idService = $request->service;
        $Personnel->message = '';

        $Personnel->save();

        return $this->connexion($request);
    }

    public function connexion(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'mdp' => 'required',
            'page' => 'required',
        ]);

        $Personnel = Personnel::join('services', 'personnels.idService', 'services.id')->join('categories', 'personnels.idCategorie', 'categories.id')->where('mail', $request->email)->get();

        if ($Personnel->isEmpty())
        {
            $erreur = 'mail';
            return view('connexion', ['erreur'=>$erreur]);
        }
        elseif (!password_verify($request->mdp, $Personnel[0]->pass))
        {
            $erreur = 'mdp';
            return view('connexion', ['erreur'=>$erreur, 'mail'=>$request->email]);
        }
        elseif ($request->email == $Personnel[0]->mail AND password_verify($request->mdp, $Personnel[0]->pass))
        {
            $commande_fini = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.id', 'nomCommandes', 'quantiteDemande', 'commandes.updated_at', 'personnels.mail', 'etats.nomEtat')->where('personnels.mail', $Personnel[0]->mail)->where('etats.nomEtat', 'Livré')->orWhere('etats.nomEtat', 'Annulé')->orderby('commandes.id', 'asc')->get();

            for ($i=0; $i < $commande_fini->count(); $i++) {
                $dateActuel = date_create(date('Y-m-d'));
                $dateCommande = date_create(date('Y-m-d', strtotime($commande_fini[$i]->updated_at)));
                $diff = date_diff($dateActuel, $dateCommande);
                if ($diff->format('%a') > 14) {
                    $commande_suppr = Commandes::where('id', $commande_fini[$i]->id)->delete();

                    $commande_fini = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.id', 'nomCommandes', 'quantiteDemande', 'commandes.updated_at', 'personnels.mail', 'etats.nomEtat')->where('personnels.mail', $Personnel[0]->mail)->where('etats.nomEtat', 'Livré')->orWhere('etats.nomEtat', 'Annulé')->orderby('commandes.id', 'asc')->get();
                }
            }

            $commande = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.id', 'nomCommandes', 'quantiteDemande', 'commandes.updated_at', 'personnels.mail', 'etats.nomEtat')->where('personnels.mail', $Personnel[0]->mail)->where('etats.nomEtat', 'En cours')->orderby('commandes.id', 'asc')->get();

            $commande_liste = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.id', 'nomCommandes', 'quantiteDemande', 'commandes.created_at', 'commandes.updated_at', 'personnels.nom', 'personnels.prenom', 'etats.nomEtat')->where('etats.nomEtat', 'En cours')->orderby('commandes.updated_at', 'asc')->get();

            $Personnels = Personnel::join('services', 'personnels.idService', 'services.id')->join('categories', 'personnels.idCategorie', 'categories.id')->select('*')->orderby('personnels.id', 'asc')->get();

            $Fournitures = Fournitures::join('familles_fournitures', 'fournitures.idFamille', 'familles_fournitures.id')->select('fournitures.*', 'nomFamille')->orderby('fournitures.id', 'asc')->get();

            session_start();

            $_SESSION['nom'] = $Personnel[0]->nom;
            $_SESSION['prenom'] = $Personnel[0]->prenom;
            $_SESSION['mail'] = $Personnel[0]->mail;
            $_SESSION['pass'] = $Personnel[0]->pass;
            $_SESSION['categorie'] = $Personnel[0]->nomCategorie;
            $_SESSION['service'] = $Personnel[0]->nomService;
            $_SESSION['message'] = $Personnel[0]->message;
            $_SESSION['commandes'] = $commande;
            $_SESSION['commandes_fini'] = $commande_fini;
            $_SESSION['commandes_liste'] = $commande_liste;
            $_SESSION['personnels'] = $Personnels;
            $_SESSION['fournitures'] = $Fournitures;

            return redirect()->route($request->page);
        }
    }

    public function deconnexion()
    {
        session_start();

        $_SESSION = array();
        session_destroy();

        setcookie('login', '');
        setcookie('pass_hache', '');

        $deconnexion = true;

        return view('connexion', ['deconnexion'=>$deconnexion]);
    }

    public function afficher()
    {
        session_start();

        return view('accueil');
    }

    public function message(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'required|max:1500',
            'mail' => 'required',
        ]);

        if ($request->mail == 'tous') {
            $Personnel = Personnel::select('*')->update(['message' => $request->message]);
        } else {
            $Personnel = Personnel::where('mail', $request->mail)->update(['message' => $request->message]);
        }

        session_start();

        $Personnel = Personnel::where('mail', $_SESSION['mail'])->get();

        $Personnels = Personnel::join('services', 'personnels.idService', 'services.id')->join('categories', 'personnels.idCategorie', 'categories.id')->select('*')->orderby('personnels.id', 'asc')->get();

        $_SESSION['message'] = $Personnel[0]->message;
        $_SESSION['personnels'] = $Personnels;

        $vrai = true;

        return view('accueil', ['vrai'=>$vrai]);
    }

    public function supprimer(Request $request)
    {
        $validatedData = $request->validate([
            'mail' => 'required|email',
        ]);

        $Personnel = Personnel::where('mail', $request->mail)->update(['message' => '']);

        session_start();

        $Personnel = Personnel::where('mail', $_SESSION['mail'])->get();

        $Personnels = Personnel::join('services', 'personnels.idService', 'services.id')->join('categories', 'personnels.idCategorie', 'categories.id')->select('*')->orderby('personnels.id', 'asc')->get();

        $_SESSION['message'] = $Personnel[0]->message;
        $_SESSION['personnels'] = $Personnels;

        $suppr = true;

        return view('accueil', ['suppr'=>$suppr]);
    }

    public function suppressionmessages()
    {
        $Personnel = Personnel::select('*')->update(['message' => '']);

        session_start();

        $Personnel = Personnel::where('mail', $_SESSION['mail'])->get();

        $Personnels = Personnel::join('services', 'personnels.idService', 'services.id')->join('categories', 'personnels.idCategorie', 'categories.id')->select('*')->orderby('personnels.id', 'asc')->get();

        $_SESSION['message'] = $Personnel[0]->message;
        $_SESSION['personnels'] = $Personnels;

        $supprtous = true;

        return view('accueil', ['supprtous'=>$supprtous]);
    }

    public function modificationlogo(Request $request)
    {
        $validatedData = $request->validate([
            'photo_logo' => 'required',
        ]);

        session_start();

        if ($request->file('photo_logo')->getsize() > 500000) {

            $tropgros = true;

            return view('accueil', ['tropgros' => $tropgros]);
        }

        $fichierTelecharger = $request->file('photo_logo');
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
                $blanc = imagecolorallocate($photo, 255, 255, 255);
                imagecolortransparent($photo, $blanc);
                break;
            default:
                $invalide = true;
                return view('accueil', ['invalide' => $invalide]);
                break;
        }

        $nomChemin = '/var/www/html/PPE3/Application/storage/app/public/logo-cci.png';

        imagepng($photo, $nomChemin);

        $modif = true;

        return view('accueil', ['modif' => $modif]);
    }

    public function suppressionlogo()
    {
        session_start();

        unlink('/var/www/html/PPE3/Application/storage/app/public/logo-cci.png');

        $supprlogo = true;

        return view('accueil', ['supprlogo' => $supprlogo]);
    }

    public function messagerie()
    {
        session_start();

        if (!isset($_SESSION['mail'])) {
            header('Refresh: 0; url=http://localhost/PPE3/Application/server.php?page=messagerie');
            exit;
        }

        if ($_SESSION['categorie'] != 'Administrateur') {
            $droitinsuf = true;
            return view('accueil', ['droitinsuf' => $droitinsuf]);
        } else {
            return view('messagerie');
        }
    }

    public function statistique()
    {
        session_start();

        if (!isset($_SESSION['mail'])) {
            header('Refresh: 0; url=http://localhost/PPE3/Application/server.php?page=statistique');
            exit;
        }

        if ($_SESSION['categorie'] != 'Administrateur') {
            $droitinsuf = true;
            return view('accueil', ['droitinsuf' => $droitinsuf]);
        } else {
            return view('statistique');
        }
    }

    public function personnalisationducompte()
    {
        session_start();

        if (!isset($_SESSION['mail'])) {
            header('Refresh: 0; url=http://localhost/PPE3/Application/server.php?page=personnalisationducompte');
            exit;
        }

        return view('personnalisationducompte');
    }
}
