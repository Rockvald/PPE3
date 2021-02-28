<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\FamillesFournitures;

class FamillesFournituresResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public static $wrap = 'famille';

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nomFamille' => $this->nomFamille,
            'descriptionFamille' => $this->descriptionFamille,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    static function creer()
    {
        $donnees = json_decode(file_get_contents("php://input"));

        $famille = new FamillesFournitures;
        $famille->nomFamille = $donnees->nomFamille;
        $famille->descriptionFamille = $donnees->descriptionFamille;
        $famille->save();

        return ['message' => 'La création à bien été effectué'];
    }

    static function modifier($id)
    {
        $donnees = json_decode(file_get_contents("php://input"));

        $ancienneDonnees = FamillesFournitures::select('*')->where('id', $id)->get();

        $Famille = FamillesFournitures::select('*')->where('id', $id)->update([
            'nomFamille' => $donnees->nomFamille ?? $ancienneDonnees[0]->nomFamille,
            'descriptionFamille' => $donnees->descriptionFamille ?? $ancienneDonnees[0]->descriptionFamille
        ]);

        return ['message' => 'La modification a bien été effectué'];
    }
}
