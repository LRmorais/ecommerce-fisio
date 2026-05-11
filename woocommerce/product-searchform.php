<?php
/**
 * Formulário de busca de produtos — override do template WooCommerce
 *
 * Sobrescreve woocommerce/templates/product-searchform.php para
 * garantir textos em português independentemente de arquivos .mo.
 *
 * @package VivaLeveChild
 * @version 7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label class="screen-reader-text" for="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>"><?php esc_html_e( 'Buscar:', 'viva-leve-child' ); ?></label>
    <input
        type="search"
        id="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>"
        class="search-field"
        placeholder="<?php esc_attr_e( 'Buscar produtos…', 'viva-leve-child' ); ?>"
        value="<?php echo get_search_query(); ?>"
        name="s"
    />
    <button type="submit" value="<?php esc_attr_e( 'Buscar', 'viva-leve-child' ); ?>" class="<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ); ?>">
        <?php esc_html_e( 'Buscar', 'viva-leve-child' ); ?>
    </button>
    <input type="hidden" name="post_type" value="product" />
</form>
