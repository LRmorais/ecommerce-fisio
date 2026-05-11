<?php
/**
 * Template part: Depoimentos de clientes — página inicial
 *
 * @package VivaLeveChild
 */

$depoimentos = new WP_Query( array(
    'post_type'      => 'vl_depoimento',
    'post_status'    => 'publish',
    'posts_per_page' => 6,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
    'no_found_rows'  => true,
) );

if ( ! $depoimentos->have_posts() ) {
    return;
}
?>

<section class="vl-depoimentos">
    <div class="vl-depoimentos-inner">

        <div class="vl-secao-header vl-secao-header--centro">
            <h2 class="vl-secao-titulo"><?php esc_html_e( 'O que nossos clientes dizem', 'viva-leve-child' ); ?></h2>
        </div>

        <div class="vl-depoimentos-grade">
            <?php while ( $depoimentos->have_posts() ) : $depoimentos->the_post();
                $avaliacao = (int) get_post_meta( get_the_ID(), '_vl_depoimento_avaliacao', true );
                if ( $avaliacao < 1 ) $avaliacao = 5;
                $cargo     = get_post_meta( get_the_ID(), '_vl_depoimento_cargo', true );
                $tem_foto  = has_post_thumbnail();
            ?>
            <article class="vl-depoimento-card">

                <div class="vl-depoimento-estrelas" aria-label="Avaliação: <?php echo esc_attr( $avaliacao ); ?> de 5 estrelas">
                    <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                        <span class="vl-estrela<?php echo $i <= $avaliacao ? ' vl-estrela--cheia' : ''; ?>" aria-hidden="true">★</span>
                    <?php endfor; ?>
                </div>

                <blockquote class="vl-depoimento-texto">
                    <?php echo wp_kses_post( get_the_content() ); ?>
                </blockquote>

                <footer class="vl-depoimento-autor">
                    <?php if ( $tem_foto ) : ?>
                        <div class="vl-depoimento-foto">
                            <?php the_post_thumbnail( array( 56, 56 ), array( 'alt' => get_the_title() ) ); ?>
                        </div>
                    <?php else : ?>
                        <div class="vl-depoimento-foto vl-depoimento-foto--inicial">
                            <span><?php echo esc_html( mb_substr( get_the_title(), 0, 1 ) ); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="vl-depoimento-autor-info">
                        <strong class="vl-depoimento-nome"><?php the_title(); ?></strong>
                        <?php if ( $cargo ) : ?>
                            <span class="vl-depoimento-cargo"><?php echo esc_html( $cargo ); ?></span>
                        <?php endif; ?>
                    </div>
                </footer>

            </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

    </div>
</section>
