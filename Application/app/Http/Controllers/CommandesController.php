<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commandes;
use App\Models\Personnel;
use App\Models\Fournitures;
use App\Models\Service;
use App\Models\Etat;

class CommandesController extends Controller
{
    public function afficher()
    {
        if (session_status() == 1) {
            session_start();
        }

        if (!isset($_SESSION['mail'])) {
            header('Refresh: 0; url=http://localhost/PPE3/Application/server.php?page=suivi');
            exit;
        }

        $Personnel = Personnel::donneesPersonnel()[0];

        $commande = Personnel::donneesPersonnel()[1];

        $commande_fini = Personnel::donneesPersonnel()[2];

        $commande_utilisateur = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.*', 'mail', 'nomEtat')->where('mail', $_SESSION['mail'])->orderby('commandes.id', 'asc')->get();

        $commande_complet = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.*', 'nom', 'prenom', 'nomEtat')->orderby('commandes.id', 'asc')->get();

        $commande_valid = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('services', 'personnels.idService', 'services.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.*', 'nom', 'prenom', 'nomEtat', 'nomService')->where('nomService', $Personnel[0]->nomService)->orderby('commandes.id', 'asc')->get();

        $Service = Service::select('services.*')->get();

        foreach ($Service as $lignes => $service) {
            $nomService = $service->nomService;
            $$nomService = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('services', 'personnels.idService', 'services.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.*', 'nom', 'prenom', 'nomEtat', 'nomService')->where('nomService', $service->nomService)->orderby('commandes.id', 'asc')->get();
            $donnesService["$nomService"] = $$nomService;
        }

        $Etat = Etat::select('*')->get();

        foreach ($Etat as $lignes => $etat) {
            $nomEtat = $etat->nomEtat;
            $$nomEtat = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.*', 'nom', 'prenom', 'mail', 'nomEtat')->where('mail', $_SESSION['mail'])->where('nomEtat', $etat->nomEtat)->orderby('commandes.id', 'asc')->get();
            $donnesEtat["$nomEtat"] = $$nomEtat;
        }

        /*$_SESSION['etats'] = $Etat;
        $_SESSION['services'] = $Service;
        $_SESSION['commande_utilisateur'] = $commande_utilisateur;
        $_SESSION['commande_complet'] = $commande_complet;
        $_SESSION['commande_valid'] = $commande_valid;*/

        return view('suivi', ['Personnel' => $Personnel, 'commande' => $commande, 'commandes_fini' => $commande_fini, 'commande_utilisateur' => $commande_utilisateur, 'commande_complet' => $commande_complet, 'commande_valid' => $commande_valid, 'services' => $Service, 'donnesService' => $donnesService, 'etats' => $Etat, 'donnesEtat' => $donnesEtat]);
    }

    public function commander(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'nom_fourniture' => 'required',
            'quantite_demande' => 'required|min:1'
        ]);

        session_start();

        $id_personnel = Personnel::where('mail', $_SESSION['mail'])->select('id')->get();

        $quantite = Fournitures::where('id', $request->id)->select('quantiteDisponible')->get();

        $nouv_quantite = $quantite[0]->quantiteDisponible - $request->quantite_demande;

        $majquantite = Fournitures::where('id', $request->id)->update(['quantiteDisponible' => $nouv_quantite]);

        $Commandes = new Commandes;

        $Commandes->idEtat = '1';
        $Commandes->idFournitures = $request->id;
        $Commandes->idPersonnel = $id_personnel[0]->id;
        $Commandes->nomCommandes = $request->nom_fourniture;
        $Commandes->quantiteDemande = $request->quantite_demande;

        $Commandes->save();

        $Fournitures = Fournitures::join('familles_fournitures', 'fournitures.idFamille', 'familles_fournitures.id')->select('fournitures.*', 'nomFamille')->orderby('fournitures.id', 'asc')->get();

        $commande_utilisateur = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->join('fournitures', 'commandes.idFournitures', 'fournitures.id')->select('commandes.*', 'personnels.mail', 'etats.nomEtat')->where('personnels.mail', $_SESSION['mail'])->orderby('commandes.id', 'asc')->get();

        $_SESSION['fournitures'] = $Fournitures;
        $_SESSION['commande_utilisateur'] = $commande_utilisateur;

        $commande_cree = true;

        return view('fournitures', ['commande_cree'=>$commande_cree]);
    }
}
