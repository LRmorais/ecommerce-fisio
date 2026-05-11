<?php
/**
 * Rodapé — Viva Leve Child Theme
 *
 * @package VivaLeveChild
 */

// Dados do Customizer
$tagline    = get_theme_mod( 'vl_footer_tagline',   'Saúde e bem-estar para o seu dia a dia.' );
$telefone   = get_theme_mod( 'vl_footer_telefone',  '' );
$email      = get_theme_mod( 'vl_footer_email',     '' );
$endereco   = get_theme_mod( 'vl_footer_endereco',  '' );
$instagram  = get_theme_mod( 'vl_footer_instagram', '' );
$facebook   = get_theme_mod( 'vl_footer_facebook',  '' );
$whatsapp   = get_theme_mod( 'vl_footer_whatsapp',  '' );

// Categorias de produto (nível raiz)
$categorias = get_terms( array(
    'taxonomy'     => 'product_cat',
    'hide_empty'   => false,
    'parent'       => 0,
    'slug__not_in' => array( 'sem-categoria', 'uncategorized' ),
    'orderby'      => 'name',
    'order'        => 'ASC',
) );

// Páginas úteis (exclui páginas funcionais do WooCommerce que têm links próprios)
$excluir_ids = array_filter( array_map( 'intval', array(
    get_option( 'woocommerce_cart_page_id' ),
    get_option( 'woocommerce_checkout_page_id' ),
) ) );

