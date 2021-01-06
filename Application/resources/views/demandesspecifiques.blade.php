<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" />
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" sizes="144x144" href="http://localhost/PPE3/Application/storage/app/public/CCI.png" />
        <link rel="stylesheet" href="http://localhost/PPE3/Application/resources/css/demandesspecifiques.css" />
        <title>Demandes spécifiques</title>
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
                    window.location.href = 'demandesspecifiques';
                } else {
                    window.location.href = 'demandesspecifiques?service=' + service ;
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
            <h1>Demandes spécifiques</h1>
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
                <?php for ($g=0; $g < $_SESSION['commandes']->count(); $g++) { ?>
                    <tr>
                        <td class="tabl_comm">{{ $_SESSION['commandes'][$g]->nomCommandes }}</td>
                        <td class="tabl_comm">{{ $_SESSION['commandes'][$g]->quantiteDemande }}</td>
                        <td class="tabl_comm">{{ $_SESSION['commandes'][$g]->nomEtat }}</td>
                        <td class="tabl_comm">{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['commandes'][$g]->updated_at)) }}</td>
                    </tr>
                <?php } ?>
                </table>
            <?php } ?>
        </header>
        <?php if ($_SESSION['categorie'] == 'Administrateur') { ?>
            <div id="bouton_imprimer"><button onclick="imprimer('corps');">Imprimer</button></div>
        <?php } ?>
        <section id="corps">
            <?php if ($_SESSION['categorie'] != 'Administrateur') {
                $confirm = $vrai ?? false;
                if ($confirm) { ?>
                    <p class="confirm"><img class="img_confirm" src="http://localhost/PPE3/Application/storage/app/public/confirm.png" alt="Icon de confirmation" /> Votre demande a bien été envoyée</p><br />
                <?php header('Refresh: 5; url=demandesspecifiques');
                }

                $envoye = $envoyer ?? false;
                if ($envoye) { ?>
                    <p class="confirm"><img class="img_confirm" src="http://localhost/PPE3/Application/storage/app/public/confirm.png" alt="Icon de confirmation" /> La demande à bien été mise à jour</p><br />
                    <?php header('Refresh: 5; url=demandesspecifiques');
                } ?>

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

                <?php if ($_SESSION['categorie'] == 'Valideur') {
                    if (isset($_SESSION['demandes_valid'][0])) { ?>
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
                                <?php for ($h=0; $h < $_SESSION['demandes_valid']->count(); $h++) {
                                    if (($_SESSION["demandes_valid"][$h]->nomEtat == 'Prise en compte') AND !isset($afficher)) {
                                        echo 'Modifier l\'état';
                                        $afficher = true;
                                    }
                                } ?>
                                </th>
                            </tr>
                            <?php for ($i=0; $i < $_SESSION['demandes_valid']->count(); $i++) { ?>
                                <tr>
                                    <td>{{ $_SESSION['demandes_valid'][$i]->nom }}</td>
                                    <td>{{ $_SESSION['demandes_valid'][$i]->prenom }}</td>
                                    <td>{{ $_SESSION['demandes_valid'][$i]->nomDemande }}</td>
                                    <td>{{ $_SESSION['demandes_valid'][$i]->quantiteDemande }}</td>
                                    <td class="lien_produit">
                                        <?php if ($_SESSION['demandes_valid'][$i]->lienProduit != 'Aucun lien fourni') { ?>
                                            <a href="{{ $_SESSION['demandes_valid'][$i]->lienProduit }}" target="_blank">{{ $_SESSION['demandes_valid'][$i]->lienProduit }}</a>
                                        <?php } else {
                                            echo $_SESSION['demandes_valid'][$i]->lienProduit;
                                        } ?>
                                    </td>
                                    <td>{{ $_SESSION["demandes_valid"][$i]->nomEtat }}</td>
                                    <td>{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['demandes_valid'][$i]->created_at)) }}</td>
                                    <td>{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['demandes_valid'][$i]->updated_at)) }}</td>
                                    <td>
                                    <?php if ($_SESSION["demandes_valid"][$i]->nomEtat == 'Prise en compte') { ?>
                                        {!! Form::open(['url' => 'majetatdemande']) !!}
                                        {{ Form::hidden('id', $_SESSION['demandes_valid'][$i]->id) }}
                                        {{ Form::hidden('id_etat', '2') }}
                                        {{ Form::submit('Valider') }}
                                        {!! Form::close() !!}
                                    <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } else { ?>
                        <section id="demandes_utilisateur">
                            <h4>Demandes utilisateurs :</h4><br />
                            <p>Vous n'avez pas de demandes d'utilisateurs.</p>
                        </section>
                    <?php }
                }
                if (isset($_SESSION['demandes_pers'][0])) { ?>
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
                    <?php for ($j=0; $j < $_SESSION['demandes_pers']->count(); $j++) { ?>
                        <tr>
                            <td>{{ $_SESSION['demandes_pers'][$j]->nomDemande }}</td>
                            <td>{{ $_SESSION['demandes_pers'][$j]->quantiteDemande }}</td>
                            <td class="lien_produit">
                                <?php if ($_SESSION['demandes_pers'][$j]->lienProduit != 'Aucun lien fourni') { ?>
                                <a href="{{ $_SESSION['demandes_pers'][$j]->lienProduit }}" target="_blank">{{ $_SESSION['demandes_pers'][$j]->lienProduit }}</a>
                                <?php } else {
                                    echo $_SESSION['demandes_pers'][$j]->lienProduit;
                                } ?>
                            </td>
                            <td>{{ $_SESSION['demandes_pers'][$j]->nomEtat }}</td>
                            <td>{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['demandes_pers'][$j]->created_at)) }}</td>
                            <td>{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['demandes_pers'][$j]->updated_at)) }}</td>
                        </tr>
                    <?php } ?>
                    </table>
                <?php } else { ?>
                    <section id="demandes_spec">
                        <h4>Demandes spécifiques :</h4><br />
                        <p>Vous n'avez pas encore effectué de demandes.</p>
                    </section>
                <?php }
            } else {
                if (isset($_SESSION['demandes'][0])) { ?>
                    <div id="choix_departements">
                        <p id="departements">Départements :</p>
                        <select id="select_services" onchange="selectionServices()">
                            <option value="Tous">Tous</option>
                        <?php for ($k=0; $k < $_SESSION['services']->count(); $k++) {
                            if (isset($_GET['service']) AND $_GET['service'] == $_SESSION['services'][$k]->nomService) {
                                echo '<option value="'.$_SESSION['services'][$k]->nomService.'" selected>'.$_SESSION['services'][$k]->nomService.'</option>';
                            } else {
                                echo '<option value="'.$_SESSION['services'][$k]->nomService.'">'.$_SESSION['services'][$k]->nomService.'</option>';
                            }
                        } ?>
                        </select>
                    </div>

                    <?php for ($l=0; $l < $_SESSION['services']->count(); $l++) {
                        if (isset($_GET['service']) AND $_GET['service'] == $_SESSION['services'][$l]->nomService) {
                            $nom = $_GET['service'];
                        }
                    }
                    $nomService = $nom ?? 'demandes';

                    $envoye = $envoyer ?? false;
                    if ($envoye) { ?>
                        <p id="confirm"><img class="img_confirm" src="http://localhost/PPE3/Application/storage/app/public/confirm.png" alt="Icon de confirmation" /> La demande à bien été mise à jour</p><br />
                        <?php header('Refresh: 5; url=demandesspecifiques');
                    }

                    if (isset($_SESSION["$nomService"][0])) { ?>
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
                                <?php for ($m=0; $m < $_SESSION["$nomService"]->count(); $m++) {
                                    if (($_SESSION["$nomService"][$m]->nomEtat == 'Validé' OR $_SESSION["$nomService"][$m]->nomEtat == 'En cours') AND !isset($afficher)) {
                                        echo 'Modifier l\'état';
                                        $afficher = true;
                                    }
                                } ?>
                                </th>
                            </tr>
                        <?php for ($n=0; $n < $_SESSION["$nomService"]->count(); $n++) { ?>
                            <tr>
                                <td>{{ $_SESSION["$nomService"][$n]->nom }}</td>
                                <td>{{ $_SESSION["$nomService"][$n]->prenom }}</td>
                                <td>{{ $_SESSION["$nomService"][$n]->nomDemande }}</td>
                                <td>{{ $_SESSION["$nomService"][$n]->quantiteDemande }}</td>
                                <td class="lien_produit">
                                    <?php if ($_SESSION["$nomService"][$n]->lienProduit != 'Aucun lien fourni') { ?>
                                        <a href="<?php echo $_SESSION["$nomService"][$n]->lienProduit; ?>" target="_blank">{{ $_SESSION["$nomService"][$n]->lienProduit }}</a>
                                    <?php } else {
                                        echo $_SESSION["$nomService"][$n]->lienProduit;
                                    } ?>
                                </td>
                                <td>{{ $_SESSION["$nomService"][$n]->nomEtat }}</td>
                                <td>{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION["$nomService"][$n]->created_at)) }}</td>
                                <td>{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION["$nomService"][$n]->updated_at)) }}</td>
                                <td>
                                <?php if ($_SESSION["$nomService"][$n]->nomEtat == 'Validé') { ?>
                                    {!! Form::open(['url' => 'majetatdemande']) !!}
                                    {{ Form::hidden('id', $_SESSION["$nomService"][$n]->id) }}
                                    {{ Form::hidden('id_etat', '3') }}
                                    {{ Form::submit('En Cours') }}
                                    {!! Form::close() !!}
                                <?php } elseif ($_SESSION["$nomService"][$n]->nomEtat == 'En cours') { ?>
                                    {!! Form::open(['url' => 'majetatdemande', 'id' => 'livre']) !!}
                                    {{ Form::hidden('id', $_SESSION["$nomService"][$n]->id) }}
                                    {{ Form::hidden('id_etat', '4') }}
                                    {{ Form::submit('Livré') }}
                                    {!! Form::close() !!}

                                    {!! Form::open(['url' => 'majetatdemande']) !!}
                                    {{ Form::hidden('id', $_SESSION["$nomService"][$n]->id) }}
                                    {{ Form::hidden('id_etat', '5') }}
                                    {{ Form::submit('Annulé') }}
                                    {!! Form::close() !!}
                                <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </table>
                    <?php } else { ?>
                        <section id="demande_util_dep">
                            <h4>Liste des demandes des utilisateurs :</h4><br />
                            <p>Il n'y a pas encore de demandes d'utilisateurs de ce département.</p>
                        </section>
                    <?php } ?>
                <?php } else { ?>
                    <section id="demande_util">
                        <h4>Liste des demandes des utilisateurs :</h4><br />
                        <p>Il n'y a pas encore de demandes d'utilisateurs.</p>
                    </section>
                <?php } ?>
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
