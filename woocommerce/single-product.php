<?php
/**
 * Override: single-product.php
 * Viva Leve Child Theme
 * Baseado no template original do WooCommerce — personalize abaixo
 *
 * COMO OBTER O ARQUIVO ORIGINAL:
 * Copie de:
 *   ~/Local Sites/viva-leve/app/public/wp-content/plugins/woocommerce/templates/single-product.php
 * Cole em:
 *   ~/Local Sites/viva-leve/app/public/wp-content/themes/viva-leve-child/woocommerce/single-product.php
 *
 * Este arquivo controla o layout da página individual do produto:
 * imagem, galeria, título, preço, descrição e botão de compra.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content
 * - woocommerce_output_content_wrapper (prioridade 10)
 * - woocommerce_breadcrumb (prioridade 20)
 */
do_action( 'woocommerce_before_main_content' );

while ( have_posts() ) :
	the_post();

	wc_get_template_part( 'content', 'single-product' );

endwhile;

/**
 * Hook: woocommerce_after_main_content
 * - woocommerce_output_content_wrapper_end (prioridade 10)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar
 * - woocommerce_get_sidebar (prioridade 10)
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
