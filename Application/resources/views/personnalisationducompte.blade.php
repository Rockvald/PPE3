@php $css = 'personnalisationducompte'; $title_h1 = 'Personnalisation du compte'; @endphp
@include('head')
@include('menus')
@include('header')
        <section id="corps">
            <?php if ($_SESSION['categorie'] != 'Administrateur') {
                  } else { ?>
            <?php } ?>
        </section>
@include('footer')
