<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournitures extends Model
{
    use HasFactory;

    static function listeFournitures()
    {
        $Fournitures = Fournitures::join('familles_fournitures', 'fournitures.idFamille', 'familles_fournitures.id')->select('fournitures.*', 'nomFamille')->orderby('fournitures.id', 'asc')->get();

        return ['fournitures' => $Fournitures];
    }

    static function donneesFourniture()
    {
        $Fournitures = Fournitures::join('familles_fournitures', 'fournitures.idFamille', 'familles_fournitures.id')->select('fournitures.*', 'nomFamille')->orderby('fournitures.id', 'asc')->get();

        $Familles = FamillesFournitures::select('*')->get();

        foreach ($Familles as $lignes => $famille) {
            $nomFamille = $famille->nomFamille;
            $$nomFamille = Fournitures::join('familles_fournitures', 'fournitures.idFamille', 'familles_fournitures.id')->where('nomFamille', $famille->nomFamille)->select('fournitures.*', 'nomFamille')->get();
            $donnesFamille["$nomFamille"] = $$nomFamille;
        }

        $alerte = "";

        foreach ($Fournitures as $lignes => $fourniture) {
            if ($fourniture->quantiteDisponible <= $fourniture->quantiteMinimum) {
                $alerte = $alerte."- ".$fourniture->nomFournitures."\n";
            }
        }

        return ['fournitures' => $Fournitures, 'famillesfournitures' => $Familles, 'donnesFamille' => $donnesFamille, "alerte" => $alerte];
    }
}
