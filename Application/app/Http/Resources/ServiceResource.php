<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Service;
use App\Models\Personnel;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public static $wrap = 'service';
    public $nomValideur;
    public $mailValideur;

    public function toArray($request)
    {
        $Personnels = Personnel::join('services', 'personnels.idService', 'services.id')->join('categories', 'personnels.idCategorie', 'categories.id')->select('*')->orderby('personnels.id', 'asc')->get();

        foreach ($Personnels as $Personnel) {
            if ($this->id == $Personnel->idService) {
                $this->nomValideur = $Personnel->nom.' '.$Personnel->prenom;
                $this->mailValideur = $Personnel->mail;
            }
        }

        return [
            'id' => $this->id,
            'nomService' => $this->nomService,
            'descriptionService' => $this->descriptionService,
            'nomValideur' => $this->nomValideur,
            'mailValideur' => $this->mailValideur,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    static function creer()
    {
        $donnees = json_decode(file_get_contents("php://input"));

        $service = new Service;
        $service->nomService = $donnees->nomService;
        $service->descriptionService = $donnees->descriptionService;
        $service->save();

        return ['message' => 'La création à bien été effectué'];
    }

    static function modifier($id)
    {
        $donnees = json_decode(file_get_contents("php://input"));

        $service = Service::select('services.*')->where('id', $id)->get();

        $Service = Service::select('services.*')->where('id', $id)->update([
            'nomService' => $donnees->nomService ?? $service[0]->nomService,
            'descriptionService' => $donnees->descriptionService ?? $service[0]->descriptionService
        ]);

        return ['message' => 'La modification a bien été effectué'];
    }
}
