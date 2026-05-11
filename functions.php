<?php
/**
 * Funções do Child Theme — Viva Leve
 *
 * Arquivo principal de funções do child theme.
 * Carrega estilos, scripts e configurações do tema.
 *
 * @package VivaLeveChild
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;


/* =========================================================
 * 1. ENQUEUE DE ESTILOS E SCRIPTS
 * =========================================================
 * Carrega o CSS do tema pai (Storefront), o CSS do child theme
 * e os assets personalizados da Viva Leve.
 * Usar wp_enqueue_style() é o método correto — evita @import.
 * ========================================================= */

add_action( 'wp_enqueue_scripts', 'vivaleve_enqueue_assets' );

function vivaleve_enqueue_assets() {

    // Carrega o style.css do tema pai (Storefront) — obrigatório para o child theme funcionar
    wp_enqueue_style(
        'storefront-style',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme( 'storefront' )->get( 'Version' )
    );

    // Carrega o style.css do child theme (variáveis CSS, reset e tipografia base)
    wp_enqueue_style(
        'vivaleve-child-style',
        get_stylesheet_uri(),
        array( 'storefront-style' ),
        wp_get_theme()->get( 'Version' )
    );

    // Carrega o CSS personalizado da Viva Leve (estilos de componentes e páginas)
    wp_enqueue_style(
        'vivaleve-custom-css',
        get_stylesheet_directory_uri() . '/assets/css/custom.css',
        array( 'vivaleve-child-style' ),
        '1.0.0'
    );

    // Carrega o JavaScript personalizado — no rodapé para não bloquear a renderização
    wp_enqueue_script(
        'vivaleve-custom-js',
        get_stylesheet_directory_uri() . '/assets/js/custom.js',
        array(),
        '1.0.0',
        true
    );
}


/* =========================================================
 * 2. SUPORTE A FUNCIONALIDADES DO TEMA
 * =========================================================
 * Declara os recursos do WordPress e WooCommerce que o tema suporta.
 * Deve rodar dentro do hook after_setup_theme para garantir que
 * o WordPress inicializou corretamente.
 * ========================================================= */

add_action( 'after_setup_theme', 'vivaleve_theme_support' );

function vivaleve_theme_support() {

    // Permite que o WordPress gerencie a tag <title> automaticamente
    add_theme_support( 'title-tag' );

    // Habilita imagens destacadas em posts e produtos
    add_theme_support( 'post-thumbnails' );

    // Declara suporte ao WooCommerce (necessário para os estilos funcionarem)
    add_theme_support( 'woocommerce' );

    // Habilita zoom ao passar o mouse sobre a imagem do produto
    add_theme_support( 'wc-product-gallery-zoom' );

    // Habilita lightbox ao clicar na imagem do produto
    add_theme_support( 'wc-product-gallery-lightbox' );

    // Habilita carrossel/slider de imagens na galeria do produto
    add_theme_support( 'wc-product-gallery-slider' );

    // Habilita suporte a breadcrumbs do WooCommerce
    add_theme_support( 'woocommerce', array(
        'breadcrumb_separator' => '&rsaquo;',
    ) );

    // Registra o menu de navegação principal
    register_nav_menus( array(
        'primary' => 'Menu Principal',
    ) );
}


/* =========================================================
 * 3. LOGO PROVISÓRIO EM TEXTO
 * =========================================================
 * Exibe o nome "Viva Leve" estilizado como logo até que
 * um arquivo de imagem seja definido no Personalizador.
 * O hook substitui o comportamento padrão do Storefront
 * somente quando nenhum logo de imagem está configurado.
 * ========================================================= */

/**
 * Gera o HTML do logo provisório em texto.
 */
function vivaleve_logo_texto() {
    return sprintf(
        '<a href="%s" class="viva-leve-logo" aria-label="%s">
            <span class="logo-viva">Viva</span>
            <span class="logo-leve">Leve</span>
        </a>',
        esc_url( home_url( '/' ) ),
        esc_attr__( 'Viva Leve — página inicial', 'viva-leve-child' )
    );
}

/**
 * Substitui o logo padrão do Storefront pelo logo provisório em texto
 * apenas quando o usuário não tiver definido um logo de imagem.
 */
add_filter( 'storefront_site_title_or_logo', 'vivaleve_substituir_logo' );

function vivaleve_substituir_logo( $html ) {
    // Se já há um logo de imagem configurado, mantém o comportamento padrão
    if ( has_custom_logo() ) {
        return $html;
    }

    return vivaleve_logo_texto();
}


/* =========================================================
 * 4. PERSONALIZAÇÃO DE TEXTOS DO WOOCOMMERCE
 * =========================================================
 * Adapta os textos padrão do WooCommerce para a voz da
 * marca Viva Leve — mais próxima e acolhedora.
 * ========================================================= */

/**
 * Troca textos do WooCommerce via filtro de traduções.
 * Funciona interceptando a string original antes de exibir.
 */
add_filter( 'gettext', 'vivaleve_textos_woocommerce', 20, 3 );

function vivaleve_textos_woocommerce( $traduzido, $original, $dominio ) {
    // Aplica apenas nas strings do WooCommerce
    if ( 'woocommerce' !== $dominio ) {
        return $traduzido;
    }

    $substituicoes = array(
        'Add to cart'                    => 'Adicionar ao carrinho 🛒',
        'Proceed to checkout'            => 'Finalizar meu pedido',
        'Your cart is currently empty.'  => 'Seu carrinho ainda está vazio — que tal explorar nossos produtos?',
    );

    if ( isset( $substituicoes[ $original ] ) ) {
        return $substituicoes[ $original ];
    }

    return $traduzido;
}


/* =========================================================
 * 5. LIMPEZA DO <HEAD>
 * =========================================================
 * Remove metadados desnecessários do <head> para reduzir
 * exposição de informações e deixar o HTML mais limpo.
 * ========================================================= */

// Remove a meta tag "generator" que expõe a versão do WordPress
remove_action( 'wp_head', 'wp_generator' );
