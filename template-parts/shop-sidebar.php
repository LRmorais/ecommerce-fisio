<?php
/**
 * Template part: Sidebar de filtros — Vitrine de produtos
 *
 * @package VivaLeveChild
 */

// URL base do formulário (sem params de filtro)
if ( is_product_category() ) {
    $form_action = get_term_link( get_queried_object() );
} elseif ( is_product_tag() ) {
    $form_action = get_term_link( get_queried_object() );
} else {
    $form_action = wc_get_page_permalink( 'shop' );
}

// Valores atuais dos filtros
$filtro_min     = isset( $_GET['min_price'] )       ? (float) wc_clean( $_GET['min_price'] ) : '';
$filtro_max     = isset( $_GET['max_price'] )       ? (float) wc_clean( $_GET['max_price'] ) : '';
$filtro_rating  = isset( $_GET['min_rating'] )      ? (int) $_GET['min_rating']              : 0;
$filtro_estoque = ! empty( $_GET['apenas_em_estoque'] );
$orderby_atual  = isset( $_GET['orderby'] )         ? sanitize_text_field( $_GET['orderby'] ) : get_option( 'woocommerce_default_catalog_orderby', 'menu_order' );

// Faixa global de preços (todos os produtos publicados)
global $wpdb;
$precos = $wpdb->get_row(
    "SELECT MIN(CAST(meta_value AS DECIMAL(10,2))) AS min_p,
            MAX(CAST(meta_value AS DECIMAL(10,2))) AS max_p
     FROM {$wpdb->postmeta} pm
     JOIN {$wpdb->posts} p ON p.ID = pm.post_id
     WHERE pm.meta_key = '_price'
       AND pm.meta_value != ''
       AND p.post_status = 'publish'
       AND p.post_type  = 'product'"
);
$preco_min_global = $precos ? (int) floor( $precos->min_p ) : 0;
$preco_max_global = $precos ? (int) ceil( $precos->max_p )  : 500;

// Categorias de produto (nível raiz)
$categorias_raiz = get_terms( array(
    'taxonomy'   => 'product_cat',
    'parent'     => 0,
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
    'slug__not_in' => array( 'sem-categoria', 'uncategorized' ),
) );

// Categoria atual (se em página de categoria)
$cat_atual = is_product_category() ? get_queried_object() : null;

// Verifica se há algum filtro ativo
$tem_filtro = $filtro_min !== '' || $filtro_max !== '' || $filtro_rating || $filtro_estoque;

// URL para limpar todos os filtros
$url_limpar = $form_action;
?>

