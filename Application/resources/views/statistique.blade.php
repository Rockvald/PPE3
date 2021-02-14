@php $css = 'statistique'; $title_h1 = 'Statistique'; @endphp
@include('head')
@include('menus')
@include('header')
        <section id="corps">
            <?php if ($donneesPersonnel['Personnel'][0]->nomCategorie != 'Administrateur') {
                  } else { ?>
            <?php } ?>
        </section>
@include('footer')
