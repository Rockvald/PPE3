<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" />
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" sizes="144x144" href="http://localhost/PPE3/Application/storage/app/public/CCI.png" />
        <link rel="stylesheet" href="http://localhost/PPE3/Application/resources/css/fournitures.css" />
        <title>Fournitures</title>
        <script type="text/javascript">
            function selectionFamilles() {
                var famille = document.getElementById("select_familles").value;
                if (famille == 'Tous') {
                    window.location.href = 'fournitures';
                } else {
                    window.location.href = 'fournitures?famille=' + famille ;
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
            <h1>Fournitures</h1>
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
                <?php for ($f=0; $f < $_SESSION['commandes']->count(); $f++) { ?>
                    <tr>
                        <td class="tabl_comm">{{ $_SESSION['commandes'][$f]->nomCommandes }}</td>
                        <td class="tabl_comm">{{ $_SESSION['commandes'][$f]->quantiteDemande }}</td>
                        <td class="tabl_comm">{{ $_SESSION['commandes'][$f]->nomEtat }}</td>
                        <td class="tabl_comm">{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['commandes'][$f]->updated_at)) }}</td>
                    </tr>
                <?php } ?>
                </table>
            <?php } ?>
        </header>
        <section id="corps">
            <?php if (isset($reponse)) { ?>
                <a href="fournitures" id="retour">< Retour à la liste des fournitures</a><br /><br />
                <?php if ($reponse) { ?>
                    <table id="resultat_recherche">
                        <caption>Résultat de la recherche :</caption>
                        <tr>
                            <th>Photo</th>
                            <th class="tabl_fourn">Nom</th>
                            <th class="tabl_fourn">Description</th>
                            <th class="tabl_fourn">Famille</th>
                            <th class="tabl_fourn">Quantitée disponible</th>
                        <?php if ($_SESSION['categorie'] != 'Administrateur') { ?>
                            <th class="tabl_fourn">Quantitée demandée</th>
                        <?php } ?>
                        </tr>
                    <?php for ($g=0; $g < $_SESSION['recherche']->count(); $g++) { ?>
                        <tr>
                            <td><img class="photo_fournitures" src="http://localhost/PPE3/Application/storage/app/public/{{ $_SESSION['recherche'][$g]->nomPhoto }}.jpg" /></td>
                            <td>{{ $_SESSION['recherche'][$g]->nomFournitures }}</td>
                            <td>{{ $_SESSION['recherche'][$g]->descriptionFournitures }}</td>
                            <td>
                                <?php if ($_SESSION['categorie'] == 'Administrateur') { ?>
                                    {!! Form::open(['url' => 'modificationfamille']) !!}
                                    {{ Form::hidden('id', $_SESSION['recherche'][$g]->id) }}
                                    <select name="nom_famille">
                                        <?php for ($h=0; $h < $_SESSION['famillesfournitures']->count(); $h++) {
                                            if ($_SESSION['famillesfournitures'][$h]->nomFamille == $_SESSION['recherche'][$g]->nomFamille) {
                                                echo '<option value="'.$_SESSION['famillesfournitures'][$h]->nomFamille.'" selected>'.$_SESSION['famillesfournitures'][$h]->nomFamille.'</option>';
                                            } else {
                                                echo '<option value="'.$_SESSION['famillesfournitures'][$h]->nomFamille.'">'.$_SESSION['famillesfournitures'][$h]->nomFamille.'</option>';
                                            } ?>
                                        <?php } ?>
                                    </select>
                                    {{ Form::submit('Mettre à jour', ['class'=>'submit']) }}
                                    {!! Form::close() !!}
                                <?php } else { ?>
                                    {{ $_SESSION['recherche'][$g]->nomFamille }}
                                <?php } ?>
                            </td>
                            <td>
                            <?php if ($_SESSION['categorie'] == 'Administrateur') { ?>
                                {!! Form::open(['url' => 'majquantite']) !!}
                                {{ Form::hidden('id', $_SESSION['recherche'][$g]->id) }}
                                {{ Form::number('quantite_disponible', $_SESSION['recherche'][$g]->quantiteDisponible, ['min'=>'0', 'max'=>'100']) }}
                                {{ Form::submit('Mettre à jour', ['class'=>'submit']) }}
                                {!! Form::close() !!}
                            <?php } else { ?>
                                {{ $_SESSION['recherche'][$g]->quantiteDisponible }}
                            <?php } ?>
                            </td>
                        <?php if ($_SESSION['categorie'] != 'Administrateur') { ?>
                            <td>
                                {!! Form::open(['url' => 'commander']) !!}
                                {{ Form::hidden('id', $_SESSION['recherche'][$g]->id) }}
                                {{ Form::hidden('nom_fourniture', $_SESSION['recherche'][$g]->nomFournitures) }}
                                {{ Form::number('quantite_demande', '1', ['min'=>'1', 'max'=>$_SESSION['recherche'][$g]->quantiteDisponible]) }}
                                {{ Form::submit('Commander', ['class'=>'submit']) }}
                                {!! Form::close() !!}
                            </td>
                        <?php } ?>
                        </tr>
                    <?php } ?>
                    </table>
                <?php } else { ?>
                    <p>Aucun résultat trouvé pour votre recherche.</p>
                <?php }
            } else {
                $valide = $valider ?? false;
                if ($valide) { ?>
                    <p class="confirm"><img class="img_confirm" src="http://localhost/PPE3/Application/storage/app/public/confirm.png" alt="Icon de confirmation" /> La mise à jour à bien été prise en compte</p><br />
                    <?php header('Refresh: 5; url=fournitures');
                }

                $fichiertropgros = $tropgros ?? false;
                if ($fichiertropgros) { ?>
                    <p class="erreur"><img class="img_erreur" src="http://localhost/PPE3/Application/storage/app/public/warning.png" alt="Icon de confirmation" /> Le poids de l'image est trop volumineux ! (Max : 500ko)</p><br />
                <?php }

                $formatinvalide = $invalide ?? false;
                if ($formatinvalide) { ?>
                    <p class="erreur"><img class="img_erreur" src="http://localhost/PPE3/Application/storage/app/public/warning.png" alt="Icon de confirmation" /> Le format de l'image n'est pas valide !</p><br />
                <?php }

                $creation = $cree ?? false;
                if ($creation) { ?>
                    <p class="confirm"><img class="img_confirm" src="http://localhost/PPE3/Application/storage/app/public/confirm.png" alt="Icon de confirmation" /> L'article a bien été créé</p><br />
                    <?php header('Refresh: 5; url=fournitures');
                }

                $creation_commande = $commande_cree ?? false;
                if ($creation_commande) { ?>
                    <p class="confirm"><img class="img_confirm" src="http://localhost/PPE3/Application/storage/app/public/confirm.png" alt="Icon de confirmation" /> La commande a bien été prise en compte</p><br />
                    <?php header('Refresh: 5; url=fournitures');
                }

                if ($_SESSION['categorie'] == 'Administrateur') { ?>
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
                                    <?php for ($i=0; $i < $_SESSION['famillesfournitures']->count(); $i++) {
                                        echo '<option value="'.$_SESSION['famillesfournitures'][$i]->nomFamille.'">'.$_SESSION['famillesfournitures'][$i]->nomFamille.'</option>';
                                    } ?>
                                </select>
                            </td>
                            <td>
                                {{ Form::number('quantite_disponible', $requete->quantite_disponible ?? '1', ['min'=>'1', 'max'=>'100']) }}
                                {{ Form::submit('Créer l\'article') }}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    </table>
                <?php } ?>

                <div id="choix_familles">
                    <p id="familles">Familles :</p>
                    <select id="select_familles" onchange="selectionFamilles()">
                        <option value="Toutes">Toutes</option>
                    <?php for ($j=0; $j < $_SESSION['famillesfournitures']->count(); $j++) {
                        if (isset($_GET['famille']) AND $_GET['famille'] == $_SESSION['famillesfournitures'][$j]->nomFamille) {
                            echo '<option value="'.$_SESSION['famillesfournitures'][$j]->nomFamille.'" selected>'.$_SESSION['famillesfournitures'][$j]->nomFamille.'</option>';
                        } else {
                            echo '<option value="'.$_SESSION['famillesfournitures'][$j]->nomFamille.'">'.$_SESSION['famillesfournitures'][$j]->nomFamille.'</option>';
                        }
                    } ?>
                    </select>
                </div>

                <?php for ($k=0; $k < $_SESSION['famillesfournitures']->count(); $k++) {
                    if (isset($_GET['famille']) AND $_GET['famille'] == $_SESSION['famillesfournitures'][$k]->nomFamille) {
                        $nom = $_GET['famille'];
                    }
                }
                $nomFamille = $nom ?? 'fournitures';

                if (isset($_SESSION["$nomFamille"][0])) { ?>
                    <table id="liste_fourniture">
                        <caption>Liste des fournitures :</caption>
                        <tr>
                            <th>Photo</th>
                            <th class="tabl_fourn">Nom</th>
                            <th class="tabl_fourn">Description</th>
                            <th class="tabl_fourn">Famille</th>
                            <th class="tabl_fourn">Quantitée disponible</th>
                        <?php if ($_SESSION['categorie'] != 'Administrateur') { ?>
                            <th class="tabl_fourn">Quantitée demandée</th>
                        <?php } ?>
                        </tr>
                    <?php for ($l=0; $l < $_SESSION["$nomFamille"]->count(); $l++) { ?>
                        <tr>
                            <td><img class="photo_fournitures" src='http://localhost/PPE3/Application/storage/app/public/{{ $_SESSION["$nomFamille"][$l]->nomPhoto }}.jpg' /></td>
                            <td>{{ $_SESSION["$nomFamille"][$l]->nomFournitures }}</td>
                            <td>{{ $_SESSION["$nomFamille"][$l]->descriptionFournitures }}</td>
                            <td>
                                <?php if ($_SESSION['categorie'] == 'Administrateur') { ?>
                                    {!! Form::open(['url' => 'modificationfamille']) !!}
                                    {{ Form::hidden('id', $_SESSION["$nomFamille"][$l]->id) }}
                                    <select name="nom_famille">
                                        <?php for ($m=0; $m < $_SESSION['famillesfournitures']->count(); $m++) {
                                            if ($_SESSION['famillesfournitures'][$m]->nomFamille == $_SESSION["$nomFamille"][$l]->nomFamille) {
                                                echo '<option value="'.$_SESSION['famillesfournitures'][$m]->nomFamille.'" selected>'.$_SESSION['famillesfournitures'][$m]->nomFamille.'</option>';
                                            } else {
                                                echo '<option value="'.$_SESSION['famillesfournitures'][$m]->nomFamille.'">'.$_SESSION['famillesfournitures'][$m]->nomFamille.'</option>';
                                            } ?>
                                        <?php } ?>
                                    </select>
                                    {{ Form::submit('Mettre à jour', ['class'=>'submit']) }}
                                    {!! Form::close() !!}
                                <?php } else { ?>
                                    {{ $_SESSION["$nomFamille"][$l]->nomFamille }}
                                <?php } ?>
                            </td>
                            <td>
                            <?php if ($_SESSION['categorie'] == 'Administrateur') { ?>
                                {!! Form::open(['url' => 'majquantite']) !!}
                                {{ Form::hidden('id', $_SESSION["$nomFamille"][$l]->id) }}
                                {{ Form::number('quantite_disponible', $_SESSION["$nomFamille"][$l]->quantiteDisponible, ['min'=>'0', 'max'=>'100']) }}
                                {{ Form::submit('Mettre à jour', ['class'=>'submit']) }}
                                {!! Form::close() !!}
                            <?php } else { ?>
                                {{ $_SESSION["$nomFamille"][$l]->quantiteDisponible }}
                            <?php } ?>
                            </td>
                        <?php if ($_SESSION['categorie'] != 'Administrateur') { ?>
                            <td>
                                {!! Form::open(['url' => 'commander']) !!}
                                {{ Form::hidden('id', $_SESSION["$nomFamille"][$l]->id) }}
                                {{ Form::hidden('nom_fourniture', $_SESSION["$nomFamille"][$l]->nomFournitures) }}
                                {{ Form::number('quantite_demande', '1', ['min'=>'1', 'max'=>$_SESSION["$nomFamille"][$l]->quantiteDisponible]) }}
                                {{ Form::submit('Commander', ['class'=>'submit']) }}
                                {!! Form::close() !!}
                            </td>
                        <?php } ?>
                        </tr>
                    <?php } ?>
                    </table>
                <?php } else { ?>
                    <section id="liste_fourniture_famille">
                        <h4>Liste des fournitures :</h4><br />
                        <p>Il n'y a pas de fournitures de cette famille.</p>
                    </section>
                <?php }
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
                <?php for ($n=0; $n < $_SESSION['commandes_fini']->count(); $n++) { ?>
                    <tr>
                        <td class="tabl_comm">{{ $_SESSION['commandes_fini'][$n]->nomCommandes }}</td>
                        <td class="tabl_comm">{{ $_SESSION['commandes_fini'][$n]->quantiteDemande }}</td>
                        <td class="tabl_comm">{{ $_SESSION['commandes_fini'][$n]->nomEtat }}</td>
                        <td class="tabl_comm">{{ date('G:i:s \l\e d-m-Y', strtotime($_SESSION['commandes_fini'][$n]->updated_at)) }}</td>
                    </tr>
                <?php } ?>
                </table>
            <?php } ?>
            <p id="service">Vous êtes dans le service : {{ $_SESSION['service'] }}</p>
            <p id="categorie">Votre rôle est : {{ $_SESSION['categorie'] }}</p>
        </footer>
    </body>
</html>
