@php $css = 'suivi'; $title_h1 = 'Suivi'; @endphp
@include('head')
@include('menus')
@include('header')
        <div id="bouton_imprimer"><button onclick="imprimer('corps');">Imprimer</button></div>
        <section id="corps">
            @if ($_SESSION['categorie'] == 'Administrateur')
                @if (isset($_SESSION['commande_complet'][0]))
                    <div id="choix_departements">
                        <p id="departements">Départements :</p>
                        <select id="select_services" onchange="selectionServices('suivi')">
                            <option value="Tous">Tous</option>
                        @foreach ($_SESSION['services'] as $lignes => $service)
                            @if (isset($_GET['service']) AND $_GET['service'] == $service->nomService)
                                <option value="{{ $service->nomService }}" selected >{{ $service->nomService }}</option>
                            @else
                                <option value="{{ $service->nomService }}" >{{ $service->nomService }}</option>
                            @endif
                        @endforeach
                        </select>
                    </div>

                    @foreach ($_SESSION['services'] as $lignes => $service)
                        @if (isset($_GET['service']) AND $_GET['service'] == $service->nomService)
                            @php ($nom = $_GET['service'])
                        @endif
                    @endforeach
                    @php ($nomService = $nom ?? 'commande_complet')

                    @php ($envoye = $envoyer ?? false)
                    @if ($envoye)
                        <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> La commande à bien été mise à jour</p><br />
                        @php (header('Refresh: 5; url=suivi'))
                    @endif

                    @if (isset($_SESSION["$nomService"][0]))
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
                                @foreach ($_SESSION["$nomService"] as $lignes => $nomservice)
                                    @if (($nomservice->nomEtat == 'Validé' OR $nomservice->nomEtat == 'En cours') AND !isset($afficher))
                                        {{ 'Modifier l\'état' }}
                                        @php ($afficher = true)
                                    @endif
                                @endforeach
                                </th>
                            </tr>
                        @foreach ($_SESSION["$nomService"] as $lignes => $nomservice)
                            <tr>
                                <td>{{ $nomservice->nom }}</td>
                                <td>{{ $nomservice->prenom }}</td>
                                <td>{{ $nomservice->nomCommandes }}</td>
                                <td>{{ $nomservice->quantiteDemande }}</td>
                                <td>{{ $nomservice->nomEtat }}</td>
                                <td>{{ date('G:i:s \l\e d-m-Y', strtotime($nomservice->created_at)) }}</td>
                                <td>{{ date('G:i:s \l\e d-m-Y', strtotime($nomservice->updated_at)) }}</td>
                                <td>
                                @if ($nomservice->nomEtat == 'Validé')
                                    {!! Form::open(['url' => 'majetatcommande']) !!}
                                    {{ Form::hidden('id', $nomservice->id) }}
                                    {{ Form::hidden('id_etat', '3') }}
                                    {{ Form::submit('En Cours') }}
                                    {!! Form::close() !!}
                                @elseif ($nomservice->nomEtat == 'En cours')
                                    {!! Form::open(['url' => 'majetatcommande', 'id' => 'livre']) !!}
                                    {{ Form::hidden('id', $nomservice->id) }}
                                    {{ Form::hidden('id_etat', '4') }}
                                    {{ Form::submit('Livré') }}
                                    {!! Form::close() !!}

                                    {!! Form::open(['url' => 'majetatcommande']) !!}
                                    {{ Form::hidden('id', $nomservice->id) }}
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
                @if (isset($_SESSION['commande_utilisateur'][0]))
                    <div id="choix_etat">
                        <p id="etats">États :</p>
                        <select id="select_etat" onchange="selectionEtat('suivi')">
                            <option value="Tous">Tous</option>
                        @foreach ($_SESSION['etats'] as $lignes => $etat)
                            @if (isset($_GET['etat']) AND $_GET['etat'] == $etat->nomEtat)
                                <option value="{{ $etat->nomEtat }}" selected >{{ $etat->nomEtat }}</option>
                            @else
                                <option value="{{ $etat->nomEtat }}" >{{ $etat->nomEtat }}</option>
                            @endif
                        @endforeach
                        </select>
                    </div>

                    @foreach ($_SESSION['etats'] as $lignes => $etat)
                        @if (isset($_GET['etat']) AND $_GET['etat'] == $etat->nomEtat)
                            @php ($nom = $_GET['etat'])
                        @endif
                    @endforeach
                    @php ($nomEtat = $nom ?? 'commande_utilisateur')

                    @php ($envoye = $envoyer ?? false)
                    @if ($envoye)
                        <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> La commande à bien été mise à jour</p><br />
                        @php (header('Refresh: 5; url=suivi'))
                    @endif

                    @if (isset($_SESSION["$nomEtat"][0]))
                        <table id="liste_commandes">
                            <caption>Liste des commandes</caption>
                            <tr>
                                <th>Nom de la commande</th>
                                <th>Quantitée demandée</th>
                                <th>État</th>
                                <th>Création</th>
                                <th>Dernière mise à jour</th>
                            </tr>
                        @foreach ($_SESSION["$nomEtat"] as $lignes => $nometat)
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

                @if ($_SESSION['categorie'] == 'Valideur')
                    @if (isset($_SESSION['commande_valid'][0]))
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
                                @foreach ($_SESSION['commande_valid'] as $lignes => $commande_valid)
                                    @if (($commande_valid->nomEtat == 'Prise en compte') AND !isset($afficher))
                                        {{ 'Modifier l\'état' }}
                                        @php ($afficher = true)
                                    @endif
                                @endforeach
                                </th>
                            </tr>
                            @foreach ($_SESSION['commande_valid'] as $lignes => $commande_valid)
                                <tr>
                                    <td>{{ $commande_valid->nom }}</td>
                                    <td>{{ $commande_valid->prenom }}</td>
                                    <td>{{ $commande_valid->nomCommandes }}</td>
                                    <td>{{ $commande_valid->quantiteDemande }}</td>
                                    <td>{{ $_SESSION["commande_valid"][$n]->nomEtat }}</td>
                                    <td>{{ date('G:i:s \l\e d-m-Y', strtotime($commande_valid->created_at)) }}</td>
                                    <td>{{ date('G:i:s \l\e d-m-Y', strtotime($commande_valid->updated_at)) }}</td>
                                    <td>
                                        @if ($_SESSION["commande_valid"][$n]->nomEtat == 'Prise en compte')
                                            {!! Form::open(['url' => 'majetatcommande']) !!}
                                            {{ Form::hidden('id', $commande_valid->id) }}
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
