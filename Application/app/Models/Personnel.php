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
        $commande_fini = Commandes::join('personnels', 'commandes.idPersonnel', 'personnels.id')->join('etats', 'commandes.idEtat', 'etats.id')->select('commandes.id', 'nomCommandes', 'quantiteDemande', 'commandes.updated_at', 'personnels.mail', 'etats.nomEtat')->where('commandes.updated_at', '>', $dateMin)->where('personnels.mail', $_SESSION['mail'])->where('etats.nomEtat', 'Livré')->orWhere('etats.nomEtat', 'Annulé')->where('commandes.updated_at', '>', $dateMin)->where('personnels.mail', $_SESSION['mail'])->orderby('commandes.id', 'asc')->get();

        return [$Personnel, $commande, $commande_fini];
    }
}
