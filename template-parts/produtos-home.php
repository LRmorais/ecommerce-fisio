<?php
/**
 * Template part: Grade de produtos na página inicial
 *
 * Exibe os produtos mais recentes usando o loop nativo do WooCommerce,
 * garantindo compatibilidade com variações, galerias e add-to-cart AJAX.
 *
 * @package VivaLeveChild
 */

if ( ! function_exists( 'wc_get_products' ) ) {
    return;
}

$produtos = wc_get_products( array(
    'status'  => 'publish',
    'limit'   => 8,
    'orderby' => 'date',
    'order'   => 'DESC',
) );

if ( empty( $produtos ) ) {
    return;
}

$url_loja = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/loja/' );
?>

<section class="vl-produtos-home">
    <div class="vl-produtos-home-inner">

        <div class="vl-secao-header">
            <h2 class="vl-secao-titulo"><?php esc_html_e( 'Nossos Produtos', 'viva-leve-child' ); ?></h2>
            <a href="<?php echo esc_url( $url_loja ); ?>" class="vl-ver-todos">
                <?php esc_html_e( 'Ver todos', 'viva-leve-child' ); ?> &rarr;
            </a>
        </div>

        <?php woocommerce_product_loop_start(); ?>

        <?php foreach ( $produtos as $produto ) :
            $GLOBALS['product'] = $produto;
            $post = get_post( $produto->get_id() ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
            setup_postdata( $post );
            wc_get_template_part( 'content', 'product' );
        endforeach;
        wp_reset_postdata(); ?>

        <?php woocommerce_product_loop_end(); ?>

    </div>
</section>
