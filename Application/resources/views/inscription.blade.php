<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" sizes="144x144" href="http://localhost/PPE-3/Application/storage/app/public/CCI.png" />
        <link rel="stylesheet" href="http://localhost/PPE-3/Application/resources/css/connexion.css">
        <title>Créer un compte</title>
    </head>
    <body>
        <header>
            <img src="http://localhost/PPE-3/Application/storage/app/public/logo-cci.png" alt="Logo de la CCI" />
            <h1>Inscription</h1>
        </header>
        {!! Form::open(['url' => 'inscription']) !!}
        {{ Form::label('nom', 'Nom') }}
        {{ Form::text('nom', $value = null, ['maxlength'=>'50', 'required']) }}
        <br>
        {{ Form::label('prenom', 'Prénom') }}
        {{ Form::text('prenom', $value = null, ['maxlength'=>'50', 'required']) }}
        <br>
        {{ Form::label('email', 'Adresse mail') }}
        {{ Form::email('email', $value = null, ['maxlength'=>'50', 'required']) }}
        <br>
        {{ Form::label('mdp', 'Mot de passe') }}
        {{ Form::password('mdp', ['required']) }}
        <br>
        {{ Form::label('categorie', 'Catégorie') }}
        {{ Form::select('categorie',[
                '1' => 'Utilisateur',
                '2' => 'Valideur',
            ]) }}
        <br>
        {{ Form::label('service', 'Service') }}
        <select name="service">
            <?php for ($i=0; $i < $Services->count(); $i++) { ?>
                <option value="{{ $Services[$i]->id }}">{{ $Services[$i]->nomService }}</option>
            <?php } ?>
        </select>
        <br>
        {{ Form::submit('Créer un compte', ['class'=>'submit']) }}
        {{ Form::button('Retour à la page de connexion', ['onclick'=>'window.location.href="http://localhost/PPE-3/Application/server.php"']) }}
        {!! Form::close() !!}
    </body>
</html>
