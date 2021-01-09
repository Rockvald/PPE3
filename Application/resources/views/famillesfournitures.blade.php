@php $css = 'famillesfournitures'; $title_h1 = 'Familles Fournitures'; @endphp
@include('head')
@include('menus')
@include('header')
        <section id="corps">
            @php ($creation = $cree ?? false)
            @if ($creation)
                <p class="confirm"><img class="img_confirm" src="{{ asset('storage/app/public/confirm.png') }}" alt="Icon de confirmation" /> La famille a bien été créé</p><br />
                @php (header('Refresh: 5; url=famillesfournitures'))
            @endif
            <table id="ajout_famille">
                <caption>Ajouter une famille</caption>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th></th>
                </tr>
                <tr>
                    <td>
                        {!! Form::open(['url' => 'creationfamille']) !!}
                        {{ Form::text('nom_famille', $value = null, ['maxlength'=>'50', 'placeholder'=>'Ex: Marqueurs', 'required']) }}
                    </td>
                    <td>{{ Form::text('description_famille', $value = null, ['maxlength'=>'50', 'required']) }}</td>
                    <td>
                        {{ Form::submit('Créer la famille') }}
                        {!! Form::close() !!}
                    </td>
                </tr>
            </table>

            <table id="liste_famille">
                <caption>Liste des familles</caption>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                </tr>
                @foreach ($_SESSION['famillesfournitures'] as $lignes => $famillefourniture)
                    <tr>
                        <td>{{ $famillefourniture->nomFamille }}</td>
                        <td>{{ $famillefourniture->descriptionFamille }}</td>
                    </tr>
                @endforeach
            </table>
        </section>
@include('footer')
