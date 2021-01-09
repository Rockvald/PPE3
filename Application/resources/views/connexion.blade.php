@php $css = 'connexion'; $title_h1 = "Connexion"; @endphp
@include('head')
        <header>
            <img src="{{ asset('storage/app/public/logo-cci.png') }}" alt="Logo de la CCI" />
            <h1>Connexion</h1>
        </header>
        @php ($refresh = $deconnexion ?? false)
        @if ($refresh)
            @php (header('Refresh: 0; url='.url('')))
            @php (exit)
        @endif
        @if (isset($_GET['page']))
            @switch ($_GET['page'])
                @case ('departements')
                    @php ($page = 'departements')
                    @break
                @case ('fournitures')
                    @php ($page = 'fournitures')
                    @break
                @case ('famillesfournitures')
                    @php ($page = 'famillesfournitures')
                    @break
                @case ('messagerie')
                    @php ($page = 'messagerie')
                    @break
                @case ('statistique')
                    @php ($page = 'statistique')
                    @break
                @case ('demandesspecifiques')
                    @php ($page = 'demandesspecifiques')
                    @break
                @case ('suivi')
                    @php ($page = 'suivi')
                    @break
                @case ('personnalisationducompte')
                    @php ($page = 'personnalisationducompte')
                    @break
                @default:
                    @php ($page = 'accueil')
                    @break
            @endswitch
        @else
            @php ($page = 'accueil')
        @endif

        @if (isset($erreur))
            @if ($erreur == 'mail')
                <p class="erreur"><img class="img_erreur" src="{{ asset('storage/app/public/warning.png') }}" alt="Icone d'erreur" /> L'adresse mail est incorrect !</p>
            @elseif ($erreur == 'mdp')
                <p class="erreur"><img class="img_erreur" src="{{ asset('storage/app/public/warning.png') }}" alt="Icone d'erreur" /> Le mot de passe est incorrect !</p>
            @endif
        @endif

        {!! Form::open(['url' => 'connexion']) !!}
        {{ Form::hidden('page', $page) }}
        {{ Form::label('email', 'Adresse mail') }}
        {{ Form::email('email', $value = $mail ?? null, ['required']) }}
        <br>
        {{ Form::label('mdp', 'Mot de passe') }}
        {{ Form::password('mdp', ['required']) }}
        <br>
        {{ Form::submit('Se connecter', ['class'=>'submit']) }}
        {{ Form::button('Créer un compte', ['onclick'=>'window.location.href="'.url('inscription').'"']) }}
        {!! Form::close() !!}
    </body>
</html>
