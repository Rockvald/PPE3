<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Personnel;

class ServiceController extends Controller
{
    public function afficher()
    {
        session_start();

        if (!isset($_SESSION['mail'])) {
            header('Refresh: 0; url=http://localhost/PPE-3/Application/server.php?page=departements');
            exit;
        }

        $Services = Service::select('services.*')->get();

        $ServiceUtilvalideur = Service::join('personnels', 'services.id', 'personnels.idService')->join('categories', 'personnels.idCategorie', 'categories.id')->select('services.*', 'nom', 'prenom', 'mail', 'nomCategorie')->where('nomService', $_SESSION['service'])->where('nomCategorie', 'Valideur')->get();

        $ServiceUtil = Service::select('services.*')->where('nomService', $_SESSION['service'])->get();

        if (isset($ServiceUtilvalideur[0])) {
            $_SESSION['service_util'] = $ServiceUtilvalideur;
        } else {
            $_SESSION['service_util'] = $ServiceUtil;
        }

        $_SESSION['services'] = $Services;

        return view('departements');
    }

    public function creationdepartement(Request $request)
    {
        $validatedData = $request->validate([
            'nom_departement' => 'required|max:50',
            'description_departement' => 'required|max:50',
        ]);

        session_start();

        $Service = new Service;
        $Service->nomService = $request->nom_departement;
        $Service->descriptionService = $request->description_departement;
        $Service->save();

        $Services = Service::select('services.*')->get();

        $_SESSION['services'] = $Services;

        $cree = true;

        return view('departements', ['cree' => $cree]);
    }

    public function modificationvalideur(Request $request)
    {
        $validatedData = $request->validate([
            'nom_prenom' => 'required',
        ]);

        session_start();

        if ($request->nom_prenom != 'Aucun') {
            $nomPrenom = explode(' ', $request->nom_prenom);
            $majNouvValid = Personnel::where('nom', $nomPrenom[0])->where('prenom', $nomPrenom[1])->update(['idCategorie' => '2']);
        }

        if (isset($request->mail_ancien_valideur)) {
            $majAncienValid = Personnel::where('mail', $request->mail_ancien_valideur)->update(['idCategorie' => '1']);
        }

        $Personnels = Personnel::join('services', 'personnels.idService', 'services.id')->join('categories', 'personnels.idCategorie', 'categories.id')->select('*')->orderby('personnels.id', 'asc')->get();

        $_SESSION['personnels'] = $Personnels;

        $valider = true;

        return view('departements', ['valider' => $valider]);
    }
}
