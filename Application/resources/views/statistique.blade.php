@php $css = 'statistique'; $title_h1 = 'Statistique'; @endphp
@include('head')
@include('menus')
@include('header')
        <section id="corps">
            <?php if ($_SESSION['categorie'] != 'Administrateur') {
                  } else { ?>
            <?php } ?>
        </section>
@include('footer')
