@php $css = 'departements'; $title_h1 = 'Départements'; @endphp
@include('head')
@include('menus')
@include('header')
        <section id="corps">
            @if ($_SESSION['categorie'] == 'Administrateur')
                @php ($creation = $cree ?? false)
                @if ($creation)
                    <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> Le département a bien été créé</p><br />
                    @php (header('Refresh: 5; url=departements'))
                @endif

                @php ($valide = $valider ?? false)
                @if ($valide)
                    <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> La mise à jour à bien été prise en compte</p><br />
                    @php (header('Refresh: 5; url=departements'))
                @endif

                <table id="ajout_service">
                    <caption>Ajouter un département</caption>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <!--<th>Valideur rattaché</th>-->
                        <th></th>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::open(['url' => 'creationdepartement']) !!}
                            {{ Form::text('nom_departement', $value = null, ['maxlength'=>'50', 'placeholder'=>'Ex: Direction', 'required']) }}
                        </td>
                        <td>{{ Form::text('description_departement', $value = null, ['maxlength'=>'50', 'required']) }}</td>
                        <!--<td>
                            <select name="mail">
                                <option value="Aucun">Aucun</option>
                                {{-- @foreach ($_SESSION['personnels'] as $lignes => $personnel)
                                    <option value="{{ $personnel->mail }}" >{{ $personnel->mail }}</option>
                                @endforeach --}}
                            </select>
                        </td>-->
                        <td>
                            {{ Form::submit('Créer le département') }}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                </table>

                <table id="service_complet">
                    <caption>Liste des départements</caption>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Valideur rattaché</th>
                        <th>Contact du valideur</th>
                        <th></th>
                    </tr>
                    @foreach ($_SESSION['services'] as $lignes => $service)
                        <tr>
                            <td>{{ $service->nomService }}</td>
                            <td>{{ $service->descriptionService }}</td>
                            <td>
                                {!! Form::open(['url' => 'modificationvalideur']) !!}
                                <select name="nom_prenom">
                                    <option value="Aucun">Aucun</option>
                                    @foreach ($_SESSION['personnels'] as $lignes => $personnel)
                                        @if ($personnel->nomService == $service->nomService)
                                            @if ($personnel->nomCategorie == 'Valideur')
                                                <option value="{{ $personnel->nom }} {{ $personnel->prenom }}" selected >{{ $personnel->nom }} {{ $personnel->prenom }}</option>
                                            @else
                                                <option value="{{ $personnel->nom }} {{ $personnel->prenom }}" >{{ $personnel->nom }} {{ $personnel->prenom }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                @foreach ($_SESSION['personnels'] as $lignes => $personnel)
                                    @if ($personnel->nomService == $service->nomService AND $personnel->nomCategorie == 'Valideur')
                                        {{ $personnel->mail }}
                                        {{ Form::hidden('mail_ancien_valideur', $personnel->mail) }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                {{ Form::submit('Modifier le valideur') }}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif
            <table id="service_util">
                <caption>Départements d’attachement</caption>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Valideur rattaché</th>
                    <th>Contact du valideur</th>
                </tr>
                <tr>
                    <td>{{ $_SESSION['service_util'][0]->nomService }}</td>
                    <td>{{ $_SESSION['service_util'][0]->descriptionService }}</td>
                    <td>
                        @if (isset($_SESSION['service_util'][0]->nom))
                            @php ($nom_prenom = $_SESSION['service_util'][0]->nom.' '.$_SESSION['service_util'][0]->prenom)
                        @endif
                        {{ $nom_prenom ?? 'Aucun' }}
                    </td>
                    <td>
                        @php ($mail = $_SESSION['service_util'][0]->mail ?? 'N/A')
                        {{ $mail }}
                    </td>
                </tr>
            </table>
        </section>
@include('footer')
