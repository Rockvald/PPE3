<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Personnel;

class ServiceController extends Controller
{
    public function afficher()
    {
        if (session_status() == 1) {
            session_start();
        }

        if (!isset($_SESSION['mail'])) {
            header('Refresh: 0; url=http://localhost/PPE3/Application/server.php?page=departements');
            exit;
        }

        $Personnel = Personnel::donneesPersonnel()[0];

        $commande = Personnel::donneesPersonnel()[1];

        $commande_fini = Personnel::donneesPersonnel()[2];

        $Services = Service::select('services.*')->get();

        $Personnels = Personnel::join('services', 'personnels.idService', 'services.id')->join('categories', 'personnels.idCategorie', 'categories.id')->select('*')->orderby('personnels.id', 'asc')->get();

        $ServiceUtilvalideur = Service::join('personnels', 'services.id', 'personnels.idService')->join('categories', 'personnels.idCategorie', 'categories.id')->select('services.*', 'nom', 'prenom', 'mail', 'nomCategorie')->where('nomService', $Personnel[0]->nomService)->where('nomCategorie', 'Valideur')->get();

        $ServiceUtil = Service::select('services.*')->where('nomService', $Personnel[0]->nomService)->get();

        if (isset($ServiceUtilvalideur[0])) {
            $service_util = $ServiceUtilvalideur;
        } else {
            $service_util = $ServiceUtil;
        }

        //$_SESSION['services'] = $Services;

        return view('departements', ['Personnel' => $Personnel, 'commande' => $commande, 'commandes_fini' => $commande_fini, 'personnels' => $Personnels, 'services' => $Services, 'service_util' => $service_util]);
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

        $Personnel = Personnel::donneesPersonnel()[0];

        $commande = Personnel::donneesPersonnel()[1];

        $commande_fini = Personnel::donneesPersonnel()[2];

        $Personnels = Personnel::join('services', 'personnels.idService', 'services.id')->join('categories', 'personnels.idCategorie', 'categories.id')->select('*')->orderby('personnels.id', 'asc')->get();

        $ServiceUtilvalideur = Service::join('personnels', 'services.id', 'personnels.idService')->join('categories', 'personnels.idCategorie', 'categories.id')->select('services.*', 'nom', 'prenom', 'mail', 'nomCategorie')->where('nomService', $Personnel[0]->nomService)->where('nomCategorie', 'Valideur')->get();

        $ServiceUtil = Service::select('services.*')->where('nomService', $Personnel[0]->nomService)->get();

        if (isset($ServiceUtilvalideur[0])) {
            $service_util = $ServiceUtilvalideur;
        } else {
            $service_util = $ServiceUtil;
        }

        $Services = Service::select('services.*')->get();

        //$_SESSION['services'] = $Services;

        $cree = true;

        return view('departements', ['cree' => $cree, 'Personnel' => $Personnel, 'commande' => $commande, 'commandes_fini' => $commande_fini, 'personnels' => $Personnels, 'services' => $Services, 'service_util' => $service_util]);
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

        $Personnel = Personnel::donneesPersonnel()[0];

        $commande = Personnel::donneesPersonnel()[1];

        $commande_fini = Personnel::donneesPersonnel()[2];

        $Personnels = Personnel::join('services', 'personnels.idService', 'services.id')->join('categories', 'personnels.idCategorie', 'categories.id')->select('*')->orderby('personnels.id', 'asc')->get();

        $ServiceUtilvalideur = Service::join('personnels', 'services.id', 'personnels.idService')->join('categories', 'personnels.idCategorie', 'categories.id')->select('services.*', 'nom', 'prenom', 'mail', 'nomCategorie')->where('nomService', $Personnel[0]->nomService)->where('nomCategorie', 'Valideur')->get();

        $ServiceUtil = Service::select('services.*')->where('nomService', $Personnel[0]->nomService)->get();

        if (isset($ServiceUtilvalideur[0])) {
            $service_util = $ServiceUtilvalideur;
        } else {
            $service_util = $ServiceUtil;
        }

        $Services = Service::select('services.*')->get();

        //$_SESSION['personnels'] = $Personnels;

        $valider = true;

        return view('departements', ['valider' => $valider, 'Personnel' => $Personnel, 'commande' => $commande, 'commandes_fini' => $commande_fini, 'personnels' => $Personnels, 'services' => $Services, 'service_util' => $service_util]);
    }
}
