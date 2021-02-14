<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemandesSpecifiques;
use App\Models\Personnel;

class DemandesSpecifiquesController extends Controller
{
    public function afficher()
    {
        if (session_status() == 1) {
            session_start();
        }

        if (!isset($_SESSION['mail'])) {
            header('Refresh: 0; url='.url('?page=demandesspecifiques'));
            exit;
        }

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesDemandes = DemandesSpecifiques::donneesDemandes();

        return view('demandesspecifiques', ['donneesPersonnel' => $donneesPersonnel, 'donneesDemandes' => $donneesDemandes]);
    }

    public function creation(Request $request)
    {
        $validatedData = $request->validate([
            'nom_demande' => 'required',
            'quantite_demande' => 'required|min:1|max:50',
        ]);

        session_start();

        $id_personnel = Personnel::where('mail', $_SESSION['mail'])->select('id')->get();

        $DemandesSpecifiques = new DemandesSpecifiques;

        $DemandesSpecifiques->nomDemande = $request->nom_demande;
        $DemandesSpecifiques->quantiteDemande = $request->quantite_demande;
        $DemandesSpecifiques->lienProduit = $request->lien_produit ?? 'Aucun lien fourni';
        $DemandesSpecifiques->idEtat = '1';
        $DemandesSpecifiques->idPersonnel = $id_personnel[0]->id;

        $DemandesSpecifiques->save();

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesDemandes = DemandesSpecifiques::donneesDemandes();

        $vrai = true;

        return view('demandesspecifiques', ['vrai' => $vrai, 'donneesPersonnel' => $donneesPersonnel, 'donneesDemandes' => $donneesDemandes]);
    }
}
