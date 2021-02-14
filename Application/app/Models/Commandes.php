<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commandes extends Model
{
    use HasFactory;

    static function donneesCommandes()
    {
        $Personnel = Personnel::donneesPersonnel()['Personnel'];

        $dateActuel = date_create(date('Y-m-d'));
        $dateMin = date_modify($dateActuel, '-1 month');
        $commande_utilisateur = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.*', 'mail', 'nomEtat')->where('mail', $_SESSION['mail'])->where('commandes.updated_at', '>', $dateMin)->orderby('commandes.id', 'asc')->get();

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

        return ['commande_utilisateur' => $commande_utilisateur, 'commande_complet' => $commande_complet, 'commande_valid' => $commande_valid, 'services' => $Service, 'donnesService' => $donnesService, 'etats' => $Etat, 'donnesEtat' => $donnesEtat];
    }
}
