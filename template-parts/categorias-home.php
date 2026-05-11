<?php
/**
 * Template part: Categorias visuais — página inicial
 *
 * Exibe as categorias pai de produto em cards com imagem de fundo.
 * A imagem é configurada em Produtos → Categorias → editar → imagem.
 * Sem imagem, o card usa um fundo com a cor primária do tema.
 *
 * @package VivaLeveChild
 */

$categorias = get_terms( array(
    'taxonomy'     => 'product_cat',
    'orderby'      => 'menu_order',
    'order'        => 'ASC',
    'hide_empty'   => false,
    'parent'       => 0,
    'slug__not_in' => array( 'sem-categoria', 'uncategorized' ),
) );

if ( is_wp_error( $categorias ) || empty( $categorias ) ) {
    return;
}
?>

<section class="vl-categorias-home">
    <div class="vl-categorias-home-inner">

        <div class="vl-secao-header">
            <h2 class="vl-secao-titulo"><?php esc_html_e( 'Categorias', 'viva-leve-child' ); ?></h2>
        </div>

        <div class="vl-categorias-grid" data-total="<?php echo esc_attr( count( $categorias ) ); ?>">
            <?php foreach ( $categorias as $cat ) :
                $thumbnail_id  = get_term_meta( $cat->term_id, 'thumbnail_id', true );
                $img_url       = $thumbnail_id ? wp_get_attachment_image_url( (int) $thumbnail_id, 'medium_large' ) : '';
                $url_categoria = get_term_link( $cat );
            ?>
            <a href="<?php echo esc_url( $url_categoria ); ?>"
               class="vl-categoria-card<?php echo $img_url ? '' : ' vl-categoria-card--sem-imagem'; ?>"
               <?php if ( $img_url ) : ?>
               style="background-image: url('<?php echo esc_url( $img_url ); ?>')"
               <?php endif; ?>>

                <div class="vl-categoria-card-overlay"></div>

                <div class="vl-categoria-card-info">
                    <span class="vl-categoria-card-nome"><?php echo esc_html( $cat->name ); ?></span>
                    <span class="vl-categoria-card-cta"><?php esc_html_e( 'Ver produtos', 'viva-leve-child' ); ?></span>
                </div>

            </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
