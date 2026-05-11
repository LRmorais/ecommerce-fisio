<?php
/**
 * Funções do Child Theme — Viva Leve
 *
 * Arquivo principal de funções do child theme.
 * Carrega estilos, scripts e configurações do tema.
 *
 * @package VIvaLeveChild
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

    // Carrega o style.css do child theme (variáveis CSS e overrides de base)
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

    // Carrega o JavaScript personalizado da Viva Leve (interações e melhorias de UX)
    wp_enqueue_script(
        'vivaleve-custom-js',
        get_stylesheet_directory_uri() . '/assets/js/custom.js',
        array( 'jquery' ), // depende do jQuery já incluído pelo WooCommerce
        '1.0.0',
        true // carrega no rodapé para não bloquear a renderização
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
}


/* =========================================================
 * 3. LIMPEZA DO <HEAD>
 * =========================================================
 * Remove metadados desnecessários do <head> para reduzir
 * exposição de informações e deixar o HTML mais limpo.
 * ========================================================= */

// Remove a meta tag "generator" que expõe a versão do WordPress
remove_action( 'wp_head', 'wp_generator' );
