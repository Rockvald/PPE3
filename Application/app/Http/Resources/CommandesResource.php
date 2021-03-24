<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Commandes;

class CommandesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public static $wrap = 'commande';

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'idEtat' => $this->idEtat,
            'idFournitures' => $this->idFournitures,
            'idPersonnel' => $this->idPersonnel,
            'nomCommandes' => $this->nomCommandes,
            'quantiteDemande' => $this->quantiteDemande,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    static function creer()
    {
        $donnees = json_decode(file_get_contents("php://input"));

        $commande = new Commandes;

        $commande->nomCommandes = $donnees->nomCommandes;
        $commande->quantiteDemande = $donnees->quantiteDemande;
        $commande->idEtat = '1';
        $commande->idFournitures = $donnees->idFournitures;
        $commande->idPersonnel = $donnees->idPersonnel;

        $commande->save();

        return ['message' => 'Commande bien prise en compte'];
    }

    static function modifier($id)
    {
        $donnees = json_decode(file_get_contents("php://input"));

        $ancienneDonnees = Commandes::select('*')->where('id', $id)->get();

        $Commande = Commandes::select('*')->where('id', $id)->update([
            'nomCommandes' => $donnees->nomCommandes ?? $ancienneDonnees[0]->nomCommandes,
            'quantiteDemande' => $donnees->quantiteDemande ?? $ancienneDonnees[0]->quantiteDemande,
            'idEtat' => $donnees->idEtat ?? $ancienneDonnees->idEtat,
            'idFournitures' => $donnees->idFournitures ?? $ancienneDonnees[0]->idFournitures,
            'idPersonnel' => $donnees->idPersonnel ?? $ancienneDonnees[0]->idPersonnel
        ]);

        return ['message' => 'La modification a bien été effectué'];
    }
}
