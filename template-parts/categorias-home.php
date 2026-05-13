<?php
/**
 * Template part: Categorias — grade fixa de 4 cards
 *
 * @package VivaLeveChild
 */

$categorias = get_terms( array(
    'taxonomy'     => 'product_cat',
    'orderby'      => 'menu_order',
    'order'        => 'ASC',
    'hide_empty'   => false,
    'parent'       => 0,
    'number'       => 4,
    'slug__not_in' => array( 'sem-categoria', 'uncategorized' ),
) );

if ( is_wp_error( $categorias ) || empty( $categorias ) ) {
    return;
}

$url_loja = wc_get_page_permalink( 'shop' );
?>

<section class="vl-categorias-home">
    <div class="vl-categorias-home-inner">

        <div class="vl-secao-header vl-secao-header--com-nav">
            <h2 class="vl-secao-titulo"><?php esc_html_e( 'Explore por categoria', 'viva-leve-child' ); ?></h2>
            <a href="<?php echo esc_url( $url_loja ); ?>" class="vl-ver-todos-link">
                <?php esc_html_e( 'Ver todos', 'viva-leve-child' ); ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </a>
        </div>

        <div class="vl-cat-grid">
            <?php foreach ( $categorias as $cat ) :
                $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
                $img_url      = $thumbnail_id ? wp_get_attachment_image_url( (int) $thumbnail_id, 'medium_large' ) : '';
                $url_cat      = get_term_link( $cat );
            ?>
            <a href="<?php echo esc_url( $url_cat ); ?>"
               class="vl-cat-card<?php echo $img_url ? '' : ' vl-cat-card--sem-imagem'; ?>"
               aria-label="<?php echo esc_attr( sprintf( __( 'Ver produtos de %s', 'viva-leve-child' ), $cat->name ) ); ?>">

                <div class="vl-cat-card-imagem">
                    <?php if ( $img_url ) : ?>
                        <img src="<?php echo esc_url( $img_url ); ?>"
                             alt="<?php echo esc_attr( $cat->name ); ?>"
                             loading="lazy">
                    <?php endif; ?>
                </div>

                <div class="vl-cat-card-overlay"></div>

                <div class="vl-cat-card-info">
                    <span class="vl-cat-card-nome"><?php echo esc_html( $cat->name ); ?></span>
                    <span class="vl-cat-card-cta">
                        <?php esc_html_e( 'Ver produtos', 'viva-leve-child' ); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </span>
                </div>

            </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
