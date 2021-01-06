<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" />
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" sizes="144x144" href="http://localhost/PPE-3/Application/storage/app/public/CCI.png" />
        <link rel="stylesheet" href="http://localhost/PPE-3/Application/resources/css/suivi.css" />
        <title>Suivi</title>
        <script type="text/javascript">
            function imprimer(nomSection) {
                var contenuAImprimer = document.getElementById(nomSection).innerHTML;
                var contenuOriginel = document.body.innerHTML;
                document.body.innerHTML = contenuAImprimer;
                window.print();
                document.body.innerHTML = contenuOriginel;
            }

            function selectionServices() {
                var service = document.getElementById("select_services").value;
                if (service == 'Tous') {
                    window.location.href = 'suivi';
                } else {
                    window.location.href = 'suivi?service=' + service ;
                }
            }

            function selectionEtat() {
                var etat = document.getElementById("select_etat").value;
                if (etat == 'Tous') {
                    window.location.href = 'suivi';
                } else {
                    window.location.href = 'suivi?etat=' + etat ;
                }
            }

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
                <li id="li_logo"><img id="logo" src="http://localhost/PPE-3/Application/storage/app/public/logo-cci.png" alt="Logo de la CCI" /></li>
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
            <h1>Suivi</h1>
            {!! Form::open(['url' => 'rechercher']) !!}
            {{ Form::search('recherche', $value = null, ['id'=>'recherche', 'placeholder'=>'Recherche', 'required']) }}
            {{ Form::image('http://localhost/PPE-3/Application/storage/app/public/icon-search.png', 'envoyer', ['id'=>'envoyer', 'alt'=>'Icone de loupe']) }}
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
                <?php for ($e=0; $e < $_SESSION['commandes']->count(); $e++) { ?>
                    <tr>
                        <td class="tabl_comm">{{ $_SESSION['commandes'][$e]->nomCommandes }}</td>
                        <td class="tabl_comm">{{ $_SESSION['commandes'][$e]->quantiteDemande }}</td>
                        <td class="tabl_comm">{{ $_SESSION['commandes'][$e]->nomEtat }}</td>
                        <td class="tabl_comm">{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['commandes'][$e]->updated_at)) }}</td>
                    </tr>
                <?php } ?>
                </table>
            <?php } ?>
        </header>
        <div id="bouton_imprimer"><button onclick="imprimer('corps');">Imprimer</button></div>
        <section id="corps">
            <?php if ($_SESSION['categorie'] == 'Administrateur') {
                if (isset($_SESSION['commande_complet'][0])) { ?>
                    <div id="choix_departements">
                        <p id="departements">Départements :</p>
                        <select id="select_services" onchange="selectionServices()">
                            <option value="Tous">Tous</option>
                        <?php for ($f=0; $f < $_SESSION['services']->count(); $f++) {
                            if (isset($_GET['service']) AND $_GET['service'] == $_SESSION['services'][$f]->nomService) {
                                echo '<option value="'.$_SESSION['services'][$f]->nomService.'" selected>'.$_SESSION['services'][$f]->nomService.'</option>';
                            } else {
                                echo '<option value="'.$_SESSION['services'][$f]->nomService.'">'.$_SESSION['services'][$f]->nomService.'</option>';
                            }
                        } ?>
                        </select>
                    </div>

                    <?php for ($g=0; $g < $_SESSION['services']->count(); $g++) {
                        if (isset($_GET['service']) AND $_GET['service'] == $_SESSION['services'][$g]->nomService) {
                            $nom = $_GET['service'];
                        }
                    }
                    $nomService = $nom ?? 'commande_complet';

                    $envoye = $envoyer ?? false;
                    if ($envoye) { ?>
                        <p class="confirm"><img class="img_confirm" src="http://localhost/PPE-3/Application/storage/app/public/confirm.png" alt="Icon de confirmation" /> La commande à bien été mise à jour</p><br />
                        <?php header('Refresh: 5; url=suivi');
                    }

                    if (isset($_SESSION["$nomService"][0])) { ?>
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
                                <?php for ($h=0; $h < $_SESSION["$nomService"]->count(); $h++) {
                                    if (($_SESSION["$nomService"][$h]->nomEtat == 'Validé' OR $_SESSION["$nomService"][$h]->nomEtat == 'En cours') AND !isset($afficher)) {
                                        echo 'Modifier l\'état';
                                        $afficher = true;
                                    }
                                } ?>
                                </th>
                            </tr>
                        <?php for ($i=0; $i < $_SESSION["$nomService"]->count(); $i++) { ?>
                            <tr>
                                <td>{{ $_SESSION["$nomService"][$i]->nom }}</td>
                                <td>{{ $_SESSION["$nomService"][$i]->prenom }}</td>
                                <td>{{ $_SESSION["$nomService"][$i]->nomCommandes }}</td>
                                <td>{{ $_SESSION["$nomService"][$i]->quantiteDemande }}</td>
                                <td>{{ $_SESSION["$nomService"][$i]->nomEtat }}</td>
                                <td>{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION["$nomService"][$i]->created_at)) }}</td>
                                <td>{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION["$nomService"][$i]->updated_at)) }}</td>
                                <td>
                                <?php if ($_SESSION["$nomService"][$i]->nomEtat == 'Validé') { ?>
                                    {!! Form::open(['url' => 'majetatcommande']) !!}
                                    {{ Form::hidden('id', $_SESSION["$nomService"][$i]->id) }}
                                    {{ Form::hidden('id_etat', '3') }}
                                    {{ Form::submit('En Cours') }}
                                    {!! Form::close() !!}
                                <?php } elseif ($_SESSION["$nomService"][$i]->nomEtat == 'En cours') { ?>
                                    {!! Form::open(['url' => 'majetatcommande', 'id' => 'livre']) !!}
                                    {{ Form::hidden('id', $_SESSION["$nomService"][$i]->id) }}
                                    {{ Form::hidden('id_etat', '4') }}
                                    {{ Form::submit('Livré') }}
                                    {!! Form::close() !!}

                                    {!! Form::open(['url' => 'majetatcommande']) !!}
                                    {{ Form::hidden('id', $_SESSION["$nomService"][$i]->id) }}
                                    {{ Form::hidden('id_etat', '5') }}
                                    {{ Form::submit('Annulé') }}
                                    {!! Form::close() !!}
                                <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </table>
                    <?php } else { ?>
                        <section id="commande_util_dep">
                            <h4>Liste des commandes des utilisateurs :</h4><br />
                            <p>Il n'y a pas encore de commandes d'utilisateurs de ce département.</p>
                        </section>
                    <?php } ?>
                <?php } else { ?>
                    <section id="commande_util">
                        <h4>Liste des commandes des utilisateurs :</h4><br />
                        <p>Il n'y a pas encore de commandes d'utilisateurs.</p>
                    </section>
                <?php } ?>
            <?php } else {
                if (isset($_SESSION['commande_utilisateur'][0])) { ?>
                    <div id="choix_etat">
                        <p id="etats">États :</p>
                        <select id="select_etat" onchange="selectionEtat()">
                            <option value="Tous">Tous</option>
                        <?php for ($j=0; $j < $_SESSION['etats']->count(); $j++) {
                            if (isset($_GET['etat']) AND $_GET['etat'] == $_SESSION['etats'][$j]->nomEtat) {
                                echo '<option value="'.$_SESSION['etats'][$j]->nomEtat.'" selected>'.$_SESSION['etats'][$j]->nomEtat.'</option>';
                            } else {
                                echo '<option value="'.$_SESSION['etats'][$j]->nomEtat.'">'.$_SESSION['etats'][$j]->nomEtat.'</option>';
                            }
                        } ?>
                        </select>
                    </div>

                    <?php for ($k=0; $k < $_SESSION['etats']->count(); $k++) {
                        if (isset($_GET['etat']) AND $_GET['etat'] == $_SESSION['etats'][$k]->nomEtat) {
                            $nom = $_GET['etat'];
                        }
                    }
                    $nomEtat = $nom ?? 'commande_utilisateur';

                    $envoye = $envoyer ?? false;
                    if ($envoye) { ?>
                        <p class="confirm"><img class="img_confirm" src="http://localhost/PPE-3/Application/storage/app/public/confirm.png" alt="Icon de confirmation" /> La commande à bien été mise à jour</p><br />
                        <?php header('Refresh: 5; url=suivi');
                    }

                    if (isset($_SESSION["$nomEtat"][0])) { ?>
                        <table id="liste_commandes">
                            <caption>Liste des commandes</caption>
                            <tr>
                                <th>Nom de la commande</th>
                                <th>Quantitée demandée</th>
                                <th>État</th>
                                <th>Création</th>
                                <th>Dernière mise à jour</th>
                            </tr>
                        <?php for ($l=0; $l < $_SESSION["$nomEtat"]->count(); $l++) { ?>
                            <tr>
                                <td>{{ $_SESSION["$nomEtat"][$l]->nomCommandes }}</td>
                                <td>{{ $_SESSION["$nomEtat"][$l]->quantiteDemande }}</td>
                                <td>{{ $_SESSION["$nomEtat"][$l]->nomEtat }}</td>
                                <td>{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION["$nomEtat"][$l]->created_at)) }}</td>
                                <td>{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION["$nomEtat"][$l]->updated_at)) }}</td>
                            </tr>
                        <?php } ?>
                        </table>
                    <?php } else { ?>
                        <section id="commande_pers_etat">
                            <h4>Liste des commandes :</h4><br />
                            <p>Vous n'avez pas de commandes avec cet état.</p>
                        </section>
                    <?php } ?>
                <?php } else { ?>
                    <section id="commande_pers">
                        <h4>Liste des commandes :</h4><br />
                        <p>Vous n'avez pas encore effectué de commandes.</p>
                    </section>
                <?php }

                if ($_SESSION['categorie'] == 'Valideur') {
                    if (isset($_SESSION['commande_valid'][0])) {
                        $envoye = $envoyer ?? false;
                        if ($envoye) { ?>
                            <p class="confirm"><img class="img_confirm" src="http://localhost/PPE-3/Application/storage/app/public/confirm.png" alt="Icon de confirmation" /> La commande à bien été mise à jour</p><br />
                            <?php header('Refresh: 5; url=suivi');
                        } ?>

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
                                <?php for ($m=0; $m < $_SESSION['commande_valid']->count(); $m++) {
                                    if (($_SESSION['commande_valid'][$m]->nomEtat == 'Prise en compte') AND !isset($afficher)) {
                                        echo 'Modifier l\'état';
                                        $afficher = true;
                                    }
                                } ?>
                                </th>
                            </tr>
                            <?php for ($n=0; $n < $_SESSION['commande_valid']->count(); $n++) { ?>
                                <tr>
                                    <td>{{ $_SESSION['commande_valid'][$n]->nom }}</td>
                                    <td>{{ $_SESSION['commande_valid'][$n]->prenom }}</td>
                                    <td>{{ $_SESSION['commande_valid'][$n]->nomCommandes }}</td>
                                    <td>{{ $_SESSION['commande_valid'][$n]->quantiteDemande }}</td>
                                    <td>{{ $_SESSION["commande_valid"][$n]->nomEtat }}</td>
                                    <td>{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['commande_valid'][$n]->created_at)) }}</td>
                                    <td>{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['commande_valid'][$n]->updated_at)) }}</td>
                                    <td>
                                        <?php if ($_SESSION["commande_valid"][$n]->nomEtat == 'Prise en compte') { ?>
                                            {!! Form::open(['url' => 'majetatcommande']) !!}
                                            {{ Form::hidden('id', $_SESSION['commande_valid'][$n]->id) }}
                                            {{ Form::hidden('id_etat', '2') }}
                                            {{ Form::submit('Valider') }}
                                            {!! Form::close() !!}
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } else { ?>
                        <section id="commande_valid">
                            <h4>Liste des commandes des utilisateurs :</h4><br />
                            <p>Il n'y a pas encore de commandes d'utilisateurs.</p>
                        </section>
                    <?php }
                }
            } ?>
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
                <?php for ($o=0; $o < $_SESSION['commandes_fini']->count(); $o++) { ?>
                    <tr>
                        <td class="tabl_comm">{{ $_SESSION['commandes_fini'][$o]->nomCommandes }}</td>
                        <td class="tabl_comm">{{ $_SESSION['commandes_fini'][$o]->quantiteDemande }}</td>
                        <td class="tabl_comm">{{ $_SESSION['commandes_fini'][$o]->nomEtat }}</td>
                        <td class="tabl_comm">{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['commandes_fini'][$o]->updated_at)) }}</td>
                    </tr>
                <?php } ?>
                </table>
            <?php } ?>
            <p id="service">Vous êtes dans le service : {{ $_SESSION['service'] }}</p>
            <p id="categorie">Votre rôle est : {{ $_SESSION['categorie'] }}</p>
        </footer>
    </body>
</html>
