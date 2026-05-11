/**
 * custom.js — Viva Leve Child Theme
 * Scripts personalizados carregados após o carregamento da página.
 * jQuery está disponível via dependência declarada no functions.php.
 */

document.addEventListener( 'DOMContentLoaded', function () {

    // === Global ===
    // Inicializações que afetam todo o site

    // Exemplo: fechar mensagens de aviso do WooCommerce ao clicar
    var avisos = document.querySelectorAll( '.woocommerce-message, .woocommerce-error, .woocommerce-info' );
    avisos.forEach( function ( aviso ) {
        aviso.style.cursor = 'pointer';
        aviso.addEventListener( 'click', function () {
            this.style.display = 'none';
        } );
    } );


    // === Vitrine ===
    // Interações na listagem de produtos (categoria, busca)


    // === Produto ===
    // Interações na página individual do produto


    // === Carrinho ===
    // Melhorias na página do carrinho

} );
