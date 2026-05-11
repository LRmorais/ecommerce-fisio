<?php
/**
 * Template do cabeçalho — Viva Leve Child Theme
 *
 * Substitui o header.php do Storefront com layout próprio:
 * logo, barra de busca, ícone de conta e ícone de carrinho.
 *
 * @package VivaLeveChild
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php do_action( 'storefront_before_site' ); ?>

<div id="page" class="hfeed site">
    <?php do_action( 'storefront_before_header' ); ?>

    <header id="masthead" class="site-header" role="banner">

        <!-- Barra principal: logo + busca + ações -->
        <div class="vl-header-inner">

            <!-- Logo -->
            <div class="vl-logo">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                       class="viva-leve-logo"
                       aria-label="<?php esc_attr_e( 'Viva Leve — página inicial', 'viva-leve-child' ); ?>">
                        <span class="logo-viva">Viva</span>
                        <span class="logo-leve">Leve</span>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Barra de busca (visível apenas no desktop) -->
            <?php if ( function_exists( 'get_product_search_form' ) ) : ?>
            <div class="vl-busca">
                <?php get_product_search_form(); ?>
            </div>
            <?php endif; ?>

            <!-- Ações: atendimento + conta + carrinho + hamburguer (mobile) -->
            <div class="vl-acoes">

                <!-- Atendimento -->
                <a href="/contato"
                   class="vl-icone vl-icone-atendimento"
                   aria-label="<?php esc_attr_e( 'Atendimento', 'viva-leve-child' ); ?>">
                    <span class="vl-icone-svg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.75"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true" focusable="false">
                            <path d="M3 18v-6a9 9 0 0 1 18 0v6"/>
                            <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z"/>
                            <path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/>
                        </svg>
                    </span>
                    <span class="vl-icone-label"><?php esc_html_e( 'Atendimento', 'viva-leve-child' ); ?></span>
                </a>

                <!-- Minha conta -->
                <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>"
                   class="vl-icone vl-icone-conta"
                   aria-label="<?php esc_attr_e( 'Minha conta', 'viva-leve-child' ); ?>">
                    <span class="vl-icone-svg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.75"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true" focusable="false">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </span>
                    <span class="vl-icone-label"><?php esc_html_e( 'Minha conta', 'viva-leve-child' ); ?></span>
                </a>

                <!-- Carrinho -->
                <?php
                $contagem = ( function_exists( 'WC' ) && WC()->cart )
                    ? WC()->cart->get_cart_contents_count()
                    : 0;
                ?>
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>"
                   class="vl-icone vl-icone-carrinho"
                   aria-label="<?php esc_attr_e( 'Carrinho', 'viva-leve-child' ); ?>">
                    <span class="vl-icone-svg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.75"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true" focusable="false">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                        <span class="vl-carrinho-contagem"><?php echo $contagem > 0 ? esc_html( $contagem ) : ''; ?></span>
                    </span>
                    <span class="vl-icone-label"><?php esc_html_e( 'Carrinho', 'viva-leve-child' ); ?></span>
                </a>

                <!-- Botão hamburguer (mobile) -->
                <button class="vl-hamburguer"
                        aria-expanded="false"
                        aria-controls="vl-nav"
                        aria-label="<?php esc_attr_e( 'Abrir menu', 'viva-leve-child' ); ?>">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

            </div><!-- .vl-acoes -->
        </div><!-- .vl-header-inner -->

        <!-- Barra de navegação: sempre visível no desktop, dropdown no mobile -->
        <nav id="vl-nav"
             class="vl-nav"
             aria-label="<?php esc_attr_e( 'Menu principal', 'viva-leve-child' ); ?>">

            <!-- Busca mobile (aparece no dropdown, escondida no desktop) -->
            <?php if ( function_exists( 'get_product_search_form' ) ) : ?>
            <div class="vl-nav-busca">
                <?php get_product_search_form(); ?>
            </div>
            <?php endif; ?>

            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'vl-menu',
                'fallback_cb'    => 'vivaleve_menu_fallback',
            ) );
            ?>
        </nav><!-- #vl-nav -->

    </header><!-- #masthead -->

    <?php do_action( 'storefront_before_content' ); ?>

    <div id="content" class="site-content" tabindex="-1">
        <div class="col-full">
        <?php do_action( 'storefront_content_top' ); ?>
