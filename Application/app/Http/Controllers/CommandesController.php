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
        session_start();

        if (!isset($_SESSION['mail'])) {
            header('Refresh: 0; url=http://localhost/PPE-3/Application/server.php?page=suivi');
            exit;
        }

        $commande_utilisateur = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.*', 'mail', 'nomEtat')->where('mail', $_SESSION['mail'])->orderby('commandes.id', 'asc')->get();

        $commande_complet = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.*', 'nom', 'prenom', 'nomEtat')->orderby('commandes.id', 'asc')->get();

        $commande_valid = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('services', 'personnels.idService', 'services.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.*', 'nom', 'prenom', 'nomEtat', 'nomService')->where('nomService', $_SESSION['service'])->orderby('commandes.id', 'asc')->get();

        $Service = Service::select('services.*')->get();

        for ($i=0; $i < $Service->count(); $i++) {
            $_SESSION[$Service[$i]->nomService] = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('services', 'personnels.idService', 'services.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.*', 'nom', 'prenom', 'nomEtat', 'nomService')->where('nomService', $Service[$i]->nomService)->orderby('commandes.id', 'asc')->get();
        }

        $Etat = Etat::select('*')->get();

        for ($i=0; $i < $Etat->count(); $i++) {
            $_SESSION[$Etat[$i]->nomEtat] = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.*', 'nom', 'prenom', 'mail', 'nomEtat')->where('mail', $_SESSION['mail'])->where('nomEtat', $Etat[$i]->nomEtat)->orderby('commandes.id', 'asc')->get();
        }

        $_SESSION['etats'] = $Etat;
        $_SESSION['services'] = $Service;
        $_SESSION['commande_utilisateur'] = $commande_utilisateur;
        $_SESSION['commande_complet'] = $commande_complet;
        $_SESSION['commande_valid'] = $commande_valid;

        return view('suivi');
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
