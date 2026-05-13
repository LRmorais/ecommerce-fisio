<?php
/**
 * Template da página inicial — Viva Leve Child Theme
 *
 * @package VivaLeveChild
 */

get_header();
?>

<?php get_template_part( 'template-parts/slider' ); ?>
<?php get_template_part( 'template-parts/beneficios' ); ?>

<main id="main" class="site-main vl-home-main" role="main">

    <?php get_template_part( 'template-parts/categorias-home' ); ?>
    <?php get_template_part( 'template-parts/produtos-home' ); ?>
    <?php get_template_part( 'template-parts/consulta-especialista' ); ?>
    <?php get_template_part( 'template-parts/sobre-marca' ); ?>
    <?php get_template_part( 'template-parts/depoimentos' ); ?>
    <?php get_template_part( 'template-parts/faq' ); ?>

</main>

<?php get_footer(); ?>