<div class="vl-filtros">

    <!-- Cabeçalho da sidebar -->
    <div class="vl-filtros-cabecalho">
        <h2 class="vl-filtros-titulo">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
            </svg>
            <?php esc_html_e( 'Filtros', 'viva-leve-child' ); ?>
        </h2>
        <button class="vl-filtros-fechar" id="vl-filtros-fechar" aria-label="<?php esc_attr_e( 'Fechar filtros', 'viva-leve-child' ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.5"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>

    <!-- Filtros ativos -->
    <?php if ( $tem_filtro ) : ?>
    <div class="vl-filtros-ativos">
        <span class="vl-filtros-ativos-label"><?php esc_html_e( 'Filtros ativos:', 'viva-leve-child' ); ?></span>
        <div class="vl-filtros-ativos-tags">
            <?php if ( $filtro_min !== '' || $filtro_max !== '' ) : ?>
                <?php
                $label_preco = '';
                if ( $filtro_min !== '' && $filtro_max !== '' ) {
                    $label_preco = 'R$ ' . number_format( $filtro_min, 0, ',', '.' ) . ' – R$ ' . number_format( $filtro_max, 0, ',', '.' );
                } elseif ( $filtro_min !== '' ) {
                    $label_preco = 'A partir de R$ ' . number_format( $filtro_min, 0, ',', '.' );
                } else {
                    $label_preco = 'Até R$ ' . number_format( $filtro_max, 0, ',', '.' );
                }
                $url_sem_preco = remove_query_arg( array( 'min_price', 'max_price' ), $form_action );
                ?>
                <a href="<?php echo esc_url( $url_sem_preco ); ?>" class="vl-filtro-tag">
                    <?php echo esc_html( $label_preco ); ?> &times;
                </a>
            <?php endif; ?>

            <?php if ( $filtro_rating ) : ?>
                <?php $url_sem_rating = remove_query_arg( 'min_rating', $form_action ); ?>
                <a href="<?php echo esc_url( $url_sem_rating ); ?>" class="vl-filtro-tag">
                    <?php echo esc_html( $filtro_rating ); ?>★<?php esc_html_e( ' ou mais', 'viva-leve-child' ); ?> &times;
                </a>
            <?php endif; ?>

            <?php if ( $filtro_estoque ) : ?>
                <?php $url_sem_estoque = remove_query_arg( 'apenas_em_estoque', $form_action ); ?>
                <a href="<?php echo esc_url( $url_sem_estoque ); ?>" class="vl-filtro-tag">
                    <?php esc_html_e( 'Em estoque', 'viva-leve-child' ); ?> &times;
                </a>
            <?php endif; ?>
        </div>
        <a href="<?php echo esc_url( $url_limpar ); ?>" class="vl-filtros-limpar-todos">
            <?php esc_html_e( 'Limpar tudo', 'viva-leve-child' ); ?>
        </a>
    </div>
    <?php endif; ?>

    <!-- ── Formulário de filtros ── -->
    <form method="GET" action="<?php echo esc_url( $form_action ); ?>" class="vl-filtros-form">

        <!-- Preserva orderby atual via hidden input -->
        <?php if ( $orderby_atual && $orderby_atual !== get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) ) : ?>
            <input type="hidden" name="orderby" value="<?php echo esc_attr( $orderby_atual ); ?>">
        <?php endif; ?>

        <!-- ── Categorias ── -->
        <?php if ( ! empty( $categorias_raiz ) && ! is_wp_error( $categorias_raiz ) ) : ?>
        <div class="vl-filtro-secao">
            <button type="button" class="vl-filtro-secao-titulo vl-filtro-secao-toggle" aria-expanded="true">
                <?php esc_html_e( 'Categorias', 'viva-leve-child' ); ?>
                <svg class="vl-filtro-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="18 15 12 9 6 15"/>
                </svg>
            </button>
            <ul class="vl-filtro-categorias vl-filtro-secao-corpo">
                <!-- Link "Todos" quando dentro de uma categoria -->
                <?php if ( $cat_atual ) : ?>
                <li class="vl-filtro-cat-item">
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
                       class="vl-filtro-cat-link">
                        <?php esc_html_e( '← Todas as categorias', 'viva-leve-child' ); ?>
                    </a>
                </li>
                <?php endif; ?>

                <?php foreach ( $categorias_raiz as $cat ) :
                    $ativa   = $cat_atual && ( $cat_atual->term_id === $cat->term_id || is_child_term( $cat_atual->term_id, $cat->term_id ) );
                    $filhas  = get_terms( array(
                        'taxonomy'   => 'product_cat',
                        'parent'     => $cat->term_id,
                        'hide_empty' => true,
                    ) );
                ?>
                <li class="vl-filtro-cat-item <?php echo $ativa ? 'vl-filtro-cat-item--ativa' : ''; ?>">
                    <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>"
                       class="vl-filtro-cat-link"
                       <?php echo $ativa ? 'aria-current="page"' : ''; ?>>
                        <?php echo esc_html( $cat->name ); ?>
                        <span class="vl-filtro-cat-count">(<?php echo esc_html( $cat->count ); ?>)</span>
                    </a>

                    <?php if ( ! empty( $filhas ) && ! is_wp_error( $filhas ) ) : ?>
                    <ul class="vl-filtro-subcategorias">
                        <?php foreach ( $filhas as $filha ) :
                            $filha_ativa = $cat_atual && $cat_atual->term_id === $filha->term_id;
                        ?>
                        <li>
                            <a href="<?php echo esc_url( get_term_link( $filha ) ); ?>"
                               class="vl-filtro-cat-link vl-filtro-cat-link--filha"
                               <?php echo $filha_ativa ? 'aria-current="page"' : ''; ?>>
                                <?php echo esc_html( $filha->name ); ?>
                                <span class="vl-filtro-cat-count">(<?php echo esc_html( $filha->count ); ?>)</span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- ── Faixa de preço ── -->
        <div class="vl-filtro-secao">
            <button type="button" class="vl-filtro-secao-titulo vl-filtro-secao-toggle" aria-expanded="true">
                <?php esc_html_e( 'Faixa de preço', 'viva-leve-child' ); ?>
                <svg class="vl-filtro-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="18 15 12 9 6 15"/>
                </svg>
            </button>
            <div class="vl-filtro-secao-corpo">
                <div class="vl-preco-slider-wrap"
                     data-min="<?php echo esc_attr( $preco_min_global ); ?>"
                     data-max="<?php echo esc_attr( $preco_max_global ); ?>">
                    <div class="vl-preco-track">
                        <div class="vl-preco-track-fill" id="vl-preco-track-fill"></div>
                    </div>
                    <input type="range" class="vl-preco-range vl-preco-range--min"
                           id="vl-preco-min-range"
                           min="<?php echo esc_attr( $preco_min_global ); ?>"
                           max="<?php echo esc_attr( $preco_max_global ); ?>"
                           value="<?php echo esc_attr( $filtro_min !== '' ? $filtro_min : $preco_min_global ); ?>"
                           aria-label="<?php esc_attr_e( 'Preço mínimo', 'viva-leve-child' ); ?>">
                    <input type="range" class="vl-preco-range vl-preco-range--max"
                           id="vl-preco-max-range"
                           min="<?php echo esc_attr( $preco_min_global ); ?>"
                           max="<?php echo esc_attr( $preco_max_global ); ?>"
                           value="<?php echo esc_attr( $filtro_max !== '' ? $filtro_max : $preco_max_global ); ?>"
                           aria-label="<?php esc_attr_e( 'Preço máximo', 'viva-leve-child' ); ?>">
                </div>
                <div class="vl-preco-inputs">
                    <div class="vl-preco-campo">
                        <label for="vl-min-price"><?php esc_html_e( 'Mín.', 'viva-leve-child' ); ?></label>
                        <div class="vl-preco-input-wrap">
                            <span>R$</span>
                            <input type="number" id="vl-min-price" name="min_price"
                                   min="<?php echo esc_attr( $preco_min_global ); ?>"
                                   max="<?php echo esc_attr( $preco_max_global ); ?>"
                                   value="<?php echo esc_attr( $filtro_min !== '' ? $filtro_min : $preco_min_global ); ?>"
                                   step="1">
                        </div>
                    </div>
                    <span class="vl-preco-separador">—</span>
                    <div class="vl-preco-campo">
                        <label for="vl-max-price"><?php esc_html_e( 'Máx.', 'viva-leve-child' ); ?></label>
                        <div class="vl-preco-input-wrap">
                            <span>R$</span>
                            <input type="number" id="vl-max-price" name="max_price"
                                   min="<?php echo esc_attr( $preco_min_global ); ?>"
                                   max="<?php echo esc_attr( $preco_max_global ); ?>"
                                   value="<?php echo esc_attr( $filtro_max !== '' ? $filtro_max : $preco_max_global ); ?>"
                                   step="1">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Avaliação mínima ── -->
        <div class="vl-filtro-secao">
            <button type="button" class="vl-filtro-secao-titulo vl-filtro-secao-toggle" aria-expanded="true">
                <?php esc_html_e( 'Avaliação', 'viva-leve-child' ); ?>
                <svg class="vl-filtro-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="18 15 12 9 6 15"/>
                </svg>
            </button>
            <div class="vl-filtro-secao-corpo">
                <ul class="vl-filtro-rating">
                    <?php
                    $opcoes_rating = array(
                        0 => __( 'Qualquer avaliação', 'viva-leve-child' ),
                        4 => '4★ ' . __( 'ou mais', 'viva-leve-child' ),
                        3 => '3★ ' . __( 'ou mais', 'viva-leve-child' ),
                        2 => '2★ ' . __( 'ou mais', 'viva-leve-child' ),
                    );
                    foreach ( $opcoes_rating as $valor => $label ) : ?>
                    <li>
                        <label class="vl-filtro-radio <?php echo $filtro_rating === $valor ? 'vl-filtro-radio--ativa' : ''; ?>">
                            <input type="radio" name="min_rating"
                                   value="<?php echo esc_attr( $valor ); ?>"
                                   <?php checked( $filtro_rating, $valor ); ?>>
                            <?php if ( $valor > 0 ) : ?>
                                <span class="vl-stars" aria-hidden="true">
                                    <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                        <span class="<?php echo $i <= $valor ? 'vl-star--cheia' : 'vl-star--vazia'; ?>">★</span>
                                    <?php endfor; ?>
                                </span>
                            <?php endif; ?>
                            <span><?php echo esc_html( $label ); ?></span>
                        </label>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- ── Disponibilidade ── -->
        <div class="vl-filtro-secao">
            <button type="button" class="vl-filtro-secao-titulo vl-filtro-secao-toggle" aria-expanded="true">
                <?php esc_html_e( 'Disponibilidade', 'viva-leve-child' ); ?>
                <svg class="vl-filtro-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="18 15 12 9 6 15"/>
                </svg>
            </button>
            <div class="vl-filtro-secao-corpo">
                <label class="vl-filtro-checkbox">
                    <input type="checkbox" name="apenas_em_estoque" value="1"
                           <?php checked( $filtro_estoque ); ?>>
                    <span class="vl-filtro-checkbox-mark"></span>
                    <?php esc_html_e( 'Apenas em estoque', 'viva-leve-child' ); ?>
                </label>
            </div>
        </div>

        <!-- ── Ações ── -->
        <div class="vl-filtros-acoes">
            <button type="submit" class="vl-filtros-aplicar button">
                <?php esc_html_e( 'Aplicar filtros', 'viva-leve-child' ); ?>
            </button>
            <?php if ( $tem_filtro ) : ?>
            <a href="<?php echo esc_url( $url_limpar ); ?>" class="vl-filtros-limpar">
                <?php esc_html_e( 'Limpar filtros', 'viva-leve-child' ); ?>
            </a>
            <?php endif; ?>
        </div>

    </form><!-- .vl-filtros-form -->

</div><!-- .vl-filtros -->