$paginas_uteis = get_pages( array(
    'post_status' => 'publish',
    'exclude'     => $excluir_ids,
    'sort_column' => 'menu_order',
) );
?>

        </div><!-- .col-full -->
    </div><!-- #content -->

    <?php do_action( 'storefront_before_footer' ); ?>

    <footer id="colophon" class="site-footer vl-footer" role="contentinfo">

        <!-- Corpo principal do rodapé: 4 colunas -->
        <div class="vl-footer-corpo">
            <div class="vl-footer-inner">

                <!-- Coluna 1: Marca -->
                <div class="vl-footer-col vl-footer-col--marca">

                    <?php if ( has_custom_logo() ) : ?>
                        <div class="vl-footer-logo"><?php the_custom_logo(); ?></div>
                    <?php else : ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="vl-footer-logo-texto">
                            <span class="logo-viva">Viva</span><span class="logo-leve">Leve</span>
                        </a>
                    <?php endif; ?>

                    <?php if ( $tagline ) : ?>
                        <p class="vl-footer-tagline"><?php echo esc_html( $tagline ); ?></p>
                    <?php endif; ?>

                    <!-- Redes sociais -->
                    <?php if ( $instagram || $facebook || $whatsapp ) : ?>
                    <div class="vl-footer-social">

                        <?php if ( $instagram ) : ?>
                        <a href="<?php echo esc_url( $instagram ); ?>" class="vl-social-link" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                            </svg>
                        </a>
                        <?php endif; ?>

                        <?php if ( $facebook ) : ?>
                        <a href="<?php echo esc_url( $facebook ); ?>" class="vl-social-link" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                            </svg>
                        </a>
                        <?php endif; ?>

                        <?php if ( $whatsapp ) : ?>
                        <a href="https://wa.me/<?php echo esc_attr( preg_replace( '/\D/', '', $whatsapp ) ); ?>" class="vl-social-link" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                            </svg>
                        </a>
                        <?php endif; ?>

                    </div>
                    <?php endif; ?>

                </div><!-- .vl-footer-col--marca -->

                <!-- Coluna 2: Links úteis -->
                <div class="vl-footer-col">
                    <h3 class="vl-footer-col-titulo"><?php esc_html_e( 'Links Úteis', 'viva-leve-child' ); ?></h3>
                    <ul class="vl-footer-links">
                        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Início', 'viva-leve-child' ); ?></a></li>
                        <li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Loja', 'viva-leve-child' ); ?></a></li>
                        <?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
                        <li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'Minha conta', 'viva-leve-child' ); ?></a></li>
                        <li><a href="<?php echo esc_url( wc_get_page_permalink( 'cart' ) ); ?>"><?php esc_html_e( 'Carrinho', 'viva-leve-child' ); ?></a></li>
                        <li><a href="<?php echo esc_url( wc_get_page_permalink( 'checkout' ) ); ?>"><?php esc_html_e( 'Finalizar pedido', 'viva-leve-child' ); ?></a></li>
                        <?php endif; ?>
                        <?php if ( $paginas_uteis ) :
                            $slugs_skip = array( 'shop', 'loja', 'cart', 'carrinho', 'checkout', 'my-account', 'minha-conta' );
                            foreach ( $paginas_uteis as $pag ) :
                                if ( in_array( $pag->post_name, $slugs_skip, true ) ) continue;
                        ?>
                        <li><a href="<?php echo esc_url( get_permalink( $pag->ID ) ); ?>"><?php echo esc_html( $pag->post_title ); ?></a></li>
                        <?php endforeach; endif; ?>
                    </ul>
                </div>

                <!-- Coluna 3: Categorias -->
                <?php if ( ! is_wp_error( $categorias ) && ! empty( $categorias ) ) : ?>
                <div class="vl-footer-col">
                    <h3 class="vl-footer-col-titulo"><?php esc_html_e( 'Categorias', 'viva-leve-child' ); ?></h3>
                    <ul class="vl-footer-links">
                        <?php foreach ( $categorias as $cat ) : ?>
                        <li><a href="<?php echo esc_url( get_term_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Coluna 4: Contato -->
                <?php if ( $telefone || $email || $endereco ) : ?>
                <div class="vl-footer-col">
                    <h3 class="vl-footer-col-titulo"><?php esc_html_e( 'Contato', 'viva-leve-child' ); ?></h3>
                    <ul class="vl-footer-contato">

                        <?php if ( $telefone ) : ?>
                        <li class="vl-footer-contato-item">
                            <span class="vl-footer-contato-icone" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16a2 2 0 0 1 .27.92z"/>
                                </svg>
                            </span>
                            <a href="tel:<?php echo esc_attr( preg_replace( '/\D/', '', $telefone ) ); ?>"><?php echo esc_html( $telefone ); ?></a>
                        </li>
                        <?php endif; ?>

                        <?php if ( $email ) : ?>
                        <li class="vl-footer-contato-item">
                            <span class="vl-footer-contato-icone" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                            </span>
                            <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                        </li>
                        <?php endif; ?>

                        <?php if ( $endereco ) : ?>
                        <li class="vl-footer-contato-item">
                            <span class="vl-footer-contato-icone" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </span>
                            <span><?php echo nl2br( esc_html( $endereco ) ); ?></span>
                        </li>
                        <?php endif; ?>

                    </ul>
                </div>
                <?php endif; ?>

                <!-- Coluna: Formas de pagamento -->
                <div class="vl-footer-col">
                    <h3 class="vl-footer-col-titulo"><?php esc_html_e( 'Formas de Pagamento', 'viva-leve-child' ); ?></h3>
                    <div class="vl-footer-selos" aria-label="<?php esc_attr_e( 'Formas de pagamento aceitas', 'viva-leve-child' ); ?>">
                        <?php
                        $selos = array( 'Pix', 'Cartão de crédito', 'Cartão de débito', 'Boleto' );
                        foreach ( $selos as $selo ) : ?>
                        <span class="vl-footer-selo"><?php echo esc_html( $selo ); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div><!-- .vl-footer-inner -->
        </div><!-- .vl-footer-corpo -->

        <!-- Barra inferior: copyright centralizado -->
        <div class="vl-footer-barra">
            <div class="vl-footer-barra-inner">
                <p class="vl-footer-copy">
                    &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>.
                    <?php esc_html_e( 'Todos os direitos reservados.', 'viva-leve-child' ); ?>
                </p>
            </div>
        </div><!-- .vl-footer-barra -->

    </footer><!-- #colophon -->

    <?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
