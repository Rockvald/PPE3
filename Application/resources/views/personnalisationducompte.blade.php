@php $css = 'personnalisationducompte'; $title_h1 = 'Personnalisation du compte'; @endphp
@include('head')
@include('menus')
@include('header')
        <section id="corps">
            <?php if ($donneesPersonnel['Personnel'][0]->nomCategorie != 'Administrateur') {
                  } else { ?>
            <?php } ?>
        </section>
@include('footer')
