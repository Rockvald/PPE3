<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Service;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public static $wrap = 'service';

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nomService' => $this->nomService,
            'descriptionService' => $this->descriptionService,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
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

        $ancienneDonnees = Service::select('*')->where('id', $id)->get();

        $Service = Service::select('*')->where('id', $id)->update([
            'nomService' => $donnees->nomService ?? $ancienneDonnees[0]->nomService,
            'descriptionService' => $donnees->descriptionService ?? $ancienneDonnees[0]->descriptionService
        ]);

        return ['message' => 'La modification a bien été effectué'];
    }
}
