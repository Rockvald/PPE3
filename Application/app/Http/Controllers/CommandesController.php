<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commandes;
use App\Models\Personnel;
use App\Models\Fournitures;

class CommandesController extends Controller
{
    public function afficher()
    {
        if (session_status() == 1) {
            session_start();
        }

        if (!isset($_SESSION['mail'])) {
            header('Refresh: 0; url='.url('?page=suivi'));
            exit;
        }

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesCommandes = Commandes::donneesCommandes();

        return view('suivi', ['donneesPersonnel' => $donneesPersonnel, 'donneesCommandes' => $donneesCommandes]);
    }

    public function commander(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'nom_fourniture' => 'required',
            'quantite_demande' => 'required|min:1'
        ]);

        session_start();

        $id_personnel = Personnel::where('mail', $_SESSION['mail'])->select('id')->get();

        $quantite = Fournitures::where('id', $request->id)->select('quantiteDisponible')->get();

        $nouv_quantite = $quantite[0]->quantiteDisponible - $request->quantite_demande;

        $majquantite = Fournitures::where('id', $request->id)->update(['quantiteDisponible' => $nouv_quantite]);

        $Commandes = new Commandes;

        $Commandes->idEtat = '1';
        $Commandes->idFournitures = $request->id;
        $Commandes->idPersonnel = $id_personnel[0]->id;
        $Commandes->nomCommandes = $request->nom_fourniture;
        $Commandes->quantiteDemande = $request->quantite_demande;

        $Commandes->save();

        $donneesPersonnel = Personnel::donneesPersonnel();
        $donneesFourniture = Fournitures::donneesFourniture();

        $commande_cree = true;

        return view('fournitures', ['commande_cree' => $commande_cree, 'donneesPersonnel' => $donneesPersonnel, 'donneesFourniture' => $donneesFourniture]);
    }
}
