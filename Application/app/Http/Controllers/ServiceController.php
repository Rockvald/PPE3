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
            header('Refresh: 0; url='.url('?page=departements'));
            exit;
        }

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesService = Service::donneesService();

        return view('departements', ['donneesService' => $donneesService, 'donneesPersonnel' => $donneesPersonnel]);
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

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesService = Service::donneesService();

        $cree = true;

        return view('departements', ['cree' => $cree, 'donneesService' => $donneesService, 'donneesPersonnel' => $donneesPersonnel]);
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

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesService = Service::donneesService();

        $valider = true;

        return view('departements', ['valider' => $valider, 'donneesService' => $donneesService, 'donneesPersonnel' => $donneesPersonnel]);
    }

    public function modificationservice(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'nom_service' => 'required|max:50',
        ]);

        session_start();

        $idService = Service::select('id')->where('nomService', $request->nom_service)->get();

        Personnel::where('id', $request->id)->update(['idService' => $idService[0]->id]);

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesAccueil = Personnel::donneesAccueil();

        $valider = true;

        return view('accueil', ['valider' => $valider, 'donneesPersonnel' => $donneesPersonnel, 'donneesAccueil' => $donneesAccueil]);
    }
}
