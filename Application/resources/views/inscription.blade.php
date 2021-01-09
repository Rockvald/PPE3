@php $css = 'connexion'; $title_h1 = "Inscription"; @endphp
@include('head')
        <header>
            <img src="{{ asset('storage/app/public/logo-cci.png') }}" alt="Logo de la CCI" />
            <h1>Inscription</h1>
        </header>
        {!! Form::open(['url' => 'inscription']) !!}
        {{ Form::hidden('page', 'accueil') }}
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
