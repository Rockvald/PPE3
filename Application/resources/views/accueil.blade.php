<?php
    if (!isset($_SESSION['mail'])) {
        header('Refresh: 0; url=http://localhost/PPE3/Application/server.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" />
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" sizes="144x144" href="http://localhost/PPE3/Application/storage/app/public/CCI.png" />
        <link rel="stylesheet" href="http://localhost/PPE3/Application/resources/css/accueil.css" />
        <title>Accueil</title>
        <script type="text/javascript">
            function afficherMenu(menu) {
                menu.style.visibility = "visible";
            }

            function cacherMenu(menu) {
                menu.style.visibility = "hidden";
            }
        </script>
    </head>
    <body>
        <nav>
            <ul>
                <li id="li_logo"><img id="logo" src="http://localhost/PPE3/Application/storage/app/public/logo-cci.png" alt="Logo de la CCI" /></li>
                <?php if ($_SESSION['categorie'] != 'Administrateur') { ?>
                    <li><a class="menu" href="accueil">ACCUEIL</a></li>
                <?php } else { ?>
                    <li><a class="menu" href="accueil" onmouseover="afficherMenu(menu_lateral)" onmouseout="cacherMenu(menu_lateral)">ACCUEIL</a></li>
                <?php } ?>
                <li><a class="menu" href="departements">DÉPARTEMENTS</a></li>
                <li><a class="menu" href="fournitures">FOURNITURES</a></li>
                <?php if ($_SESSION['categorie'] == 'Administrateur') { ?>
                    <li><a class="menu" href="famillesfournitures">FAMILLES FOURNITURES</a></li>
                    <li><a class="menu" href="messagerie">MÉSSAGERIE</a></li>
                    <li><a class="menu" href="statistique">STATISTIQUE</a></li>
                <?php } ?>
                <li><a class="menu" href="demandesspecifiques">DEMANDE SPÉCIFIQUE</a></li>
                <li><a class="menu" href="suivi">SUIVI</a></li>
                <?php if ($_SESSION['categorie'] != 'Administrateur') { ?>
                    <li><a class="menu" id="personnalisation" href="personnalisationducompte">PERSONNALISATION DU COMPTE</a></li>
                <?php } ?>
            </ul>
        </nav>
        <?php //if ($_SESSION['categorie'] == 'Administrateur') { ?>
        <nav id="menu_lateral"  onmouseover="afficherMenu(menu_lateral)" onmouseout="cacherMenu(menu_lateral)">
            <ul id="ul_menu_lateral">
                <li class="li_menu_lateral"><a class="menu_lateral" href="accueil#navlistecomptes">Liste des comptes</a></li>
                <li class="li_menu_lateral"><a class="menu_lateral" href="accueil#supprimer_tous">Supprimer tous les messages</a></li>
                <li class="li_menu_lateral"><a class="menu_lateral" href="accueil#liste_commandes">Liste des commandes en cours</a></li>
            </ul>
        </nav>
        <?php //} ?>
        <header>
            <h1>Accueil</h1>
            {!! Form::open(['url' => 'rechercher']) !!}
            {{ Form::search('recherche', $value = null, ['id'=>'recherche', 'placeholder'=>'Recherche', 'required']) }}
            {{ Form::image('http://localhost/PPE3/Application/storage/app/public/icon-search.png', 'envoyer', ['id'=>'envoyer', 'alt'=>'Icone de loupe']) }}
            {!! Form::close() !!}
            <div id="nom_deconnexion">
                <p id="nom_prenom">{{ $_SESSION['prenom'] }} {{ $_SESSION['nom'] }}</p>
                <button type="button" name="deconnexion" id="deconnexion" onclick="window.location.href='deconnexion'">Se déconnecter</button>
            </div>
            <?php if (isset($_SESSION['commandes'][0])) { ?>
                <table id="commandes_cours">
                    <caption>Commandes en cours</caption>
                    <tr>
                        <th class="tabl_comm">Nom</th>
                        <th class="tabl_comm">Quantitée demandée</th>
                        <th class="tabl_comm">État</th>
                        <th class="tabl_comm">Dernière mise à jour</th>
                    </tr>
                <?php foreach ($_SESSION['commandes'] as $lignes => $colonnes) { ?>
                    <tr>
                        <td class="tabl_comm">{{ $colonnes->nomCommandes }}</td>
                        <td class="tabl_comm">{{ $colonnes->quantiteDemande }}</td>
                        <td class="tabl_comm">{{ $colonnes->nomEtat }}</td>
                        <td class="tabl_comm">{{ date('G:i:s \l\e d-m-Y', strtotime($colonnes->updated_at)) }}</td>
                    </tr>
                <?php } ?>
                </table>
            <?php } ?>
        </header>
        <section id="corps">
            <?php
                $refresh = $connexion ?? false;
                if ($refresh) {
                    header('Refresh: 0; url=accueil');
                    exit;
                }

                if ($_SESSION['categorie'] != 'Administrateur') {
                    $droitinsuffisant = $droitinsuf ?? false;
                    if ($droitinsuffisant) { ?>
                        <p class="erreur"><img class="img_erreur" src="http://localhost/PPE3/Application/storage/app/public/warning.png" alt="Icon de confirmation" /> Vous n'avez pas les droits pour accéder à cette page !</p><br />
                        <?php header('Refresh: 5; url=accueil');
                    }

                    if ($_SESSION['message'] != '') { ?>
                        <section id="message">{{ $_SESSION["message"] }}</section>
                    <?php } ?>

                    <table id="liste_fourniture">
                        <caption>Liste de 6 fournitures :</caption>
                        <tr>
                            <th>Photo</th>
                            <th class="tabl_fourn">Nom</th>
                            <th class="tabl_fourn">Description</th>
                            <th class="tabl_fourn">Quantitée disponible</th>
                            <th class="tabl_fourn">Quantitée demandée</th>
                        </tr>
                    <?php for ($i=0; $i < 6; $i++) { ?>
                        <tr>
                            <td><img class="photo_fournitures" src="http://localhost/PPE3/Application/storage/app/public/{{ $_SESSION['fournitures'][$i]->nomPhoto }}.jpg" /></td>
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
                    <?php } ?>
                    </table>

            <?php } else {
                $fichiertropgros = $tropgros ?? false;
                if ($fichiertropgros) { ?>
                    <p class="erreur"><img class="img_erreur" src="http://localhost/PPE3/Application/storage/app/public/warning.png" alt="Icon de confirmation" /> Le poids du logo est trop volumineux ! (Max : 500Mo)</p><br />
                    <?php header('Refresh: 5; url=accueil');
                }

                $formatinvalide = $invalide ?? false;
                if ($formatinvalide) { ?>
                    <p class="erreur"><img class="img_erreur" src="http://localhost/PPE3/Application/storage/app/public/warning.png" alt="Icon de confirmation" /> Le format du logo n'est pas valide !</p><br />
                    <?php header('Refresh: 5; url=accueil');
                }

                $modifie = $modif ?? false;
                if ($modifie) { ?>
                    <p class="confirm"><img class="img_confirm" src="http://localhost/PPE3/Application/storage/app/public/confirm.png" alt="Icon de confirmation" /> Le logo a bien été modifier</p><br />
                    <?php header('Refresh: 5; url=accueil');
                }

                $confirmSupprlogo = $supprlogo ?? false;
                if ($confirmSupprlogo) { ?>
                    <p class="confirm"><img class="img_confirm" src="http://localhost/PPE3/Application/storage/app/public/confirm.png" alt="Icon de confirmation" /> Le logo a bien été supprimé</p><br />
                <?php header('Refresh: 5; url=accueil');
                }

                $confirm = $vrai ?? false;
                if ($confirm) { ?>
                    <p class="confirm"><img class="img_confirm" src="http://localhost/PPE3/Application/storage/app/public/confirm.png" alt="Icon de confirmation" /> Votre message a bien été envoyé</p><br />
                <?php header('Refresh: 5; url=accueil');
                }

                $confirmSuppr = $suppr ?? false;
                if ($confirmSuppr) { ?>
                    <p class="confirm"><img class="img_confirm" src="http://localhost/PPE3/Application/storage/app/public/confirm.png" alt="Icon de confirmation" /> Le message a bien été supprimé</p><br />
                <?php header('Refresh: 5; url=accueil');
                }

                $confirmSupprTous = $supprtous ?? false;
                if ($confirmSupprTous) { ?>
                    <p class="confirm"><img class="img_confirm" src="http://localhost/PPE3/Application/storage/app/public/confirm.png" alt="Icon de confirmation" /> Les messages ont bien été supprimé</p><br />
                <?php header('Refresh: 5; url=accueil');
                }

                if ($_SESSION['message'] != '') { ?>
                    <section id="message">{{ $_SESSION["message"] }}</section>
                <?php } ?>

                <a href="accueil" id="haut_page"><img id="img_haut_page" src="http://localhost/PPE3/Application/storage/app/public/haut-page.png" alt="Flèche" /></a>

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
                    <?php for ($j=0; $j < $_SESSION['personnels']->count(); $j++) {
                        echo '<option value="'.$_SESSION['personnels'][$j]->mail.'">'.$_SESSION['personnels'][$j]->mail.'</option>';
                    } ?>
                </select>
                {{ Form::submit('Envoyer') }}
                {!! Form::close() !!}

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
                <?php for ($k=0; $k < $_SESSION['personnels']->count(); $k++) { ?>
                    <tr>
                        <td>{{ $_SESSION['personnels'][$k]->nom }}</td>
                        <td>{{ $_SESSION['personnels'][$k]->prenom }}</td>
                        <td>{{ $_SESSION['personnels'][$k]->mail }}</td>
                        <td>{{ $_SESSION['personnels'][$k]->nomService }}</td>
                        <td>{{ $_SESSION['personnels'][$k]->nomCategorie }}</td>
                        <td>
                            {{ $_SESSION['personnels'][$k]->message }}
                            <?php if ($_SESSION['personnels'][$k]->message != '') { ?>
                                {!! Form::open(['url' => 'supprimer']) !!}
                                {{ Form::hidden('mail', $_SESSION['personnels'][$k]->mail) }}
                                {{ Form::submit('Supprimer le message', ['id'=>'supprimer_message']) }}
                                {!! Form::close() !!}
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
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
                <?php if ($_SESSION['commandes_liste']->count() < 6) {
                    $max = $_SESSION['commandes_liste']->count();
                } else {
                    $max = 6;
                }
                for ($l=0; $l < $max; $l++) { ?>
                    <tr>
                        <td>{{ $_SESSION['commandes_liste'][$l]->nom }}</td>
                        <td>{{ $_SESSION['commandes_liste'][$l]->prenom }}</td>
                        <td>{{ $_SESSION['commandes_liste'][$l]->nomCommandes }}</td>
                        <td>{{ $_SESSION['commandes_liste'][$l]->quantiteDemande }}</td>
                        <td>{{ $_SESSION['commandes_liste'][$l]->nomEtat }}</td>
                        <td>{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['commandes_liste'][$l]->created_at)) }}</td>
                        <td>{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['commandes_liste'][$l]->updated_at)) }}</td>
                    </tr>
                <?php } ?>
                </table>
            <?php } ?>
        </section>
        <footer>
            <?php if (isset($_SESSION['commandes_fini'][0])) { ?>
                <table id="commandes_fini">
                    <caption>Historique des commandes</caption>
                    <tr>
                        <th class="tabl_comm">Nom</th>
                        <th class="tabl_comm">Quantitée demandée</th>
                        <th class="tabl_comm">État</th>
                        <th class="tabl_comm">Dernière mise à jour</th>
                    </tr>
                <?php for ($m=0; $m < $_SESSION['commandes_fini']->count(); $m++) { ?>
                    <tr>
                        <td class="tabl_comm">{{ $_SESSION['commandes_fini'][$m]->nomCommandes }}</td>
                        <td class="tabl_comm">{{ $_SESSION['commandes_fini'][$m]->quantiteDemande }}</td>
                        <td class="tabl_comm">{{ $_SESSION['commandes_fini'][$m]->nomEtat }}</td>
                        <td class="tabl_comm">{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['commandes_fini'][$m]->updated_at)) }}</td>
                    </tr>
                <?php } ?>
                </table>
            <?php } ?>
            <p id="service">Vous êtes dans le service : {{ $_SESSION['service'] }}</p>
            <p id="categorie">Votre rôle est : {{ $_SESSION['categorie'] }}</p>
        </footer>
    </body>
</html>
