<?php
/**
 * Override: archive-product.php
 * Viva Leve Child Theme
 * Baseado no template original do WooCommerce — personalize abaixo
 *
 * COMO OBTER O ARQUIVO ORIGINAL:
 * Copie de:
 *   ~/Local Sites/viva-leve/app/public/wp-content/plugins/woocommerce/templates/archive-product.php
 * Cole em:
 *   ~/Local Sites/viva-leve/app/public/wp-content/themes/viva-leve-child/woocommerce/archive-product.php
 *
 * Este arquivo controla o layout da vitrine de produtos:
 * páginas de categoria, tag e resultados de busca.
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
 * - WC_Structured_Data::generate_website_data() (prioridade 30)
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description
	 * - woocommerce_taxonomy_archive_description (prioridade 10)
	 * - woocommerce_product_archive_description (prioridade 10)
	 */
	do_action( 'woocommerce_archive_description' );
	?>
</header>

<?php
if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop
	 * - woocommerce_output_all_notices (prioridade 10)
	 * - woocommerce_result_count (prioridade 20)
	 * - woocommerce_catalog_ordering (prioridade 30)
	 */
	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop
	 * - woocommerce_pagination (prioridade 10)
	 */
	do_action( 'woocommerce_after_shop_loop' );

} else {
	/**
	 * Hook: woocommerce_no_products_found
	 * - wc_no_products_found (prioridade 10)
	 */
	do_action( 'woocommerce_no_products_found' );
}

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
