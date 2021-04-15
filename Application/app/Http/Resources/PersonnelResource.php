<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Personnel;

class PersonnelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public static $wrap = 'personnel';

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'idCategorie' => $this->idCategorie,
            'idService' => $this->idService,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'mail' => $this->mail,
            'pass' => $this->pass,
            'message' => $this->message,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    static function afficher()
    {
        $donnees = json_decode(file_get_contents("php://input"));

        $Personnel = Personnel::where('mail', $donnees->mail)->get();

        return [
            'id' => $Personnel[0]->id,
            'idCategorie' => $Personnel[0]->idCategorie,
            'idService' => $Personnel[0]->idService,
            'nom' => $Personnel[0]->nom,
            'prenom' => $Personnel[0]->prenom,
            'mail' => $Personnel[0]->mail,
            'pass' => $Personnel[0]->pass,
            'message' => $Personnel[0]->message,
            'created_at' => $Personnel[0]->created_at,
            'updated_at' => $Personnel[0]->updated_at
        ];
    }

    static function creer()
    {
        $donnees = json_decode(file_get_contents("php://input"));

        $Personnel = new Personnel;

        $Personnel->nom = $donnees->nom;
        $Personnel->prenom = $donnees->prenom;
        $Personnel->mail = $donnees->mail;
        $Personnel->pass = password_hash($donnees->pass, PASSWORD_DEFAULT);
        $Personnel->idCategorie = $donnees->idCategorie;
        $Personnel->idService = $donnees->idService;
        $Personnel->message = '';

        $Personnel->save();

        return ['message' => 'La création à bien été effectué'];
    }

    static function modifier($id)
    {
        $donnees = json_decode(file_get_contents("php://input"));
        if (isset($donnees->pass)) {
            $pass = hash('sha256', $donnees->pass);
        }

        $ancienneDonnees = Personnel::select('*')->where('id', $id)->get();

        $Personnel = Personnel::select('*')->where('id', $id)->update([
            'nom' => $donnees->nom ?? $ancienneDonnees[0]->nom,
            'prenom' => $donnees->prenom ?? $ancienneDonnees[0]->prenom,
            'mail' => $donnees->mail ?? $ancienneDonnees[0]->mail,
            'pass' => $pass ?? $ancienneDonnees[0]->pass,
            'idCategorie' => $donnees->idCategorie ?? $ancienneDonnees[0]->idCategorie,
            'idService' => $donnees->idService ?? $ancienneDonnees[0]->idService,
            'message' => $donnees->message ?? $ancienneDonnees[0]->message
        ]);

        return ['message' => 'La modification a bien été effectué'];
    }
}
