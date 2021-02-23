@php $css = 'connexion'; $title_h1 = "Inscription"; @endphp
@include('head')
        <header>
            <img src="{{ asset('storage/app/public/logo-cci.png') }}" alt="Logo de la CCI" />
            <h1>Inscription</h1>
        </header>
        @if (isset($erreur))
            <p class="erreur"><img class="img_erreur" src="{{ asset('storage/app/public/warning.png') }}" alt="Icone d'erreur" /> Les mots de passe sont différents !</p>
        @endif

        {!! Form::open(['url' => 'inscription']) !!}
        {{ Form::hidden('page', 'accueil') }}
        {{ Form::label('nom', 'Nom') }}
        {{ Form::text('nom', $value = $nom ?? null, ['maxlength'=>'50', 'required']) }}
        <br>
        {{ Form::label('prenom', 'Prénom') }}
        {{ Form::text('prenom', $value = $prenom ?? null, ['maxlength'=>'50', 'required']) }}
        <br>
        {{ Form::label('email', 'Adresse mail') }}
        {{ Form::email('email', $value = $mail ?? null, ['maxlength'=>'50', 'required']) }}
        <br>
        {{ Form::label('mdp', 'Mot de passe') }}
        {{ Form::password('mdp', ['required']) }}
        <br>
        {{ Form::label('confirm_mdp', 'Confirmation du mot de passe') }}
        {{ Form::password('confirm_mdp', ['required']) }}
        <br>
        {{ Form::label('categorie', 'Catégorie') }}
        {{ Form::select('categorie',[
                '1' => 'Utilisateur',
                '2' => 'Valideur',
            ]) }}
        <br>
        {{ Form::label('service', 'Service') }}
        <select name="service">
            @foreach ($Services as $lignes => $service)
                <option value="{{ $service->id }}">{{ $service->nomService }}</option>
            @endforeach
        </select>
        <br>
        {{ Form::submit('Créer un compte', ['class'=>'submit']) }}
        {{ Form::button('Retour à la page de connexion', ['onclick'=>'window.location.href="'.url('/').'"']) }}
        {!! Form::close() !!}
    </body>
</html>
