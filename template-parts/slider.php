<?php
/**
 * Template part: Slider de banners — apenas imagens
 *
 * @package VivaLeveChild
 */

$slides = new WP_Query( array(
    'post_type'      => 'vl_slide',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'no_found_rows'  => true,
) );

if ( ! $slides->have_posts() ) {
    return;
}

$lista = $slides->posts;
$total = count( $lista );
?>

<div class="vl-slider-wrap">

    <div class="vl-slider" aria-label="<?php esc_attr_e( 'Banners', 'viva-leve-child' ); ?>">
        <?php foreach ( $lista as $i => $slide ) :
            $img_id  = get_post_thumbnail_id( $slide->ID );
            $img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'full' ) : '';
            if ( ! $img_url ) continue;
        ?>
            <div class="vl-slide<?php echo $i === 0 ? ' vl-slide--ativo' : ''; ?>"
                 aria-hidden="<?php echo $i === 0 ? 'false' : 'true'; ?>"
                 style="background-image: url('<?php echo esc_url( $img_url ); ?>')">
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ( $total > 1 ) : ?>
    <div class="vl-slider-dots" role="tablist">
        <?php for ( $i = 0; $i < $total; $i++ ) : ?>
            <button class="vl-slider-dot<?php echo $i === 0 ? ' vl-slider-dot--ativo' : ''; ?>"
                    role="tab"
                    aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                    aria-label="<?php echo esc_attr( sprintf( 'Slide %d', $i + 1 ) ); ?>"
                    data-indice="<?php echo esc_attr( $i ); ?>">
            </button>
        <?php endfor; ?>
    </div>
    <?php endif; ?>

</div><!-- .vl-slider-wrap -->
