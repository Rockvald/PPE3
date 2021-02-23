@php $css = 'personnalisationducompte'; $title_h1 = 'Personnalisation du compte'; @endphp
@include('head')
@include('menus')
@include('header')
        <section id="corps">
            @if (isset($erreur))
                <p class="erreur"><img class="img_erreur" src="{{ asset('storage/app/public/warning.png') }}" alt="Icone d'erreur" /> Les mots de passe sont différents !</p>
            @endif

            @php ($confirmation = $confirm ?? false)
            @if ($confirmation)
                <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> Les modifications ont bien été prisent en compte</p><br />
                @php (header('Refresh: 5; url=accueil'))
            @endif

            {!! Form::open(['url' => 'modificationPersonnalisation', 'id'=>'formpersonnalisation']) !!}
            {{ Form::hidden('page', 'personnalisationducompte') }}
            {{ Form::label('nom', 'Nom', ['class'=>'formpersonnalisation']) }}
            {{ Form::text('nom', $value = $donneesPersonnel['Personnel'][0]->nom ?? null, ['maxlength'=>'50']) }}
            <br>
            {{ Form::label('prenom', 'Prénom', ['class'=>'formpersonnalisation']) }}
            {{ Form::text('prenom', $value = $donneesPersonnel['Personnel'][0]->prenom ?? null, ['maxlength'=>'50']) }}
            <br>
            {{ Form::label('mail', 'Adresse mail', ['class'=>'formpersonnalisation']) }}
            {{ Form::email('mail', $value = $donneesPersonnel['Personnel'][0]->mail ?? null, ['maxlength'=>'50']) }}
            <br>
            {{ Form::label('mdp', 'Mot de passe', ['class'=>'formpersonnalisation']) }}
            {{ Form::password('mdp') }}
            <br>
            {{ Form::label('confirm_mdp', 'Confirmation du mot de passe', ['class'=>'formpersonnalisation']) }}
            {{ Form::password('comfirm_mdp') }}
            <br>
            {{ Form::submit('Enregister les modifications', ['class'=>'submit']) }}
            {!! Form::close() !!}
        </section>
@include('footer')
