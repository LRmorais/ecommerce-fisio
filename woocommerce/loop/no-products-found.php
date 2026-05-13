<?php
/**
 * Nenhum produto encontrado — Viva Leve Child Theme
 *
 * @package VivaLeveChild
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

$shop_url = wc_get_page_permalink( 'shop' );
?>

<div class="vl-sem-produtos">

    <div class="vl-sem-produtos-icone" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="1.4"
             stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/>
            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            <line x1="8" y1="11" x2="14" y2="11"/>
        </svg>
    </div>

    <h2 class="vl-sem-produtos-titulo">
        <?php esc_html_e( 'Nenhum produto encontrado', 'viva-leve-child' ); ?>
    </h2>

    <p class="vl-sem-produtos-texto">
        <?php esc_html_e( 'Não encontramos produtos que correspondam aos filtros selecionados. Tente ajustar os filtros ou explore toda a nossa loja.', 'viva-leve-child' ); ?>
    </p>

    <a href="<?php echo esc_url( $shop_url ); ?>" class="vl-sem-produtos-btn">
        <?php esc_html_e( 'Ver todos os produtos', 'viva-leve-child' ); ?>
    </a>

</div>
