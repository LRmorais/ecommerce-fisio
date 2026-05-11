/**
 * custom.js — Viva Leve Child Theme
 * JavaScript vanilla — sem dependências externas.
 * Todos os comportamentos são adicionados após o carregamento do DOM.
 */

document.addEventListener( 'DOMContentLoaded', function () {

    // =========================================================
    // === MENU HAMBURGUER MOBILE
    // Alterna o dropdown de navegação ao clicar no botão.
    // Fecha automaticamente ao clicar fora do header ou Escape.
    // =========================================================

    var header        = document.querySelector( '.site-header' );
    var btnHamburguer = document.querySelector( '.vl-hamburguer' );
    var nav           = document.getElementById( 'vl-nav' );

    if ( btnHamburguer && nav ) {

        btnHamburguer.addEventListener( 'click', function () {
            var estaAberto = nav.classList.toggle( 'aberto' );
            this.setAttribute( 'aria-expanded', estaAberto ? 'true' : 'false' );
        } );

        document.addEventListener( 'click', function ( evento ) {
            if ( header && ! header.contains( evento.target ) ) {
                nav.classList.remove( 'aberto' );
                btnHamburguer.setAttribute( 'aria-expanded', 'false' );
            }
        } );

        document.addEventListener( 'keydown', function ( evento ) {
            if ( evento.key === 'Escape' && nav.classList.contains( 'aberto' ) ) {
                nav.classList.remove( 'aberto' );
                btnHamburguer.setAttribute( 'aria-expanded', 'false' );
                btnHamburguer.focus();
            }
        } );
    }


    // =========================================================
    // === PADDING-TOP DINÂMICO
    // Mede a altura real do header e aplica como variável CSS
    // --altura-header, usada no padding-top do conteúdo.
    // Roda no carregamento e a cada resize para cobrir mudanças
    // de layout (abertura do nav, redimensionamento de janela).
    // =========================================================

    function ajustarPaddingHeader() {
        if ( ! header ) return;
        document.documentElement.style.setProperty(
            '--altura-header',
            header.offsetHeight + 'px'
        );
    }

    ajustarPaddingHeader();
    window.addEventListener( 'resize', ajustarPaddingHeader, { passive: true } );


    // =========================================================
    // === HEADER COM SOMBRA AO ROLAR
    // =========================================================

    if ( header ) {
        window.addEventListener( 'scroll', function () {
            header.classList.toggle( 'scrolled', window.scrollY > 50 );
        }, { passive: true } );
    }


    // =========================================================
    // === ANIMAÇÃO DE ENTRADA DOS CARDS DE PRODUTO
    // Usa IntersectionObserver para revelar cards ao entrarem
    // na viewport com uma transição suave de opacidade e posição.
    // =========================================================

    var cardsDeEntrada = document.querySelectorAll(
        '.woocommerce ul.products li.product, .woocommerce-page ul.products li.product'
    );

    if ( cardsDeEntrada.length > 0 && 'IntersectionObserver' in window ) {

        var observadorCards = new IntersectionObserver(
            function ( entradas ) {
                entradas.forEach( function ( entrada ) {
                    if ( entrada.isIntersecting ) {
                        entrada.target.classList.add( 'visivel' );
                        // Para de observar após a primeira aparição — sem loop
                        observadorCards.unobserve( entrada.target );
                    }
                } );
            },
            {
                threshold: 0.1,      // dispara quando 10% do card está visível
                rootMargin: '0px 0px -40px 0px' // antecipa levemente a animação
            }
        );

        cardsDeEntrada.forEach( function ( card ) {
            observadorCards.observe( card );
        } );

    } else {
        // Fallback para navegadores sem IntersectionObserver: exibe todos imediatamente
        cardsDeEntrada.forEach( function ( card ) {
            card.classList.add( 'visivel' );
        } );
    }


    // =========================================================
    // === FEEDBACK VISUAL NO BOTÃO "ADICIONAR AO CARRINHO"
    // Ao clicar num botão de adicionar ao carrinho, substitui
    // o texto por "Adicionado ✓" por 2 segundos e aplica
    // a cor de sucesso (definida em custom.css).
    // Funciona para cliques diretos — o AJAX do WooCommerce
    // também dispara eventos próprios que podem ser combinados.
    // =========================================================

    document.addEventListener( 'click', function ( evento ) {
        var btn = evento.target.closest( '.add_to_cart_button, .single_add_to_cart_button' );

        if ( ! btn ) return;

        var textoOriginal = btn.textContent;

        // Evita duplo clique enquanto o feedback está ativo
        if ( btn.classList.contains( 'adicionado' ) ) return;

        btn.classList.add( 'adicionado' );
        btn.textContent = 'Adicionado ✓';
        btn.setAttribute( 'aria-label', 'Produto adicionado ao carrinho' );

        setTimeout( function () {
            btn.classList.remove( 'adicionado' );
            btn.textContent = textoOriginal;
            btn.removeAttribute( 'aria-label' );
        }, 2000 );
    } );

} ); // fim do DOMContentLoaded
