<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\DemandesSpecifiques;

class DemandesSpecifiquesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public static $wrap = 'demande';

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'idEtat' => $this->idEtat,
            'idPersonnel' => $this->idPersonnel,
            'nomDemande' => $this->nomDemande,
            'quantiteDemande' => $this->quantiteDemande,
            'lienProduit' => $this->lienProduit,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    static function creer()
    {
        $donnees = json_decode(file_get_contents("php://input"));

        $demande = new DemandesSpecifiques;

        $demande->nomDemande = $donnees->nomDemande;
        $demande->quantiteDemande = $donnees->quantiteDemande;
        $demande->lienProduit = $donnees->lienProduit ?? 'Aucun lien fourni';
        $demande->idEtat = '1';
        $demande->idPersonnel = $donnees->idPersonnel;

        $demande->save();

        return ['message' => 'La création à bien été effectué'];
    }

    static function modifier($id)
    {
        $donnees = json_decode(file_get_contents("php://input"));

        $ancienneDonnees = DemandesSpecifiques::select('*')->where('id', $id)->get();

        $Demande = DemandesSpecifiques::select('*')->where('id', $id)->update([
            'nomDemande' => $donnees->nomDemande ?? $ancienneDonnees[0]->nomDemande,
            'quantiteDemande' => $donnees->quantiteDemande ?? $ancienneDonnees[0]->quantiteDemande,
            'lienProduit' => $donnees->lienProduit ?? $ancienneDonnees[0]->lienProduit,
            'idEtat' => $donnees->idEtat ?? $donnees[0]->idEtat,
            'idPersonnel' => $donnees->idPersonnel ?? $ancienneDonnees[0]->idPersonnel
        ]);

        return ['message' => 'La modification a bien été effectué'];
    }
}
