<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" />
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" sizes="144x144" href="http://localhost/PPE3/Application/storage/app/public/CCI.png" />
        <link rel="stylesheet" href="http://localhost/PPE3/Application/resources/css/departements.css" />
        <title>Départements</title>
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
        <nav id="menu_lateral"  onmouseover="afficherMenu(menu_lateral)" onmouseout="cacherMenu(menu_lateral)">
            <ul id="ul_menu_lateral">
                <li class="li_menu_lateral"><a class="menu_lateral" href="accueil#navlistecomptes">Liste des comptes</a></li>
                <li class="li_menu_lateral"><a class="menu_lateral" href="accueil#supprimer_tous">Supprimer tous les messages</a></li>
                <li class="li_menu_lateral"><a class="menu_lateral" href="accueil#liste_commandes">Liste des commandes en cours</a></li>
            </ul>
        </nav>
        <header>
            <h1>Départements</h1>
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
                <?php for ($h=0; $h < $_SESSION['commandes']->count(); $h++) { ?>
                    <tr>
                        <td class="tabl_comm">{{ $_SESSION['commandes'][$h]->nomCommandes }}</td>
                        <td class="tabl_comm">{{ $_SESSION['commandes'][$h]->quantiteDemande }}</td>
                        <td class="tabl_comm">{{ $_SESSION['commandes'][$h]->nomEtat }}</td>
                        <td class="tabl_comm">{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['commandes'][$h]->updated_at)) }}</td>
                    </tr>
                <?php } ?>
                </table>
            <?php } ?>
        </header>
        <section id="corps">
            <?php if ($_SESSION['categorie'] == 'Administrateur') {
                $creation = $cree ?? false;
                if ($creation) { ?>
                    <p class="confirm"><img class="img_confirm" src="http://localhost/PPE3/Application/storage/app/public/confirm.png" alt="Icon de confirmation" /> Le département a bien été créé</p><br />
                    <?php header('Refresh: 5; url=departements');
                }

                $valide = $valider ?? false;
                if ($valide) { ?>
                    <p class="confirm"><img class="img_confirm" src="http://localhost/PPE3/Application/storage/app/public/confirm.png" alt="Icon de confirmation" /> La mise à jour à bien été prise en compte</p><br />
                    <?php header('Refresh: 5; url=departements');
                } ?>

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
                                <?php /*for ($i=0; $i < $_SESSION['personnels']->count(); $i++) {
                                    echo '<option value="'.$_SESSION['personnels'][$i]->mail.'">'.$_SESSION['personnels'][$i]->mail.'</option>';
                                }*/ ?>
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
                    <?php for ($j=0; $j < $_SESSION['services']->count(); $j++) { ?>
                        <tr>
                            <td>{{ $_SESSION['services'][$j]->nomService }}</td>
                            <td>{{ $_SESSION['services'][$j]->descriptionService }}</td>
                            <td>
                                {!! Form::open(['url' => 'modificationvalideur']) !!}
                                <select name="nom_prenom">
                                    <option value="Aucun">Aucun</option>
                                    <?php for ($k=0; $k < $_SESSION['personnels']->count(); $k++) {
                                        if ($_SESSION['personnels'][$k]->nomService == $_SESSION['services'][$j]->nomService) {
                                            if ($_SESSION['personnels'][$k]->nomCategorie == 'Valideur') {
                                                echo '<option value="'.$_SESSION['personnels'][$k]->nom.' '.$_SESSION['personnels'][$k]->prenom.'" selected>'.$_SESSION['personnels'][$k]->nom.' '.$_SESSION['personnels'][$k]->prenom.'</option>';
                                            } else {
                                                echo '<option value="'.$_SESSION['personnels'][$k]->nom.' '.$_SESSION['personnels'][$k]->prenom.'">'.$_SESSION['personnels'][$k]->nom.' '.$_SESSION['personnels'][$k]->prenom.'</option>';
                                            }
                                        }
                                    } ?>
                                </select>
                            </td>
                            <td>
                                <?php
                                for ($l=0; $l < $_SESSION['personnels']->count(); $l++) {
                                    if ($_SESSION['personnels'][$l]->nomService == $_SESSION['services'][$j]->nomService AND $_SESSION['personnels'][$l]->nomCategorie == 'Valideur') {
                                        echo $_SESSION['personnels'][$l]->mail;
                                        echo '<input type="hidden" name="mail_ancien_valideur" value="'.$_SESSION['personnels'][$l]->mail.'">';
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                {{ Form::submit('Modifier le valideur') }}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
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
                    <td><?php
                        if (isset($_SESSION['service_util'][0]->nom)) {
                            $nom_prenom = $_SESSION['service_util'][0]->nom.' '.$_SESSION['service_util'][0]->prenom;
                        }
                        echo $nom_prenom ?? 'Aucun';
                        ?>
                    </td>
                    <td>
                    <?php
                        $mail = $_SESSION['service_util'][0]->mail ?? 'N/A'; echo $mail;
                    ?>
                    </td>
                </tr>
            </table>
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
