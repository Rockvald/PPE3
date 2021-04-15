<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Etat;

class EtatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public static $wrap = 'etat';

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nomEtat' => $this->nomEtat,
            'descriptionEtat' => $this->descriptionEtat,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    static function creer()
    {
        $donnees = json_decode(file_get_contents("php://input"));

        $etat = new Etat;
        $etat->nomEtat = $donnees->nomEtat;
        $etat->descriptionEtat = $donnees->descriptionEtat;
        $etat->save();

        return ['message' => 'La création à bien été effectué'];
    }

    static function modifier($id)
    {
        $donnees = json_decode(file_get_contents("php://input"));

        $ancienneDonnees = Etat::select('*')->where('id', $id)->get();

        $Etat = Etat::select('*')->where('id', $id)->update([
            'nomEtat' => $donnees->nomEtat ?? $ancienneDonnees[0]->nomEtat,
            'descriptionEtat' => $donnees->descriptionEtat ?? $ancienneDonnees[0]->descriptionEtat
        ]);

        return ['message' => 'La modification a bien été effectué'];
    }
}
