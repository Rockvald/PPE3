<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandesSpecifiques extends Model
{
    use HasFactory;

    static function donneesDemandes()
    {
        $Personnel = Personnel::donneesPersonnel()['Personnel'];

        $demandes = DemandesSpecifiques::join('etats', 'demandes_specifiques.idEtat', 'etats.id')->join('personnels', 'demandes_specifiques.idPersonnel', 'personnels.id')->select('demandes_specifiques.*', 'nomEtat', 'nom', 'prenom')->orderby('demandes_specifiques.id', 'asc')->get();

        $dateActuel = date_create(date('Y-m-d'));
        $dateMin = date_modify($dateActuel, '-1 month');
        $demandes_pers = DemandesSpecifiques::join('etats', 'demandes_specifiques.idEtat', 'etats.id')->join('personnels', 'demandes_specifiques.idPersonnel', 'personnels.id')->select('demandes_specifiques.*', 'nomEtat', 'mail')->where('mail', $_SESSION['mail'])->where('demandes_specifiques.updated_at', '>', $dateMin)->orderby('demandes_specifiques.id', 'asc')->get();

        $demandes_valid = DemandesSpecifiques::join('etats', 'demandes_specifiques.idEtat', 'etats.id')->join('personnels', 'demandes_specifiques.idPersonnel', 'personnels.id')->join('services', 'personnels.idService', 'services.id')->select('demandes_specifiques.*', 'nomEtat', 'nom', 'prenom', 'nomService')->where('nomService', $Personnel[0]->nomService)->orderby('demandes_specifiques.id', 'asc')->get();

        $Service = Service::select('services.*')->get();

        foreach ($Service as $lignes => $service) {
            $nomService = $service->nomService;
            $$nomService = DemandesSpecifiques::join('personnels', 'demandes_specifiques.idPersonnel', 'personnels.id')->join('services', 'personnels.idService', 'services.id')->join('etats', 'demandes_specifiques.idEtat', 'etats.id')->select('demandes_specifiques.*', 'nom', 'prenom', 'nomEtat', 'nomService')->where('nomService', $service->nomService)->orderby('demandes_specifiques.id', 'asc')->get();
            $donnesService["$nomService"] = $$nomService;
        }

        return ['demandes' => $demandes, 'demandes_pers' => $demandes_pers, 'demandes_valid' => $demandes_valid, 'services' => $Service, 'donnesService' => $donnesService];
    }
}
