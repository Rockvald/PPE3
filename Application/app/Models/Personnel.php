<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;

    static function donneesPersonnel()
    {
        $Personnel = Personnel::join('services', 'personnels.idService', 'services.id')->join('categories', 'personnels.idCategorie', 'categories.id')->where('mail', $_SESSION['mail'])->get();

        $commande = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.id', 'nomCommandes', 'quantiteDemande', 'commandes.updated_at', 'personnels.mail', 'etats.nomEtat')->where('personnels.mail', $_SESSION['mail'])->where('etats.nomEtat', 'En cours')->orderby('commandes.id', 'asc')->get();

        $dateActuel = date_create(date('Y-m-d'));
        $dateMin = date_modify($dateActuel, '-1 month');
        $commandes_fini = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.id', 'nomCommandes', 'quantiteDemande', 'commandes.updated_at', 'personnels.mail', 'etats.nomEtat')->where('commandes.updated_at', '>', $dateMin)->where('personnels.mail', $_SESSION['mail'])->where('etats.nomEtat', 'Livré')->orWhere('etats.nomEtat', 'Annulé')->where('commandes.updated_at', '>', $dateMin)->where('personnels.mail', $_SESSION['mail'])->orderby('commandes.id', 'asc')->get();

        return ['Personnel' => $Personnel, 'commande' => $commande, 'commandes_fini' => $commandes_fini];
    }

    static function donneesAccueil()
    {
        $commandes_liste = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.id', 'nomCommandes', 'quantiteDemande', 'commandes.created_at', 'commandes.updated_at', 'personnels.nom', 'personnels.prenom', 'etats.nomEtat')->where('etats.nomEtat', 'En cours')->orderby('commandes.updated_at', 'asc')->get();

        $Personnels = Personnel::join('services', 'personnels.idService', 'services.id')->join('categories', 'personnels.idCategorie', 'categories.id')->select('personnels.*', 'nomService', 'nomCategorie')->orderby('personnels.id', 'asc')->get();

        $Fournitures = Fournitures::join('familles_fournitures', 'fournitures.idFamille', 'familles_fournitures.id')->select('fournitures.*', 'nomFamille')->orderby('fournitures.id', 'asc')->get();

        $Services = Service::select('services.*')->get();

        return ['commandes_liste' => $commandes_liste, 'Personnels' => $Personnels, 'Fournitures' => $Fournitures, 'services' => $Services];
    }
}
