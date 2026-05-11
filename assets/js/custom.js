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


    // =========================================================
    // === SLIDER DE BANNERS
    // Auto-play com transição de opacidade. Dots clicáveis abaixo.
    // Pausa no hover. Suporte a swipe em touch.
    // =========================================================

    var sliderWrap = document.querySelector( '.vl-slider-wrap' );

    if ( sliderWrap ) {
        var slides    = sliderWrap.querySelectorAll( '.vl-slide' );
        var dots      = sliderWrap.querySelectorAll( '.vl-slider-dot' );
        var total     = slides.length;
        var atual     = 0;
        var intervalo = null;
        var DELAY     = 4000;

        if ( total < 2 ) return;

        function irPara( indice ) {
            slides[ atual ].classList.remove( 'vl-slide--ativo' );
            slides[ atual ].setAttribute( 'aria-hidden', 'true' );
            if ( dots[ atual ] ) {
                dots[ atual ].classList.remove( 'vl-slider-dot--ativo' );
                dots[ atual ].setAttribute( 'aria-selected', 'false' );
            }

            atual = ( indice + total ) % total;

            slides[ atual ].classList.add( 'vl-slide--ativo' );
            slides[ atual ].setAttribute( 'aria-hidden', 'false' );
            if ( dots[ atual ] ) {
                dots[ atual ].classList.add( 'vl-slider-dot--ativo' );
                dots[ atual ].setAttribute( 'aria-selected', 'true' );
            }
        }

        function iniciar() { intervalo = setInterval( function () { irPara( atual + 1 ); }, DELAY ); }
        function parar()   { clearInterval( intervalo ); }

        iniciar();

        dots.forEach( function ( dot ) {
            dot.addEventListener( 'click', function () {
                parar();
                irPara( parseInt( this.dataset.indice, 10 ) );
                iniciar();
            } );
        } );

        sliderWrap.addEventListener( 'mouseenter', parar );
        sliderWrap.addEventListener( 'mouseleave', iniciar );

        var touchX = 0;
        sliderWrap.addEventListener( 'touchstart', function ( e ) { touchX = e.touches[0].clientX; }, { passive: true } );
        sliderWrap.addEventListener( 'touchend', function ( e ) {
            var diff = touchX - e.changedTouches[0].clientX;
            if ( Math.abs( diff ) > 50 ) { parar(); irPara( diff > 0 ? atual + 1 : atual - 1 ); iniciar(); }
        }, { passive: true } );
    }


    // =========================================================
    // === FAQ — ACORDEÃO
    // Abre/fecha respostas com animação de max-height.
    // Fecha o item anterior ao abrir um novo.
    // =========================================================

    var faqBotoes = document.querySelectorAll( '.vl-faq-pergunta' );

    function fecharFaq( btn ) {
        var painel = document.getElementById( btn.getAttribute( 'aria-controls' ) );
        if ( ! painel ) return;
        btn.setAttribute( 'aria-expanded', 'false' );
        painel.style.maxHeight = '0';
        painel.classList.remove( 'aberto' );
        painel.addEventListener( 'transitionend', function handler() {
            painel.setAttribute( 'hidden', '' );
            painel.removeEventListener( 'transitionend', handler );
        } );
    }

    function abrirFaq( btn ) {
        var painel = document.getElementById( btn.getAttribute( 'aria-controls' ) );
        if ( ! painel ) return;
        painel.removeAttribute( 'hidden' );
        painel.classList.add( 'aberto' );
        btn.setAttribute( 'aria-expanded', 'true' );
        painel.style.maxHeight = painel.scrollHeight + 'px';
    }

    faqBotoes.forEach( function ( btn ) {
        btn.addEventListener( 'click', function () {
            var estaAberto = this.getAttribute( 'aria-expanded' ) === 'true';

            // Fecha todos os outros
            faqBotoes.forEach( function ( outro ) {
                if ( outro !== btn && outro.getAttribute( 'aria-expanded' ) === 'true' ) {
                    fecharFaq( outro );
                }
            } );

            if ( estaAberto ) {
                fecharFaq( this );
            } else {
                abrirFaq( this );
            }
        } );
    } );


    // =========================================================
    // === BARRA STICKY DE COMPRA — PÁGINA DE PRODUTO
    // Aparece quando o botão principal sai da viewport.
    // O clique na barra dispara o botão original.
    // =========================================================

    var stickyBar = document.getElementById( 'vl-sticky-bar' );
    var btnOriginal = document.querySelector( '.single_add_to_cart_button' );
    var stickyBtn   = document.getElementById( 'vl-sticky-bar-btn' );

    if ( stickyBar && btnOriginal ) {
        var observer = new IntersectionObserver( function( entradas ) {
            var visivel = entradas[0].isIntersecting;
            stickyBar.classList.toggle( 'visivel', ! visivel );
            stickyBar.setAttribute( 'aria-hidden', visivel ? 'true' : 'false' );
        }, { threshold: 0.2 } );

        observer.observe( btnOriginal );

        if ( stickyBtn ) {
            stickyBtn.addEventListener( 'click', function() {
                btnOriginal.click();
            } );
        }
    }

} ); // fim do DOMContentLoaded
