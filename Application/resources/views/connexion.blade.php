<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" />
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" sizes="144x144" href="http://localhost/PPE3/Application/storage/app/public/CCI.png" />
        <link rel="stylesheet" href="http://localhost/PPE3/Application/resources/css/connexion.css" />
        <title>Connexion</title>
    </head>
    <body>
        <header>
            <img src="http://localhost/PPE3/Application/storage/app/public/logo-cci.png" alt="Logo de la CCI" />
            <h1>Connexion</h1>
        </header>
        <?php $refresh = $deconnexion ?? false;
        if ($refresh) {
            header('Refresh: 0; url=http://localhost/PPE3/Application/server.php');
            exit;
        }

        if (isset($_GET['page'])) {
            switch ($_GET['page']) {
                case 'departements':
                    $page = 'departements';
                    break;
                case 'fournitures':
                    $page = 'fournitures';
                    break;
                case 'famillesfournitures':
                    $page = 'famillesfournitures';
                    break;
                case 'messagerie':
                    $page = 'messagerie';
                    break;
                case 'statistique':
                    $page = 'statistique';
                    break;
                case 'demandesspecifiques':
                    $page = 'demandesspecifiques';
                    break;
                case 'suivi':
                    $page = 'suivi';
                    break;
                case 'personnalisationducompte':
                    $page = 'personnalisationducompte';
                    break;
                default:
                    $page = 'accueil';
                    break;
            }
        } else {
            $page = 'accueil';
        }

        if (isset($erreur)) {
            if ($erreur == 'mail') { ?>
                <p class="erreur"><img class="img_erreur" src="http://localhost/PPE3/Application/storage/app/public/warning.png" alt="Icone d'erreur" /> L'adresse mail est incorrect !</p>
            <?php } elseif ($erreur == 'mdp') { ?>
                <p class="erreur"><img class="img_erreur" src="http://localhost/PPE3/Application/storage/app/public/warning.png" alt="Icone d'erreur" /> Le mot de passe est incorrect !</p>
            <?php }
        } ?>

        {!! Form::open(['url' => 'connexion']) !!}
        {{ Form::hidden('page', $page) }}
        {{ Form::label('email', 'Adresse mail') }}
        {{ Form::email('email', $value = $mail ?? null, ['required']) }}
        <br>
        {{ Form::label('mdp', 'Mot de passe') }}
        {{ Form::password('mdp', ['required']) }}
        <br>
        {{ Form::submit('Se connecter', ['class'=>'submit']) }}
        {{ Form::button('Créer un compte', ['onclick'=>'window.location.href="http://localhost/PPE3/Application/server.php/inscription"']) }}
        {!! Form::close() !!}
    </body>
</html>
