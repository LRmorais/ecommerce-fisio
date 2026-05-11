<?php
/**
 * Template part: Seção "Sobre a marca" — página inicial
 *
 * Conteúdo configurável em Aparência → Personalizar → Sobre a Marca.
 *
 * @package VivaLeveChild
 */

if ( ! get_theme_mod( 'vl_sobre_exibir', true ) ) {
    return;
}

$titulo    = get_theme_mod( 'vl_sobre_titulo', 'Nossa missão é cuidar de você' );
$texto     = get_theme_mod( 'vl_sobre_texto',  'Nascemos com o propósito de levar saúde, conforto e bem-estar para o seu dia a dia. Cada produto é selecionado com cuidado para garantir qualidade e resultados reais.' );
$btn_texto = get_theme_mod( 'vl_sobre_btn_texto', 'Conheça nossa história' );
$btn_url   = get_theme_mod( 'vl_sobre_btn_url', '' );
$img_id    = get_theme_mod( 'vl_sobre_imagem', 0 );
$img_url   = $img_id ? wp_get_attachment_image_url( (int) $img_id, 'large' ) : '';
$img_alt   = $img_id ? get_post_meta( (int) $img_id, '_wp_attachment_image_alt', true ) : '';
?>

<section class="vl-sobre-marca">
    <div class="vl-sobre-marca-inner">

        <div class="vl-sobre-imagem-wrap">
            <?php if ( $img_url ) : ?>
                <img src="<?php echo esc_url( $img_url ); ?>"
                     alt="<?php echo esc_attr( $img_alt ?: $titulo ); ?>"
                     class="vl-sobre-imagem"
                     loading="lazy">
            <?php else : ?>
                <div class="vl-sobre-imagem-placeholder" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    <p><?php esc_html_e( 'Adicione uma imagem em Aparência → Personalizar → Sobre a Marca', 'viva-leve-child' ); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <div class="vl-sobre-conteudo">

            <div class="vl-sobre-badge"><?php esc_html_e( 'Nossa história', 'viva-leve-child' ); ?></div>

            <h2 class="vl-sobre-titulo"><?php echo esc_html( $titulo ); ?></h2>

            <p class="vl-sobre-texto"><?php echo nl2br( esc_html( $texto ) ); ?></p>

            <ul class="vl-sobre-valores" aria-label="Nossos valores">
                <li>
                    <span class="vl-sobre-valores-icone" aria-hidden="true">✓</span>
                    <?php esc_html_e( 'Produtos selecionados com rigor', 'viva-leve-child' ); ?>
                </li>
                <li>
                    <span class="vl-sobre-valores-icone" aria-hidden="true">✓</span>
                    <?php esc_html_e( 'Foco no bem-estar do cliente', 'viva-leve-child' ); ?>
                </li>
                <li>
                    <span class="vl-sobre-valores-icone" aria-hidden="true">✓</span>
                    <?php esc_html_e( 'Atendimento humano e especializado', 'viva-leve-child' ); ?>
                </li>
            </ul>

            <?php if ( $btn_texto && $btn_url ) : ?>
                <a href="<?php echo esc_url( $btn_url ); ?>" class="vl-sobre-btn button">
                    <?php echo esc_html( $btn_texto ); ?>
                </a>
            <?php endif; ?>

        </div>

    </div>
</section>
