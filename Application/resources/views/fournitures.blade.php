@php $css = 'fournitures'; $title_h1 = 'Fournitures'; @endphp
@include('head')
@include('menus')
@include('header')
        <section id="corps">
            @if (isset($reponse))
                <a href="fournitures" id="retour">Retour à la liste des fournitures</a><br /><br />
                @if ($reponse)
                    <table id="resultat_recherche">
                        <caption>Résultat de la recherche :</caption>
                        <tr>
                            <th>Photo</th>
                            <th class="tabl_fourn">Nom</th>
                            <th class="tabl_fourn">Description</th>
                            <th class="tabl_fourn">Famille</th>
                            <th class="tabl_fourn">Quantitée disponible</th>
                        @if ($donneesPersonnel['Personnel'][0]->nomCategorie != 'Administrateur')
                            <th class="tabl_fourn">Quantitée demandée</th>
                        @endif
                        </tr>
                    @foreach ($resultats as $lignes => $resultat)
                        <tr>
                            <td><img class="photo_fournitures" src="{{ asset('storage/app/public/'.$resultat->nomPhoto.'.jpg') }}" /></td>
                            <td>{{ $resultat->nomFournitures }}</td>
                            <td>{{ $resultat->descriptionFournitures }}</td>
                            <td>
                                @if ($donneesPersonnel['Personnel'][0]->nomCategorie == 'Administrateur')
                                    {!! Form::open(['url' => 'modificationfamille']) !!}
                                    {{ Form::hidden('id', $resultat->id) }}
                                    <select name="nom_famille">
                                        @foreach ($donneesFourniture['famillesfournitures'] as $lignes => $famille)
                                            @if ($famille->nomFamille == $resultat->nomFamille)
                                                <option value="{{ $famille->nomFamille }}" selected >{{ $famille->nomFamille }}</option>
                                            @else
                                                <option value="{{ $famille->nomFamille }}" >{{ $famille->nomFamille }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    {{ Form::submit('Mettre à jour', ['class'=>'submit']) }}
                                    {!! Form::close() !!}
                                @else
                                    {{ $resultat->nomFamille }}
                                @endif
                            </td>
                            <td>
                            @if ($donneesPersonnel['Personnel'][0]->nomCategorie == 'Administrateur')
                                {!! Form::open(['url' => 'majquantite']) !!}
                                {{ Form::hidden('id', $resultat->id) }}
                                {{ Form::number('quantite_disponible', $resultat->quantiteDisponible, ['min'=>'0', 'max'=>'100']) }}
                                {{ Form::submit('Mettre à jour', ['class'=>'submit']) }}
                                {!! Form::close() !!}
                            @else
                                {{ $resultat->quantiteDisponible }}
                            @endif
                            </td>
                        @if ($donneesPersonnel['Personnel'][0]->nomCategorie != 'Administrateur')
                            <td>
                                {!! Form::open(['url' => 'commander']) !!}
                                {{ Form::hidden('id', $resultat->id) }}
                                {{ Form::hidden('nom_fourniture', $resultat->nomFournitures) }}
                                {{ Form::number('quantite_demande', '1', ['min'=>'1', 'max'=>$resultat->quantiteDisponible]) }}
                                {{ Form::submit('Commander', ['class'=>'submit']) }}
                                {!! Form::close() !!}
                            </td>
                        @endif
                        </tr>
                    @endforeach
                    </table>
                @else
                    <p>Aucun résultat trouvé pour votre recherche.</p>
                @endif
            @else
                @php ($valide = $valider ?? false)
                @if ($valide)
                    <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> La mise à jour à bien été prise en compte</p><br />
                    @php (header('Refresh: 5; url=fournitures'))
                @endif

                @php ($fichiertropgros = $tropgros ?? false)
                @if ($fichiertropgros)
                    <p class="erreur"><img class="img_erreur" src="{{ asset('storage/app/public/warning.png') }}" alt="Icon de confirmation" /> Le poids de l'image est trop volumineux ! (Max : 500ko)</p><br />
                @endif

                @php ($formatinvalide = $invalide ?? false)
                @if ($formatinvalide)
                    <p class="erreur"><img class="img_erreur" src="{{ asset('storage/app/public/warning.png') }}" alt="Icon de confirmation" /> Le format de l'image n'est pas valide !</p><br />
                @endif

                @php ($creation = $cree ?? false)
                @if ($creation)
                    <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> L'article a bien été créé</p><br />
                    @php (header('Refresh: 5; url=fournitures'))
                @endif

                @php ($creation_commande = $commande_cree ?? false)
                @if ($creation_commande)
                    <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> La commande a bien été prise en compte</p><br />
                    @php (header('Refresh: 5; url=fournitures'))
                @endif

                @if ($donneesPersonnel['Personnel'][0]->nomCategorie == 'Administrateur')
                    <table id="ajout_fourniture">
                        <caption>Ajouter une fourniture</caption>
                        <tr>
                            <th>Photo</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Famille</th>
                            <th>Quantitée disponible</th>
                        </tr>
                        <tr>
                            <td>
                                {!! Form::open(['url' => 'creationfourniture', 'files' => true]) !!}
                                {{ Form::file('photo_fournitures', ['required']) }}
                            </td>
                            <td>{{ Form::text('nom_fourniture', $value = $requete->nom_fourniture ?? null, ['maxlength'=>'50', 'placeholder'=>'Ex: Ciseau Maped', 'required']) }}</td>
                            <td>{{ Form::text('description_fourniture', $value = $requete->description_fourniture ?? null, ['maxlength'=>'50', 'required']) }}</td>
                            <td>
                                <select name="nom_famille">
                                    @foreach ($donneesFourniture['famillesfournitures'] as $lignes => $famille)
                                        <option value="{{ $famille->nomFamille }}" >{{ $famille->nomFamille }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                {{ Form::number('quantite_disponible', $requete->quantite_disponible ?? '1', ['min'=>'1', 'max'=>'100']) }}
                                {{ Form::submit('Créer l\'article') }}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    </table>
                @endif

                <div id="choix_familles">
                    <p id="familles">Familles :</p>
                    <select id="select_familles" onchange="selectionFamilles('fournitures')">
                        <option value="Toutes">Toutes</option>
                    @foreach ($donneesFourniture['famillesfournitures'] as $lignes => $famille)
                        @if (isset($_GET['famille']) AND $_GET['famille'] == $famille->nomFamille)
                            <option value="{{ $famille->nomFamille }}" selected >{{ $famille->nomFamille }}</option>
                        @else
                            <option value="{{ $famille->nomFamille }}" >{{ $famille->nomFamille }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>

                @foreach ($donneesFourniture['famillesfournitures'] as $lignes => $famille)
                    @if (isset($_GET['famille']) AND $_GET['famille'] == $famille->nomFamille)
                        @php ($nom = $_GET['famille'])
                    @endif
                @endforeach
                @php ($fourniture = $nom ?? '')

                @if ($fourniture == '')
                    @php ($liste_fournitures = $donneesFourniture['fournitures'])
                @else
                    @php ($liste_fournitures = $donneesFourniture['donnesFamille']["$fourniture"])
                @endif

                @if (isset($liste_fournitures[0]))
                    <table id="liste_fourniture">
                        <caption>Liste des fournitures :</caption>
                        <tr>
                            <th>Photo</th>
                            <th class="tabl_fourn">Nom</th>
                            <th class="tabl_fourn">Description</th>
                            <th class="tabl_fourn">Famille</th>
                            <th class="tabl_fourn">Quantitée disponible</th>
                        @if ($donneesPersonnel['Personnel'][0]->nomCategorie != 'Administrateur')
                            <th class="tabl_fourn">Quantitée demandée</th>
                        @endif
                        </tr>
                    @foreach ($liste_fournitures as $lignes => $fourniture)
                        <tr>
                            <td><img class="photo_fournitures" src='{{ asset('storage/app/public/'.$fourniture->nomPhoto.'.jpg') }}' /></td>
                            <td>{{ $fourniture->nomFournitures }}</td>
                            <td>{{ $fourniture->descriptionFournitures }}</td>
                            <td>
                                @if ($donneesPersonnel['Personnel'][0]->nomCategorie == 'Administrateur')
                                    {!! Form::open(['url' => 'modificationfamille']) !!}
                                    {{ Form::hidden('id', $fourniture->id) }}
                                    <select name="nom_famille">
                                        @foreach ($donneesFourniture['famillesfournitures'] as $lignes => $famille)
                                            @if ($famille->nomFamille == $fourniture->nomFamille)
                                                <option value="{{ $famille->nomFamille }}" selected >{{ $famille->nomFamille }}</option>
                                            @else
                                                <option value="{{ $famille->nomFamille }}" >{{ $famille->nomFamille }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    {{ Form::submit('Mettre à jour', ['class'=>'submit']) }}
                                    {!! Form::close() !!}
                                @else
                                    {{ $fourniture->nomFamille }}
                                @endif
                            </td>
                            <td>
                            @if ($donneesPersonnel['Personnel'][0]->nomCategorie == 'Administrateur')
                                {!! Form::open(['url' => 'majquantite']) !!}
                                {{ Form::hidden('id', $fourniture->id) }}
                                {{ Form::number('quantite_disponible', $fourniture->quantiteDisponible, ['min'=>'0', 'max'=>'100']) }}
                                {{ Form::submit('Mettre à jour', ['class'=>'submit']) }}
                                {!! Form::close() !!}
                            @else
                                {{ $fourniture->quantiteDisponible }}
                            @endif
                            </td>
                        @if ($donneesPersonnel['Personnel'][0]->nomCategorie != 'Administrateur')
                            <td>
                                {!! Form::open(['url' => 'commander']) !!}
                                {{ Form::hidden('id', $fourniture->id) }}
                                {{ Form::hidden('nom_fourniture', $fourniture->nomFournitures) }}
                                {{ Form::number('quantite_demande', '1', ['min'=>'1', 'max'=>$fourniture->quantiteDisponible]) }}
                                {{ Form::submit('Commander', ['class'=>'submit']) }}
                                {!! Form::close() !!}
                            </td>
                        @endif
                        </tr>
                    @endforeach
                    </table>
                @else
                    <section id="liste_fourniture_famille">
                        <h4>Liste des fournitures :</h4><br />
                        <p>Il n'y a pas de fournitures de cette famille.</p>
                    </section>
                @endif
            @endif
        </section>
@include('footer')
