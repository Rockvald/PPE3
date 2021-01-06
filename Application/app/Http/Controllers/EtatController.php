<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etat;
use App\Models\DemandesSpecifiques;
use App\Models\Commandes;

class EtatController extends Controller
{
    public function majetatdemande(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'id_etat' => 'required|min:2|max:5',
        ]);

        session_start();

        $DemandesSpecifiques = DemandesSpecifiques::where('id', $request->id)->update(['idEtat' => $request->id_etat]);

        $demandes = DemandesSpecifiques::join('etats', 'demandes_specifiques.idEtat', 'etats.id')->join('personnels', 'demandes_specifiques.idPersonnel', 'personnels.id')->select('demandes_specifiques.*', 'nomEtat', 'nom', 'prenom')->orderby('demandes_specifiques.id', 'asc')->get();

        $demandes_pers = DemandesSpecifiques::join('etats', 'demandes_specifiques.idEtat', 'etats.id')->join('personnels', 'demandes_specifiques.idPersonnel', 'personnels.id')->select('demandes_specifiques.*', 'nomEtat', 'mail')->where('mail', $_SESSION['mail'])->orderby('demandes_specifiques.id', 'asc')->get();

        $demandes_valid = DemandesSpecifiques::join('etats', 'demandes_specifiques.idEtat', 'etats.id')->join('personnels', 'demandes_specifiques.idPersonnel', 'personnels.id')->join('services', 'personnels.idService', 'services.id')->select('demandes_specifiques.*', 'nomEtat', 'nom', 'prenom', 'nomService')->where('nomService', $_SESSION['service'])->orderby('demandes_specifiques.id', 'asc')->get();

        $_SESSION['demandes'] = $demandes;
        $_SESSION['demandes_pers'] = $demandes_pers;
        $_SESSION['demandes_valid'] = $demandes_valid;

        $envoyer = true;

        return view('demandesspecifiques', ['envoyer'=>$envoyer]);
    }

    public function majetatcommande(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'id_etat' => 'required|min:1|max:5',
        ]);

        session_start();

        $Commandes = Commandes::where('id', $request->id)->update(['idEtat' => $request->id_etat]);

        $commande_utilisateur = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->join('fournitures', 'commandes.idFournitures', 'fournitures.id')->select('commandes.*', 'mail', 'nomEtat')->where('mail', $_SESSION['mail'])->orderby('commandes.id', 'asc')->get();

        $commande_complet = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.*', 'nom', 'prenom', 'nomEtat')->orderby('commandes.id', 'asc')->get();

        $commande_valid = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('services', 'personnels.idService', 'services.id')->join('etats', 'commandes.idEtat', 'etats.id')->join('fournitures', 'commandes.idFournitures', 'fournitures.id')->select('commandes.*', 'nom', 'prenom', 'nomEtat', 'nomService')->where('nomService', $_SESSION['service'])->orderby('commandes.id', 'asc')->get();

        $_SESSION['commande_utilisateur'] = $commande_utilisateur;
        $_SESSION['commande_complet'] = $commande_complet;
        $_SESSION['commande_valid'] = $commande_valid;

        $envoyer = true;

        return view('suivi', ['envoyer'=>$envoyer]);
    }
}
