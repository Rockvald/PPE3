<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Categorie;

class CategorieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static $wrap = 'categorie';

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nomCategorie' => $this->nomCategorie,
            'descriptionCategorie' => $this->descriptionCategorie
        ];
    }

    static function creer()
    {
        $donnees = json_decode(file_get_contents("php://input"));

        $categorie = new Categorie;
        $categorie->nomCategorie = $donnees->nomCategorie;
        $categorie->descriptionCategorie = $donnees->descriptionCategorie;
        $categorie->save();

        return ['message' => 'La création à bien été effectué'];
    }

    static function modifier($id)
    {
        $donnees = json_decode(file_get_contents("php://input"));

        $ancienneDonnees = Categorie::select('*')->where('id', $id)->get();

        $Categorie = Categorie::select('*')->where('id', $id)->update([
            'nomCategorie' => $donnees->nomCategorie ?? $ancienneDonnees[0]->nomCategorie,
            'descriptionCategorie' => $donnees->descriptionCategorie ?? $ancienneDonnees[0]->descriptionCategorie
        ]);

        return ['message' => 'La modification a bien été effectué'];
    }
}
