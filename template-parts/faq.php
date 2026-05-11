<?php
/**
 * Template part: FAQ rápido — página inicial
 *
 * Acordeão acessível. Título do post = pergunta, conteúdo = resposta.
 * Gerenciado em FAQ no painel. Ordem pelo campo Ordem do post.
 *
 * @package VivaLeveChild
 */

$perguntas = new WP_Query( array(
    'post_type'      => 'vl_faq',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
    'no_found_rows'  => true,
) );

if ( ! $perguntas->have_posts() ) {
    return;
}
?>

<section class="vl-faq">
    <div class="vl-faq-inner">

        <div class="vl-secao-header vl-secao-header--centro">
            <h2 class="vl-secao-titulo"><?php esc_html_e( 'Perguntas Frequentes', 'viva-leve-child' ); ?></h2>
        </div>

        <div class="vl-faq-lista" role="list">
            <?php $i = 0; while ( $perguntas->have_posts() ) : $perguntas->the_post(); $i++; ?>

            <div class="vl-faq-item" role="listitem">
                <button class="vl-faq-pergunta"
                        aria-expanded="false"
                        aria-controls="vl-faq-resposta-<?php echo esc_attr( $i ); ?>"
                        id="vl-faq-btn-<?php echo esc_attr( $i ); ?>">
                    <span><?php the_title(); ?></span>
                    <span class="vl-faq-icone" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </span>
                </button>
                <div class="vl-faq-resposta"
                     id="vl-faq-resposta-<?php echo esc_attr( $i ); ?>"
                     role="region"
                     aria-labelledby="vl-faq-btn-<?php echo esc_attr( $i ); ?>"
                     hidden>
                    <div class="vl-faq-resposta-inner">
                        <?php echo wp_kses_post( get_the_content() ); ?>
                    </div>
                </div>
            </div>

            <?php endwhile; wp_reset_postdata(); ?>
        </div>

    </div>
</section>
