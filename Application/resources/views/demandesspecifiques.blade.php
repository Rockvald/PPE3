@php $css = 'demandesspecifiques'; $title_h1 = "Demandes spécifiques"; @endphp
@include('head')
@include('menus')
@include('header')
        @if ($_SESSION['categorie'] == 'Administrateur')
            <div id="bouton_imprimer"><button onclick="imprimer('corps');">Imprimer</button></div>
        @endif
        <section id="corps">
            @if ($_SESSION['categorie'] != 'Administrateur')
                @php ($confirm = $vrai ?? false)
                @if ($confirm)
                    <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> Votre demande a bien été envoyée</p><br />
                    @php (header('Refresh: 5; url=demandesspecifiques'))
                @endif

                @php ($envoye = $envoyer ?? false)
                @if ($envoye)
                    <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> La demande à bien été mise à jour</p><br />
                    @php (header('Refresh: 5; url=demandesspecifiques'))
                @endif

                <h4>Effectuer une demande spécifique :</h4><br />
                {!! Form::open(['url' => 'creationdemande']) !!}
                {{ Form::label('nom_demande', 'Nom de la demande :') }}
                {{ Form::text('nom_demande', $value = null, ['maxlength'=>'50', 'placeholder'=>'Ex: Lampe de projecteur', 'required']) }}
                {{ Form::label('quantite_demande', 'Quantitée demandée :') }}
                {{ Form::number('quantite_demande', '1', ['min'=>'1', 'max'=>'50']) }}
                {{ Form::label('lien_produit', 'Lien vers le produit :') }}
                {{ Form::text('lien_produit', $value = null, ['maxlength'=>'200', 'placeholder'=>'Optionnel']) }}
                {{ Form::submit('Envoyer la demande') }}
                {!! Form::close() !!}

                @if ($_SESSION['categorie'] == 'Valideur')
                    @if (isset($_SESSION['demandes_valid'][0]))
                        <table id="tab_ut">
                            <caption class="titre_demande">Liste des demandes des utilisateurs</caption>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Nom de la demande</th>
                                <th>Quantitée demandée</th>
                                <th>Lien du produit</th>
                                <th>État</th>
                                <th>Création</th>
                                <th>Dernière mise à jour</th>
                                <th>
                                @foreach ($_SESSION['demandes_valid'] as $lignes => $demandes_valid)
                                    @if (($demandes_valid->nomEtat == 'Prise en compte') AND !isset($afficher))
                                        {{ 'Modifier l\'état' }}
                                        @php ($afficher = true)
                                    @endif
                                @endforeach
                                </th>
                            </tr>
                            @foreach ($_SESSION['demandes_valid'] as $lignes => $demandes_valid)
                                <tr>
                                    <td>{{ $demandes_valid->nom }}</td>
                                    <td>{{ $demandes_valid->prenom }}</td>
                                    <td>{{ $demandes_valid->nomDemande }}</td>
                                    <td>{{ $demandes_valid->quantiteDemande }}</td>
                                    <td class="lien_produit">
                                        @if ($demandes_valid->lienProduit != 'Aucun lien fourni')
                                            <a href="{{ $demandes_valid->lienProduit }}" target="_blank">{{ $demandes_valid->lienProduit }}</a>
                                        @else
                                            {{ $demandes_valid->lienProduit }}
                                        @endif
                                    </td>
                                    <td>{{ $demandes_valid->nomEtat }}</td>
                                    <td>{{ date('G:i:s \l\e d-m-Y', strtotime($demandes_valid->created_at)) }}</td>
                                    <td>{{ date('G:i:s \l\e d-m-Y', strtotime($demandes_valid->updated_at)) }}</td>
                                    <td>
                                    @if ($demandes_valid->nomEtat == 'Prise en compte')
                                        {!! Form::open(['url' => 'majetatdemande']) !!}
                                        {{ Form::hidden('id', $demandes_valid->id) }}
                                        {{ Form::hidden('id_etat', '2') }}
                                        {{ Form::submit('Valider') }}
                                        {!! Form::close() !!}
                                    @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <section id="demandes_utilisateur">
                            <h4>Demandes utilisateurs :</h4><br />
                            <p>Vous n'avez pas de demandes d'utilisateurs.</p>
                        </section>
                    @endif
                @endif
                @if (isset($_SESSION['demandes_pers'][0]))
                    <table id="demandes_pers">
                        <caption class="titre_demande">Liste des demandes effectuées :</caption>
                        <tr>
                            <th>Nom de la demande</th>
                            <th>Quantitée demandée</th>
                            <th>Lien du produit</th>
                            <th>État</th>
                            <th>Création</th>
                            <th>Dernière mise à jour</th>
                        </tr>
                    @foreach ($_SESSION['demandes_pers'] as $lignes => $demandes_pers)
                        <tr>
                            <td>{{ $demandes_pers->nomDemande }}</td>
                            <td>{{ $demandes_pers->quantiteDemande }}</td>
                            <td class="lien_produit">
                                @if ($demandes_pers->lienProduit != 'Aucun lien fourni')
                                    <a href="{{ $demandes_pers->lienProduit }}" target="_blank">{{ $demandes_pers->lienProduit }}</a>
                                @else
                                    {{ $demandes_pers->lienProduit }}
                                @endif
                            </td>
                            <td>{{ $demandes_pers->nomEtat }}</td>
                            <td>{{ date('G:i:s \l\e d-m-Y', strtotime($demandes_pers->created_at)) }}</td>
                            <td>{{ date('G:i:s \l\e d-m-Y', strtotime($demandes_pers->updated_at)) }}</td>
                        </tr>
                    @endforeach
                    </table>
                @else
                    <section id="demandes_spec">
                        <h4>Demandes spécifiques :</h4><br />
                        <p>Vous n'avez pas encore effectué de demandes.</p>
                    </section>
                @endif
            @else
                @if (isset($_SESSION['demandes'][0]))
                    <div id="choix_departements">
                        <p id="departements">Départements :</p>
                        <select id="select_services" onchange="selectionServices('demandesspecifiques')">
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
                    @php ($nomService = $nom ?? 'demandes')

                    @php ($envoye = $envoyer ?? false)
                    @if ($envoye)
                        <p id="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> La demande à bien été mise à jour</p><br />
                        @php (header('Refresh: 5; url=demandesspecifiques'))
                    @endif

                    @if (isset($_SESSION["$nomService"][0]))
                        <table id="demandes_list">
                            <caption>Liste des demandes spécifiques</caption>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Nom de la demande</th>
                                <th>Quantitée demandée</th>
                                <th>Lien du produit</th>
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
                                <td>{{ $nomservice->nomDemande }}</td>
                                <td>{{ $nomservice->quantiteDemande }}</td>
                                <td class="lien_produit">
                                    @if ($nomservice->lienProduit != 'Aucun lien fourni')
                                        <a href="{{ $nomservice->lienProduit }}" target="_blank">{{ $nomservice->lienProduit }}</a>
                                    @else
                                        {{ $nomservice->lienProduit }}
                                    @endif
                                </td>
                                <td>{{ $nomservice->nomEtat }}</td>
                                <td>{{ date('G:i:s \l\e d-m-Y', strtotime($nomservice->created_at)) }}</td>
                                <td>{{ date('G:i:s \l\e d-m-Y', strtotime($nomservice->updated_at)) }}</td>
                                <td>
                                @if ($nomservice->nomEtat == 'Validé')
                                    {!! Form::open(['url' => 'majetatdemande']) !!}
                                    {{ Form::hidden('id', $nomservice->id) }}
                                    {{ Form::hidden('id_etat', '3') }}
                                    {{ Form::submit('En Cours') }}
                                    {!! Form::close() !!}
                                @elseif ($nomservice->nomEtat == 'En cours')
                                    {!! Form::open(['url' => 'majetatdemande', 'id' => 'livre']) !!}
                                    {{ Form::hidden('id', $nomservice->id) }}
                                    {{ Form::hidden('id_etat', '4') }}
                                    {{ Form::submit('Livré') }}
                                    {!! Form::close() !!}

                                    {!! Form::open(['url' => 'majetatdemande']) !!}
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
                        <section id="demande_util_dep">
                            <h4>Liste des demandes des utilisateurs :</h4><br />
                            <p>Il n'y a pas encore de demandes d'utilisateurs de ce département.</p>
                        </section>
                    @endif
                @else
                    <section id="demande_util">
                        <h4>Liste des demandes des utilisateurs :</h4><br />
                        <p>Il n'y a pas encore de demandes d'utilisateurs.</p>
                    </section>
                @endif
            @endif
        </section>
@include('footer')
