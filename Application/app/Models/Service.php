<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    static function donneesService()
    {
        $Personnel = Personnel::donneesPersonnel()['Personnel'];

        $Personnels = Personnel::join('services', 'personnels.idService', 'services.id')->join('categories', 'personnels.idCategorie', 'categories.id')->select('*')->orderby('personnels.id', 'asc')->get();

        $Services = Service::select('services.*')->get();

        $ServiceUtilvalideur = Service::join('personnels', 'services.id', 'personnels.idService')->join('categories', 'personnels.idCategorie', 'categories.id')->select('services.*', 'nom', 'prenom', 'mail', 'nomCategorie')->where('nomService', $Personnel[0]->nomService)->where('nomCategorie', 'Valideur')->get();

        $ServiceUtil = Service::select('services.*')->where('nomService', $Personnel[0]->nomService)->get();

        if (isset($ServiceUtilvalideur[0])) {
            $service_util = $ServiceUtilvalideur;
        } else {
            $service_util = $ServiceUtil;
        }

        return ['personnels' => $Personnels, 'services' => $Services, 'service_util' => $service_util];
    }
}
