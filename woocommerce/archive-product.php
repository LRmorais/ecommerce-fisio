<?php
/**
 * Archive de produtos — Viva Leve Child Theme
 * Layout: sidebar de filtros (esquerda) + grade de produtos (direita).
 *
 * @package VivaLeveChild
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );

// Contagem de filtros ativos para o badge mobile
$filtros_ativos = 0;
if ( ! empty( $_GET['min_price'] ) || ! empty( $_GET['max_price'] ) ) $filtros_ativos++;
if ( ! empty( $_GET['min_rating'] ) ) $filtros_ativos++;
if ( ! empty( $_GET['apenas_em_estoque'] ) ) $filtros_ativos++;
?>

<div class="vl-shop-wrap">

    <!-- Botão mobile para abrir filtros -->
    <div class="vl-shop-topbar">
        <button class="vl-filtros-toggle" id="vl-filtros-toggle"
                aria-expanded="false" aria-controls="vl-shop-sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="4" y1="6" x2="20" y2="6"/>
                <line x1="8" y1="12" x2="20" y2="12"/>
                <line x1="12" y1="18" x2="20" y2="18"/>
            </svg>
            <?php esc_html_e( 'Filtrar', 'viva-leve-child' ); ?>
            <?php if ( $filtros_ativos > 0 ) : ?>
                <span class="vl-filtros-badge" aria-label="<?php echo esc_attr( $filtros_ativos ); ?> filtros ativos">
                    <?php echo esc_html( $filtros_ativos ); ?>
                </span>
            <?php endif; ?>
        </button>

        <div class="vl-shop-topbar-resultado">
            <?php woocommerce_result_count(); ?>
        </div>
    </div>

    <!-- Overlay mobile -->
    <div class="vl-filtros-overlay" id="vl-filtros-overlay" aria-hidden="true"></div>

    <div class="vl-shop-layout">

        <!-- ── Sidebar de filtros ── -->
        <aside class="vl-shop-sidebar" id="vl-shop-sidebar"
               aria-label="<?php esc_attr_e( 'Filtros de produtos', 'viva-leve-child' ); ?>">
            <?php get_template_part( 'template-parts/shop-sidebar' ); ?>
        </aside>

        <!-- ── Conteúdo: produtos ── -->
        <div class="vl-shop-conteudo">

            <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                <h1 class="vl-shop-titulo"><?php woocommerce_page_title(); ?></h1>
            <?php endif; ?>
            <?php do_action( 'woocommerce_archive_description' ); ?>

            <?php if ( woocommerce_product_loop() ) : ?>

                <?php woocommerce_output_all_notices(); ?>

                <!-- Toolbar: resultado + ordenação (chamadas diretas — sem hook) -->
                <div class="vl-shop-barra">
                    <span class="vl-shop-barra-resultado">
                        <?php woocommerce_result_count(); ?>
                    </span>
                    <div class="vl-shop-barra-ordem">
                        <?php woocommerce_catalog_ordering(); ?>
                    </div>
                </div>

                <?php woocommerce_product_loop_start(); ?>

                <?php if ( wc_get_loop_prop( 'total' ) ) : ?>
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php do_action( 'woocommerce_shop_loop' ); ?>
                        <?php wc_get_template_part( 'content', 'product' ); ?>
                    <?php endwhile; ?>
                <?php endif; ?>

                <?php woocommerce_product_loop_end(); ?>

                <?php
                // Storefront move catalog_ordering e result_count para after_shop_loop —
                // removemos pois já temos nosso toolbar no topo
                remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
                remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count',    20 );
                do_action( 'woocommerce_after_shop_loop' ); // dispara apenas paginação
                ?>

            <?php else : ?>
                <?php do_action( 'woocommerce_no_products_found' ); ?>
            <?php endif; ?>

        </div><!-- .vl-shop-conteudo -->

    </div><!-- .vl-shop-layout -->

</div><!-- .vl-shop-wrap -->

<?php
do_action( 'woocommerce_after_main_content' );
// Não chama woocommerce_sidebar — sidebar personalizada já está no layout
get_footer( 'shop' );