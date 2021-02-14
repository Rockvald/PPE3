@php $css = 'messagerie'; $title_h1 = 'Méssagerie'; @endphp
@include('head')
@include('menus')
@include('header')
        <section id="corps">
            <?php if ($donneesPersonnel['Personnel'][0]->nomCategorie != 'Administrateur') {
                  } else { ?>
            <?php } ?>
        </section>
@include('footer')
