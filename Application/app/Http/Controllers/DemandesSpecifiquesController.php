<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemandesSpecifiques;
use App\Models\Personnel;
use App\Models\Service;

class DemandesSpecifiquesController extends Controller
{
    public function afficher()
    {
        session_start();

        if (!isset($_SESSION['mail'])) {
            header('Refresh: 0; url=http://localhost/PPE3/Application/server.php?page=demandesspecifiques');
            exit;
        }

        $demandes = DemandesSpecifiques::join('etats', 'demandes_specifiques.idEtat', 'etats.id')->join('personnels', 'demandes_specifiques.idPersonnel', 'personnels.id')->select('demandes_specifiques.*', 'nomEtat', 'nom', 'prenom')->orderby('demandes_specifiques.id', 'asc')->get();

        $demandes_pers = DemandesSpecifiques::join('etats', 'demandes_specifiques.idEtat', 'etats.id')->join('personnels', 'demandes_specifiques.idPersonnel', 'personnels.id')->select('demandes_specifiques.*', 'nomEtat', 'mail')->where('mail', $_SESSION['mail'])->orderby('demandes_specifiques.id', 'asc')->get();

        $demandes_valid = DemandesSpecifiques::join('etats', 'demandes_specifiques.idEtat', 'etats.id')->join('personnels', 'demandes_specifiques.idPersonnel', 'personnels.id')->join('services', 'personnels.idService', 'services.id')->select('demandes_specifiques.*', 'nomEtat', 'nom', 'prenom', 'nomService')->where('nomService', $_SESSION['service'])->orderby('demandes_specifiques.id', 'asc')->get();

        $demande_fini = DemandesSpecifiques::join('etats', 'demandes_specifiques.idEtat', 'etats.id')->join('personnels', 'demandes_specifiques.idPersonnel', 'personnels.id')->select('demandes_specifiques.*', 'nomEtat', 'mail')->where('mail', $_SESSION['mail'])->where('etats.nomEtat', 'Livré')->orWhere('etats.nomEtat', 'Annulé')->orderby('demandes_specifiques.id', 'asc')->get();

        for ($i=0; $i < $demande_fini->count(); $i++) {
            $dateActuel = date_create(date('Y-m-d'));
            $dateCommande = date_create(date('Y-m-d', strtotime($demande_fini[$i]->updated_at)));
            $diff = date_diff($dateActuel, $dateCommande);
            if ($diff->format('%a') > 14) {
                $commande_suppr = DemandesSpecifiques::where('id', $demande_fini[$i]->id)->delete();

                $demande_fini = DemandesSpecifiques::join('etats', 'demandes_specifiques.idEtat', 'etats.id')->join('personnels', 'demandes_specifiques.idPersonnel', 'personnels.id')->select('demandes_specifiques.*', 'nomEtat', 'mail')->where('mail', $_SESSION['mail'])->where('etats.nomEtat', 'Livré')->orWhere('etats.nomEtat', 'Annulé')->orderby('demandes_specifiques.id', 'asc')->get();
            }
        }

        $Service = Service::select('services.*')->get();

        for ($j=0; $j < $Service->count(); $j++) {
            $_SESSION[$Service[$j]->nomService] = DemandesSpecifiques::join('personnels', 'demandes_specifiques.idPersonnel', 'personnels.id')->join('services', 'personnels.idService', 'services.id')->join('etats', 'demandes_specifiques.idEtat', 'etats.id')->select('demandes_specifiques.*', 'nom', 'prenom', 'nomEtat', 'nomService')->where('nomService', $Service[$j]->nomService)->orderby('demandes_specifiques.id', 'asc')->get();
        }

        $_SESSION['services'] = $Service;
        $_SESSION['demandes'] = $demandes;
        $_SESSION['demandes_pers'] = $demandes_pers;
        $_SESSION['demandes_valid'] = $demandes_valid;

        return view('demandesspecifiques');
    }

    public function creation(Request $request)
    {
        $validatedData = $request->validate([
            'nom_demande' => 'required',
            'quantite_demande' => 'required|min:1|max:50',
        ]);

        session_start();

        $id_personnel = Personnel::where('mail', $_SESSION['mail'])->select('id')->get();

        $DemandesSpecifiques = new DemandesSpecifiques;

        $DemandesSpecifiques->nomDemande = $request->nom_demande;
        $DemandesSpecifiques->quantiteDemande = $request->quantite_demande;
        $DemandesSpecifiques->lienProduit = $request->lien_produit ?? 'Aucun lien fourni';
        $DemandesSpecifiques->idEtat = '1';
        $DemandesSpecifiques->idPersonnel = $id_personnel[0]->id;

        $DemandesSpecifiques->save();

        // Mise à jour des demandes personnels
        $demandes_pers = DemandesSpecifiques::join('etats', 'demandes_specifiques.idEtat', 'etats.id')->join('personnels', 'demandes_specifiques.idPersonnel', 'personnels.id')->select('demandes_specifiques.*', 'nomEtat', 'mail')->where('mail', $_SESSION['mail'])->orderby('demandes_specifiques.id', 'asc')->get();

        $_SESSION['demandes_pers'] = $demandes_pers;

        // Renvoi d'une variable afin d'afficher un message de confirmation
        $vrai = true;

        return view('demandesspecifiques', ['vrai'=>$vrai]);
    }
}
