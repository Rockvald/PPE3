        <footer>
            @if (isset($commandes_fini[0]))
                <table id="commandes_fini">
                    <caption>Historique des commandes</caption>
                    <tr>
                        <th class="tabl_comm">Nom</th>
                        <th class="tabl_comm">Quantitée demandée</th>
                        <th class="tabl_comm">État</th>
                        <th class="tabl_comm">Dernière mise à jour</th>
                    </tr>
                @foreach ($commandes_fini as $lignes => $commande_enFini)
                    <tr>
                        <td class="tabl_comm">{{ $commande_enFini->nomCommandes }}</td>
                        <td class="tabl_comm">{{ $commande_enFini->quantiteDemande }}</td>
                        <td class="tabl_comm">{{ $commande_enFini->nomEtat }}</td>
                        <td class="tabl_comm">{{ date('G:i:s \l\e d-m-Y', strtotime($commande_enFini->updated_at)) }}</td>
                    </tr>
                @endforeach
                </table>
            @endif
            <p id="service">Vous êtes dans le service : {{ $Personnel[0]->nomService }}</p>
            <p id="categorie">Votre rôle est : {{ $Personnel[0]->nomCategorie }}</p>
        </footer>
    </body>
</html>
