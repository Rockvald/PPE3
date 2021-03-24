<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personnel;
use App\Models\Service;

class PersonnelController extends Controller
{
    // TODO: Ajouter une fonction pour supprimer un compte

    public function creer()
    {
        $Services = Service::select('services.*')->orderby('id', 'asc')->get();

        return view('inscription', ['Services' => $Services]);
    }

    public function verif_creer(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|max:50',
            'prenom' => 'required|max:50',
            'email' => 'required|max:50|email',
            'mdp' => 'required',
            'confirm_mdp' => 'required',
            'categorie' => 'required',
            'service' => 'required',
            'page' => 'required',
        ]);

        if ($request->mdp != $request->confirm_mdp) {
            $erreur = 'confirm';
            $Services = Service::select('services.*')->orderby('id', 'asc')->get();
            return view('inscription', ['erreur' => $erreur, 'Services' => $Services, 'nom' => $request->nom, 'prenom' => $request->prenom, 'mail' => $request->email]);
        }

        $Personnel = new Personnel;

        $Personnel->nom = $request->nom;
        $Personnel->prenom = $request->prenom;
        $Personnel->mail = $request->email;
        $Personnel->pass = hash('sha256', $request->mdp);
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

        $mdp = hash('sha256', $request->mdp);

        if ($Personnel->isEmpty())
        {
            $erreur = 'mail';
            return view('connexion', ['erreur' => $erreur, 'page' => $request->page]);
        }
        elseif ($mdp != $Personnel[0]->pass)
        {
            $erreur = 'mdp';
            return view('connexion', ['erreur' => $erreur, 'mail' => $request->email, 'page' => $request->page]);
        }
        elseif ($request->email == $Personnel[0]->mail AND $mdp == $Personnel[0]->pass)
        {
            session_start();

            $_SESSION['mail'] = $request->email;

            if ($request->page != 'accueil') {
                return redirect()->route($request->page);
            } else {
                return $this->afficher();
            }
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

        return view('connexion', ['deconnexion' => $deconnexion]);
    }

    public function afficher()
    {
        if (session_status() == 1) {
            session_start();
        }

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesAccueil = Personnel::donneesAccueil();

        return view('accueil', ['donneesAccueil' => $donneesAccueil, 'donneesPersonnel' => $donneesPersonnel]);
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

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesAccueil = Personnel::donneesAccueil();

        $vrai = true;

        return view('accueil', ['vrai' => $vrai, 'donneesAccueil' => $donneesAccueil, 'donneesPersonnel' => $donneesPersonnel]);
    }

    public function supprimer(Request $request)
    {
        $validatedData = $request->validate([
            'mail' => 'required|email',
        ]);

        $Personnel = Personnel::where('mail', $request->mail)->update(['message' => '']);

        session_start();

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesAccueil = Personnel::donneesAccueil();

        $suppr = true;

        return view('accueil', ['suppr' => $suppr, 'donneesAccueil' => $donneesAccueil, 'donneesPersonnel' => $donneesPersonnel]);
    }

    public function suppressionmessages()
    {
        $Personnel = Personnel::select('*')->update(['message' => '']);

        session_start();

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesAccueil = Personnel::donneesAccueil();

        $supprtous = true;

        return view('accueil', ['supprtous' => $supprtous, 'donneesAccueil' => $donneesAccueil, 'donneesPersonnel' => $donneesPersonnel]);
    }

    public function modificationlogo(Request $request)
    {
        $validatedData = $request->validate([
            'photo_logo' => 'required',
        ]);

        session_start();

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesAccueil = Personnel::donneesAccueil();

        if ($request->file('photo_logo')->getsize() > 500000) {

            $tropgros = true;

            return view('accueil', ['tropgros' => $tropgros, 'donneesAccueil' => $donneesAccueil, 'donneesPersonnel' => $donneesPersonnel]);
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
                return view('accueil', ['invalide' => $invalide, 'donneesAccueil' => $donneesAccueil, 'donneesPersonnel' => $donneesPersonnel]);
                break;
        }

        $nomChemin = '/var/www/html/PPE3/Application/storage/app/public/logo-cci.png';

        imagepng($photo, $nomChemin);

        $modif = true;

        return view('accueil', ['modif' => $modif, 'donneesAccueil' => $donneesAccueil, 'donneesPersonnel' => $donneesPersonnel]);
    }

    public function suppressionlogo()
    {
        session_start();

        unlink('/var/www/html/PPE3/Application/storage/app/public/logo-cci.png');

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesAccueil = Personnel::donneesAccueil();

        $supprlogo = true;

        return view('accueil', ['supprlogo' => $supprlogo, 'donneesAccueil' => $donneesAccueil, 'donneesPersonnel' => $donneesPersonnel]);
    }

    public function messagerie()
    {
        if (session_status() == 1) {
            session_start();
        }

        if (!isset($_SESSION['mail'])) {
            header('Refresh: 0; url='.url('?page=messagerie'));
            exit;
        }

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesAccueil = Personnel::donneesAccueil();

        if ($donneesPersonnel['Personnel'][0]->nomCategorie != 'Administrateur') {
            $droitinsuf = true;
            return view('accueil', ['droitinsuf' => $droitinsuf, 'donneesAccueil' => $donneesAccueil, 'donneesPersonnel' => $donneesPersonnel]);
        } else {
            return view('messagerie', ['donneesAccueil' => $donneesAccueil, 'donneesPersonnel' => $donneesPersonnel]);
        }
    }

    public function statistique()
    {
        if (session_status() == 1) {
            session_start();
        }

        if (!isset($_SESSION['mail'])) {
            header('Refresh: 0; url='.url('?page=statistique'));
            exit;
        }

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesAccueil = Personnel::donneesAccueil();

        if ($donneesPersonnel['Personnel'][0]->nomCategorie != 'Administrateur') {
            $droitinsuf = true;
            return view('accueil', ['droitinsuf' => $droitinsuf, 'donneesAccueil' => $donneesAccueil, 'donneesPersonnel' => $donneesPersonnel]);
        } else {
            return view('statistique', ['donneesAccueil' => $donneesAccueil, 'donneesPersonnel' => $donneesPersonnel]);
        }
    }

    public function personnalisationducompte()
    {
        if (session_status() == 1) {
            session_start();
        }

        if (!isset($_SESSION['mail'])) {
            header('Refresh: 0; url='.url('?page=personnalisationducompte'));
            exit;
        }

        $donneesPersonnel = Personnel::donneesPersonnel();

        return view('personnalisationducompte', ['donneesPersonnel' => $donneesPersonnel]);
    }

    public function modificationPersonnalisation(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => '|max:50',
            'prenom' => '|max:50',
            'mail' => '|max:50|email',
            'mdp' => 'required',
            'confirm_mdp' => 'required',
        ]);

        session_start();

        $donneesPersonnel = Personnel::donneesPersonnel();

        if ($request->mdp != $request->confirm_mdp) {
            $erreur = 'confirm';
            return view('personnalisationducompte', ['donneesPersonnel' => $donneesPersonnel, 'erreur' => $erreur]);
        }

        if ($request->nom != $donneesPersonnel['Personnel'][0]->nom AND $request->nom != '') {
            $Personnel = Personnel::where('mail', $request->mail)->update(['nom' => $request->nom]);
        }

        if ($request->prenom != $donneesPersonnel['Personnel'][0]->prenom AND $request->prenom != '') {
            $Personnel = Personnel::where('mail', $request->mail)->update(['prenom' => $request->prenom]);
        }

        if ($request->mail != $donneesPersonnel['Personnel'][0]->mail AND $request->mail != '') {
            $Personnel = Personnel::where('mail', $request->mail)->update(['mail' => $request->mail]);
        }

        if ($request->mdp != $donneesPersonnel['Personnel'][0]->mdp AND $request->mdp != '') {
            $Personnel = Personnel::where('mail', $request->mail)->update(['pass' => hash('sha256', $request->mdp)]);
        }

        $confirm = true;

        return view('personnalisationducompte', ['donneesPersonnel' => $donneesPersonnel, 'confirm' => $confirm]);
    }
}
