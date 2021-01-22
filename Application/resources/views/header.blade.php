        <header>
            <h1>{{ $title_h1 ?? 'CCI' }}</h1>
            {!! Form::open(['url' => 'rechercher']) !!}
            {{ Form::search('recherche', $value = null, ['id'=>'recherche', 'placeholder'=>'Recherche', 'required']) }}
            {{ Form::image(asset('storage/app/public/icon-search.png'), 'envoyer', ['id'=>'envoyer', 'alt'=>'Icone de loupe']) }}
            {!! Form::close() !!}
            <div id="nom_deconnexion">
                <p id="nom_prenom">{{ $Personnel[0]->prenom }} {{ $Personnel[0]->nom }}</p>
                <button type="button" name="deconnexion" id="deconnexion" onclick="window.location.href='deconnexion'">Se déconnecter</button>
            </div>
            @if (isset($commande[0]))
                <table id="commandes_cours">
                    <caption>Commandes en cours</caption>
                    <tr>
                        <th class="tabl_comm">Nom</th>
                        <th class="tabl_comm">Quantitée demandée</th>
                        <th class="tabl_comm">État</th>
                        <th class="tabl_comm">Dernière mise à jour</th>
                    </tr>
                @foreach ($commande as $lignes => $commande_enCours)
                    <tr>
                        <td class="tabl_comm">{{ $commande_enCours->nomCommandes }}</td>
                        <td class="tabl_comm">{{ $commande_enCours->quantiteDemande }}</td>
                        <td class="tabl_comm">{{ $commande_enCours->nomEtat }}</td>
                        <td class="tabl_comm">{{ date('G:i:s \l\e d-m-Y', strtotime($commande_enCours->updated_at)) }}</td>
                    </tr>
                @endforeach
                </table>
            @endif
        </header>
