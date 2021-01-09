@if (!isset($_SESSION['mail']))
    @php header('Refresh: 0; url='.url('/'));
    exit; @endphp
@endif
@php $css = 'accueil'; $title_h1 = "Accueil"; @endphp
@include('head')
@include('menus')
@include('header')
        <section id="corps">
            <!-- Redirection juste après la connexion -->
            @php $refresh = $connexion ?? false; @endphp
            @if ($refresh)
                @php header('Refresh: 0; url=accueil');
                exit; @endphp
            @endif

            @if ($_SESSION['categorie'] != 'Administrateur')
                @php ($droitinsuffisant = $droitinsuf ?? false)
                @if ($droitinsuffisant)
                    <p class="erreur"><img class="img_erreur" src="{{ asset('storage/app/public/warning.png') }}" alt="Icon de confirmation" /> Vous n'avez pas les droits pour accéder à cette page !</p><br />
                    @php (header('Refresh: 5; url=accueil'))
                @endif

                <!-- Affichage d'un message -->
                @if ($_SESSION['message'] != '')
                    <section id="message">{{ $_SESSION["message"] }}</section>
                @endif

                <table id="liste_fourniture">
                    <caption>Liste de 6 fournitures :</caption>
                    <tr>
                        <th>Photo</th>
                        <th class="tabl_fourn">Nom</th>
                        <th class="tabl_fourn">Description</th>
                        <th class="tabl_fourn">Quantitée disponible</th>
                        <th class="tabl_fourn">Quantitée demandée</th>
                    </tr>
                @for ($i=0; $i < 6; $i++)
                    <tr>
                        <td><img class="photo_fournitures" src="{{ asset('storage/app/public/'.$_SESSION['fournitures'][$i]->nomPhoto.'.jpg') }}" /></td>
                        <td>{{ $_SESSION['fournitures'][$i]->nomFournitures }}</td>
                        <td>{{ $_SESSION['fournitures'][$i]->descriptionFournitures }}</td>
                        <td>{{ $_SESSION['fournitures'][$i]->quantiteDisponible }}</td>
                        <td>
                            {!! Form::open(['url' => 'commander']) !!}
                            {{ Form::hidden('id', $_SESSION['fournitures'][$i]->id) }}
                            {{ Form::hidden('nom_fourniture', $_SESSION['fournitures'][$i]->nomFournitures) }}
                            {{ Form::number('quantite_demande', '1', ['min'=>'1', 'max'=>$_SESSION['fournitures'][$i]->quantiteDisponible]) }}
                            {{ Form::submit('Commander', ['class'=>'submit']) }}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endfor
                </table>
            @else
                @php ($fichiertropgros = $tropgros ?? false)
                @if ($fichiertropgros)
                    <p class="erreur"><img class="img_erreur" src="{{ asset('storage/app/public/warning.png') }}" alt="Icon de confirmation" /> Le poids du logo est trop volumineux ! (Max : 500Mo)</p><br />
                    @php (header('Refresh: 5; url=accueil'))
                @endif

                @php ($formatinvalide = $invalide ?? false)
                @if ($formatinvalide)
                    <p class="erreur"><img class="img_erreur" src="{{ asset('storage/app/public/warning.png') }}" alt="Icon de confirmation" /> Le format du logo n'est pas valide !</p><br />
                    @php (header('Refresh: 5; url=accueil'))
                @endif

                @php ($modifie = $modif ?? false)
                @if ($modifie)
                    <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> Le logo a bien été modifier</p><br />
                    @php (header('Refresh: 5; url=accueil'))
                @endif

                @php ($confirmSupprlogo = $supprlogo ?? false)
                @if ($confirmSupprlogo)
                    <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> Le logo a bien été supprimé</p><br />
                    @php (header('Refresh: 5; url=accueil'))
                @endif

                @php ($confirm = $vrai ?? false)
                @if ($confirm)
                    <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> Votre message a bien été envoyé</p><br />
                    @php (header('Refresh: 5; url=accueil'))
                @endif

                @php ($confirmSuppr = $suppr ?? false)
                @if ($confirmSuppr)
                    <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> Le message a bien été supprimé</p><br />
                    @php (header('Refresh: 5; url=accueil'))
                @endif

                @php ($confirmSupprTous = $supprtous ?? false)
                @if ($confirmSupprTous)
                    <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> Les messages ont bien été supprimé</p><br />
                    @php (header('Refresh: 5; url=accueil'))
                @endif

                @if ($_SESSION['message'] != '')
                    <section id="message">{{ $_SESSION["message"] }}</section>
                @endif

                <a href="accueil" id="haut_page"><img id="img_haut_page" src="{{ asset('storage/app/public/haut-page.png') }}" alt="Flèche" /></a>

                <h4>Modification du logo :</h4>
                {!! Form::open(['url' => 'modificationlogo', 'files' => true, 'id'=>'modificationlogo']) !!}
                {{ Form::file('photo_logo', ['required']) }}
                {{ Form::submit('Modifier le logo') }}
                {!! Form::close() !!}

                <button type="button" id="suppressionlogo" onclick="window.location.href='suppressionlogo'">Supprimer le logo</button>

                <h4>Envoyer un message à un utilisateur :</h4>
                {!! Form::open(['url' => 'message']) !!}
                {{ Form::textarea('message', $value = null, ['maxlength' => '1500', 'required']) }}
                {{ Form::label('mail', 'Utilisateur :', ['id'=>'label_select']) }}
                <select name="mail">
                    <option value="tous">Tous</option>
                    @foreach ($_SESSION['personnels'] as $lignes => $mailpersonnel)
                        <option value="{{ $mailpersonnel->mail }}">{{ $mailpersonnel->mail }}</option>
                    @endforeach
                </select>
                {{ Form::submit('Envoyer') }}
                {!! Form::close() !!}

                <!-- Balise vide pour positioner le lien du menu latéral-->
                <span id="navlistecomptes"></span>
                <table id='liste_comptes'>
                    <caption>Liste des comptes</caption>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Mail</th>
                        <th>Service</th>
                        <th>Catégorie</th>
                        <th id="col_message">Message</th>
                    </tr>
                @foreach ($_SESSION['personnels'] as $lignes => $personnel)
                    <tr>
                        <td>{{ $personnel->nom }}</td>
                        <td>{{ $personnel->prenom }}</td>
                        <td>{{ $personnel->mail }}</td>
                        <td>{{ $personnel->nomService }}</td>
                        <td>{{ $personnel->nomCategorie }}</td>
                        <td>
                            {{ $personnel->message }}
                            @if ($personnel->message != '')
                                {!! Form::open(['url' => 'supprimer']) !!}
                                {{ Form::hidden('mail', $personnel->mail) }}
                                {{ Form::submit('Supprimer le message', ['id'=>'supprimer_message']) }}
                                {!! Form::close() !!}
                            @endif
                        </td>
                    </tr>
                @endforeach
                </table>
                <button type="button" id="supprimer_tous" onclick="window.location.href='suppressionmessages'">Supprimer tous les messages</button>

                <table id="liste_commandes">
                    <caption>Aperçu des commandes en cours</caption>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Nom de la commande</th>
                        <th>Quantitée demandée</th>
                        <th>État</th>
                        <th>Création</th>
                        <th>Dernière mise à jour</th>
                    </tr>
                @if ($_SESSION['commandes_liste']->count() < 6)
                    @php ($max = $_SESSION['commandes_liste']->count())
                @else
                    @php ($max = 6)
                @endif
                @for ($l=0; $l < $max; $l++)
                    <tr>
                        <td>{{ $_SESSION['commandes_liste'][$l]->nom }}</td>
                        <td>{{ $_SESSION['commandes_liste'][$l]->prenom }}</td>
                        <td>{{ $_SESSION['commandes_liste'][$l]->nomCommandes }}</td>
                        <td>{{ $_SESSION['commandes_liste'][$l]->quantiteDemande }}</td>
                        <td>{{ $_SESSION['commandes_liste'][$l]->nomEtat }}</td>
                        <td>{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['commandes_liste'][$l]->created_at)) }}</td>
                        <td>{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['commandes_liste'][$l]->updated_at)) }}</td>
                    </tr>
                @endfor
                </table>
            @endif
        </section>
@include('footer')
