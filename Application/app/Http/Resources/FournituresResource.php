<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Fournitures;

class FournituresResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static $wrap = 'fourniture';

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'idFamille' => $this->idFamille,
            'nomFournitures' => $this->nomFournitures,
            'nomPhoto' => $this->nomPhoto,
            'descriptionFournitures' => $this->descriptionFournitures,
            'quantiteDisponible' => $this->quantiteDisponible,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    static function creer()
    {
        $donnees = json_decode(file_get_contents("php://input"));

        $fourniture = new Fournitures;

        $fourniture->nomFournitures = $donnees->nomFournitures;
        $fourniture->descriptionFournitures = $donnees->descriptionFournitures;
        $fourniture->nomPhoto = $donnees->nomPhoto;
        $fourniture->quantiteDisponible = $donnees->quantiteDisponible;
        $fourniture->idFamille = $donnees->idFamille;

        $fourniture->save();

        return ['message' => 'La création à bien été effectué'];
    }

    static function modifier($id)
    {
        $donnees = json_decode(file_get_contents("php://input"));

        $ancienneDonnees = Fournitures::select('*')->where('id', $id)->get();

        $Fournitures = Fournitures::select('*')->where('id', $id)->update([
            'nomFournitures' => $donnees->nomFournitures ?? $ancienneDonnees[0]->nomFournitures,
            'descriptionFournitures' => $donnees->descriptionFournitures ?? $ancienneDonnees[0]->descriptionFournitures,
            'nomPhoto' => $donnees->nomPhoto ?? $ancienneDonnees[0]->nomPhoto,
            'quantiteDisponible' => $donnees->quantiteDisponible ?? $ancienneDonnees[0]->quantiteDisponible,
            'idFamille' => $donnees->idFamille ?? $ancienneDonnees[0]->idFamille
        ]);

        return ['message' => 'La modification a bien été effectué'];
    }
}
