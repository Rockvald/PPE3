@php $css = 'suivi'; $title_h1 = 'Suivi'; @endphp
@include('head')
@include('menus')
@include('header')
        <div id="bouton_imprimer"><button onclick="imprimer('corps');">Imprimer</button></div>
        <section id="corps">
            @if ($Personnel[0]->nomCategorie == 'Administrateur')
                @if (isset($commande_complet[0]))
                    <div id="choix_departements">
                        <p id="departements">Départements :</p>
                        <select id="select_services" onchange="selectionServices('suivi')">
                            <option value="Tous">Tous</option>
                        @foreach ($services as $lignes => $service)
                            @if (isset($_GET['service']) AND $_GET['service'] == $service->nomService)
                                <option value="{{ $service->nomService }}" selected >{{ $service->nomService }}</option>
                            @else
                                <option value="{{ $service->nomService }}" >{{ $service->nomService }}</option>
                            @endif
                        @endforeach
                        </select>
                    </div>

                    @foreach ($services as $lignes => $service)
                        @if (isset($_GET['service']) AND $_GET['service'] == $service->nomService)
                            @php ($nom = $_GET['service'])
                        @endif
                    @endforeach
                    @php ($nomService = $nom ?? '')

                    @if ($nomService == '')
                        @php ($commandes = $commande_complet)
                    @else
                        @php ($commandes = $donnesService["$nomService"])
                    @endif

                    @php ($envoye = $envoyer ?? false)
                    @if ($envoye)
                        <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> La commande à bien été mise à jour</p><br />
                        @php (header('Refresh: 5; url=suivi'))
                    @endif

                    @if (isset($commandes[0]))
                        <table id="liste_commandes_utilisateur">
                            <caption>Liste des commandes des utilisateurs</caption>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Nom de la commande</th>
                                <th>Quantitée demandée</th>
                                <th>État</th>
                                <th>Création</th>
                                <th>Dernière mise à jour</th>
                                <th>
                                @foreach ($commandes as $lignes => $commande)
                                    @if (($commande->nomEtat == 'Validé' OR $commande->nomEtat == 'En cours') AND !isset($afficher))
                                        {{ 'Modifier l\'état' }}
                                        @php ($afficher = true)
                                    @endif
                                @endforeach
                                </th>
                            </tr>
                        @foreach ($commandes as $lignes => $commande)
                            <tr>
                                <td>{{ $commande->nom }}</td>
                                <td>{{ $commande->prenom }}</td>
                                <td>{{ $commande->nomCommandes }}</td>
                                <td>{{ $commande->quantiteDemande }}</td>
                                <td>{{ $commande->nomEtat }}</td>
                                <td>{{ date('G:i:s \l\e d-m-Y', strtotime($commande->created_at)) }}</td>
                                <td>{{ date('G:i:s \l\e d-m-Y', strtotime($commande->updated_at)) }}</td>
                                <td>
                                @if ($commande->nomEtat == 'Validé')
                                    {!! Form::open(['url' => 'majetatcommande']) !!}
                                    {{ Form::hidden('id', $commande->id) }}
                                    {{ Form::hidden('id_etat', '3') }}
                                    {{ Form::submit('En Cours') }}
                                    {!! Form::close() !!}
                                @elseif ($commande->nomEtat == 'En cours')
                                    {!! Form::open(['url' => 'majetatcommande', 'id' => 'livre']) !!}
                                    {{ Form::hidden('id', $commande->id) }}
                                    {{ Form::hidden('id_etat', '4') }}
                                    {{ Form::submit('Livré') }}
                                    {!! Form::close() !!}

                                    {!! Form::open(['url' => 'majetatcommande']) !!}
                                    {{ Form::hidden('id', $commande->id) }}
                                    {{ Form::hidden('id_etat', '5') }}
                                    {{ Form::submit('Annulé') }}
                                    {!! Form::close() !!}
                                @endif
                                </td>
                            </tr>
                        @endforeach
                        </table>
                    @else
                        <section id="commande_util_dep">
                            <h4>Liste des commandes des utilisateurs :</h4><br />
                            <p>Il n'y a pas encore de commandes d'utilisateurs de ce département.</p>
                        </section>
                    @endif
                @else
                    <section id="commande_util">
                        <h4>Liste des commandes des utilisateurs :</h4><br />
                        <p>Il n'y a pas encore de commandes d'utilisateurs.</p>
                    </section>
                @endif
            @else
                @if (isset($commande_utilisateur[0]))
                    <div id="choix_etat">
                        <p id="etats">États :</p>
                        <select id="select_etat" onchange="selectionEtat('suivi')">
                            <option value="Tous">Tous</option>
                        @foreach ($etats as $lignes => $etat)
                            @if (isset($_GET['etat']) AND $_GET['etat'] == $etat->nomEtat)
                                <option value="{{ $etat->nomEtat }}" selected >{{ $etat->nomEtat }}</option>
                            @else
                                <option value="{{ $etat->nomEtat }}" >{{ $etat->nomEtat }}</option>
                            @endif
                        @endforeach
                        </select>
                    </div>

                    @foreach ($etats as $lignes => $etat)
                        @if (isset($_GET['etat']) AND $_GET['etat'] == $etat->nomEtat)
                            @php ($nom = $_GET['etat'])
                        @endif
                    @endforeach
                    @php ($nomEtat = $nom ?? '')

                    @if ($nomEtat == '')
                        @php ($commandes = $commande_utilisateur)
                    @else
                        @php ($commandes = $donnesEtat["$nomEtat"])
                    @endif

                    @php ($envoye = $envoyer ?? false)
                    @if ($envoye)
                        <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> La commande à bien été mise à jour</p><br />
                        @php (header('Refresh: 5; url=suivi'))
                    @endif

                    @if (isset($commandes[0]))
                        <table id="liste_commandes">
                            <caption>Liste des commandes</caption>
                            <tr>
                                <th>Nom de la commande</th>
                                <th>Quantitée demandée</th>
                                <th>État</th>
                                <th>Création</th>
                                <th>Dernière mise à jour</th>
                            </tr>
                        @foreach ($commandes as $lignes => $nometat)
                            <tr>
                                <td>{{ $nometat->nomCommandes }}</td>
                                <td>{{ $nometat->quantiteDemande }}</td>
                                <td>{{ $nometat->nomEtat }}</td>
                                <td>{{ date('G:i:s \l\e d-m-Y', strtotime($nometat->created_at)) }}</td>
                                <td>{{ date('G:i:s \l\e d-m-Y', strtotime($nometat->updated_at)) }}</td>
                            </tr>
                        @endforeach
                        </table>
                    @else
                        <section id="commande_pers_etat">
                            <h4>Liste des commandes :</h4><br />
                            <p>Vous n'avez pas de commandes avec cet état.</p>
                        </section>
                    @endif
                @else
                    <section id="commande_pers">
                        <h4>Liste des commandes :</h4><br />
                        <p>Vous n'avez pas encore effectué de commandes.</p>
                    </section>
                @endif

                @if ($Personnel[0]->nomCategorie == 'Valideur')
                    @if (isset($commande_valid[0]))
                        <table id="liste_commandes_valid">
                            <caption>Liste des commandes des utilisateurs</caption>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Nom de la commande</th>
                                <th>Quantitée demandée</th>
                                <th>État</th>
                                <th>Création</th>
                                <th>Dernière mise à jour</th>
                                <th>
                                @foreach ($commande_valid as $lignes => $commande)
                                    @if (($commande->nomEtat == 'Prise en compte') AND !isset($afficher))
                                        {{ 'Modifier l\'état' }}
                                        @php ($afficher = true)
                                    @endif
                                @endforeach
                                </th>
                            </tr>
                            @foreach ($commande_valid as $lignes => $commande)
                                <tr>
                                    <td>{{ $commande->nom }}</td>
                                    <td>{{ $commande->prenom }}</td>
                                    <td>{{ $commande->nomCommandes }}</td>
                                    <td>{{ $commande->quantiteDemande }}</td>
                                    <td>{{ $commande->nomEtat }}</td>
                                    <td>{{ date('G:i:s \l\e d-m-Y', strtotime($commande->created_at)) }}</td>
                                    <td>{{ date('G:i:s \l\e d-m-Y', strtotime($commande->updated_at)) }}</td>
                                    <td>
                                        @if ($commande->nomEtat == 'Prise en compte')
                                            {!! Form::open(['url' => 'majetatcommande']) !!}
                                            {{ Form::hidden('id', $commande->id) }}
                                            {{ Form::hidden('id_etat', '2') }}
                                            {{ Form::submit('Valider') }}
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <section id="commande_valid">
                            <h4>Liste des commandes des utilisateurs :</h4><br />
                            <p>Il n'y a pas encore de commandes d'utilisateurs.</p>
                        </section>
                    @endif
                @endif
            @endif
        </section>
@include('footer')
