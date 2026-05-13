<?php
/**
 * Loop Start — Viva Leve Child Theme
 * Adiciona a classe vl-products-grid ao ul.products para que o CSS
 * do child theme possa targetar o grid sem conflito com as regras
 * float do Storefront (que usam .site-main ul.products.columns-N).
 *
 * @package VivaLeveChild
 * @version 3.3.0
 */

defined( 'ABSPATH' ) || exit;
?>
<ul class="products columns-<?php echo esc_attr( wc_get_loop_prop( 'columns' ) ); ?> vl-products-grid">
