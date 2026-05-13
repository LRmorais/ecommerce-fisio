<?php
/**
 * Template part: Chamada para consulta com a Fisioterapeuta — Página Inicial
 *
 * @package VivaLeveChild
 */

$numero = preg_replace( '/\D/', '', get_theme_mod( 'vl_footer_whatsapp', '' ) );
if ( ! $numero ) return;

$mensagem = rawurlencode( 'Olá! Gostaria de falar com a fisioterapeuta especialista para me ajudar a escolher o produto ideal.' );
$url_wpp  = 'https://wa.me/' . esc_attr( $numero ) . '?text=' . $mensagem;
?>

<section class="vl-consulta-home" aria-labelledby="vl-consulta-titulo">
    <div class="vl-consulta-home-inner">

        <!-- Lado esquerdo: texto e CTA -->
        <div class="vl-consulta-home-texto">

            <span class="vl-consulta-home-tag">
                <?php esc_html_e( 'Diferencial exclusivo', 'viva-leve-child' ); ?>
            </span>

            <h2 class="vl-consulta-home-titulo" id="vl-consulta-titulo">
                <?php esc_html_e( 'Escolha o produto certo com a ajuda de quem entende', 'viva-leve-child' ); ?>
            </h2>

            <p class="vl-consulta-home-desc">
                <?php esc_html_e( 'Cada corpo tem uma necessidade diferente. Nossa Fisioterapeuta especialista analisa seu caso e indica o produto ideal para você — direto no WhatsApp.', 'viva-leve-child' ); ?>
            </p>

            <ul class="vl-consulta-home-beneficios">
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    <?php esc_html_e( 'Orientação personalizada com quem entende', 'viva-leve-child' ); ?>
                </li>
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    <?php esc_html_e( 'Resposta rápida pelo WhatsApp', 'viva-leve-child' ); ?>
                </li>
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    <?php esc_html_e( 'Compra mais assertiva para o seu bem-estar', 'viva-leve-child' ); ?>
                </li>
            </ul>

            <a href="<?php echo esc_url( $url_wpp ); ?>"
               class="vl-consulta-home-btn"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="<?php esc_attr_e( 'Falar com a fisioterapeuta especialista pelo WhatsApp', 'viva-leve-child' ); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                </svg>
                <?php esc_html_e( 'Falar com a especialista agora', 'viva-leve-child' ); ?>
            </a>

        </div><!-- .vl-consulta-home-texto -->

        <!-- Lado direito: card visual da especialista -->
        <div class="vl-consulta-home-visual" aria-hidden="true">

            <div class="vl-consulta-home-card">
                <div class="vl-consulta-home-avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div class="vl-consulta-home-card-info">
                    <strong><?php esc_html_e( 'Fisioterapeuta Especialista', 'viva-leve-child' ); ?></strong>
                    <span><?php esc_html_e( 'Saúde &amp; Bem-estar', 'viva-leve-child' ); ?></span>
                </div>
                <div class="vl-consulta-home-badge">
                    <span class="vl-consulta-home-online"></span>
                    <?php esc_html_e( 'Online agora', 'viva-leve-child' ); ?>
                </div>
            </div>

            <div class="vl-consulta-home-balao">
                <p><?php esc_html_e( '"Posso te ajudar a encontrar o produto ideal para a sua necessidade. É só me chamar!"', 'viva-leve-child' ); ?></p>
            </div>

        </div><!-- .vl-consulta-home-visual -->

    </div><!-- .vl-consulta-home-inner -->
</section>
