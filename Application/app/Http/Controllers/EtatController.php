<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etat;
use App\Models\DemandesSpecifiques;
use App\Models\Commandes;
use App\Models\Personnel;

class EtatController extends Controller
{
    public function majetatdemande(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'id_etat' => 'required|min:1|max:5',
        ]);

        session_start();

        $DemandesSpecifiques = DemandesSpecifiques::where('id', $request->id)->update(['idEtat' => $request->id_etat]);

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesDemandes = DemandesSpecifiques::donneesDemandes();

        $envoyer = true;

        return view('demandesspecifiques', ['envoyer' => $envoyer, 'donneesPersonnel' => $donneesPersonnel, 'donneesDemandes' => $donneesDemandes]);
    }

    public function majetatcommande(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'id_etat' => 'required|min:1|max:5',
        ]);

        session_start();

        $Commandes = Commandes::where('id', $request->id)->update(['idEtat' => $request->id_etat]);

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesCommandes = Commandes::donneesCommandes();

        $envoyer = true;

        return view('suivi', ['envoyer' => $envoyer, 'donneesPersonnel' => $donneesPersonnel, 'donneesCommandes' => $donneesCommandes]);
    }
}
