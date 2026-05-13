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
 * 2. FRAGMENTO DE CARRINHO (AJAX)
 * =========================================================
 * Atualiza o badge de contagem do carrinho no header
 * via AJAX sempre que um produto é adicionado/removido.
 * O seletor do fragmento deve bater com o HTML do header.php.
 * ========================================================= */

add_filter( 'woocommerce_add_to_cart_fragments', 'vivaleve_fragmento_carrinho' );

function vivaleve_fragmento_carrinho( $fragmentos ) {
    $contagem = ( function_exists( 'WC' ) && WC()->cart )
        ? WC()->cart->get_cart_contents_count()
        : 0;

    $fragmentos['span.vl-carrinho-contagem'] =
        '<span class="vl-carrinho-contagem">' .
        ( $contagem > 0 ? esc_html( $contagem ) : '' ) .
        '</span>';

    return $fragmentos;
}

/**
 * Fallback de navegação quando nenhum menu está atribuído à localização 'primary'.
 * Exibe "Início" + todas as categorias de produto cadastradas (exceto "Sem categoria").
 */
function vivaleve_menu_fallback() {
    $slugs_excluir = array( 'sem-categoria', 'uncategorized' );

    $pais = get_terms( array(
        'taxonomy'   => 'product_cat',
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,
        'parent'     => 0,
        'slug__not_in' => $slugs_excluir,
    ) );

    echo '<ul class="vl-menu">';
    echo '<li class="menu-item"><a href="' . esc_url( home_url( '/' ) ) . '">'
        . esc_html__( 'Início', 'viva-leve-child' ) . '</a></li>';

    if ( ! is_wp_error( $pais ) && ! empty( $pais ) ) {
        foreach ( $pais as $cat ) {
            $filhos = get_terms( array(
                'taxonomy'     => 'product_cat',
                'orderby'      => 'name',
                'order'        => 'ASC',
                'hide_empty'   => false,
                'parent'       => $cat->term_id,
                'slug__not_in' => $slugs_excluir,
            ) );

            $tem_filhos = ! is_wp_error( $filhos ) && ! empty( $filhos );

            echo '<li class="menu-item' . ( $tem_filhos ? ' menu-item-has-children' : '' ) . '">';
            echo '<a href="' . esc_url( get_term_link( $cat ) ) . '">'
                . esc_html( $cat->name ) . '</a>';

            if ( $tem_filhos ) {
                echo '<ul class="sub-menu">';
                foreach ( $filhos as $filho ) {
                    echo '<li class="menu-item"><a href="' . esc_url( get_term_link( $filho ) ) . '">'
                        . esc_html( $filho->name ) . '</a></li>';
                }
                echo '</ul>';
            }

            echo '</li>';
        }
    }

    echo '</ul>';
}


/* =========================================================
 * 3. SUPORTE A FUNCIONALIDADES DO TEMA
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
 * 4. TRADUÇÃO COMPLETA DO WOOCOMMERCE PARA PT-BR
 * =========================================================
 * Intercepta todas as strings do WooCommerce antes de exibir
 * e substitui pela versão em português, adaptada à voz da
 * marca Viva Leve — próxima, acolhedora e clara.
 * Cobre: carrinho, checkout, produto, vitrine, conta e avisos.
 * ========================================================= */

add_filter( 'gettext', 'vivaleve_traduzir_woocommerce', 20, 3 );

function vivaleve_traduzir_woocommerce( $traduzido, $original, $dominio ) {
    if ( 'woocommerce' !== $dominio ) {
        return $traduzido;
    }

    $traducoes = array(

        // --- Carrinho ---
        'Cart'                                => 'Carrinho',
        'Cart totals'                         => 'Totais do carrinho',
        'Remove item'                         => 'Remover item',
        'Thumbnail'                           => 'Imagem',
        'Product'                             => 'Produto',
        'Price'                               => 'Preço',
        'Quantity'                            => 'Quantidade',
        'Subtotal'                            => 'Subtotal',
        'Total'                               => 'Total',
        'Shipping'                            => 'Entrega',
        'Coupon:'                             => 'Cupom:',
        'Coupon code'                         => 'Código do cupom',
        'Apply coupon'                        => 'Aplicar cupom',
        'Update cart'                         => 'Atualizar carrinho',
        'Proceed to checkout'                 => 'Finalizar meu pedido',
        'Your cart is currently empty.'       => 'Seu carrinho ainda está vazio — que tal explorar nossos produtos?',
        'Return to shop'                      => 'Voltar à loja',
        'Available on backorder'              => 'Disponível sob encomenda',
        'Cart updated.'                       => 'Carrinho atualizado.',
        'view cart'                           => 'ver carrinho',
        'Free!'                               => 'Grátis!',
        'Calculate shipping'                  => 'Calcular frete',
        'Flat rate'                           => 'Frete fixo',
        'Free shipping'                       => 'Frete grátis',
        'Local pickup'                        => 'Retirada no local',
        'No shipping options were found.'     => 'Nenhuma opção de frete encontrada.',
        'Enter your address to view shipping options.' => 'Informe seu endereço para ver as opções de frete.',

        // --- Checkout ---
        'Your order'                          => 'Seu pedido',
        'Billing details'                     => 'Dados de cobrança',
        'Shipping address'                    => 'Endereço de entrega',
        'Ship to a different address?'        => 'Enviar para outro endereço?',
        'Order notes'                         => 'Observações do pedido',
        'Notes about your order, e.g. special notes for delivery.' => 'Observações sobre seu pedido — ex.: instruções especiais para a entrega.',
        'Place order'                         => 'Confirmar pedido',
        'Payment'                             => 'Pagamento',
        'Payment method'                      => 'Forma de pagamento',
        'You must be logged in to checkout.'  => 'Você precisa estar logado para finalizar a compra.',
        'No payment methods are available. Please contact us if you require assistance or wish to make alternate arrangements.' => 'Nenhum método de pagamento disponível. Entre em contato conosco para obter ajuda.',
        'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.' => 'Não há métodos de pagamento disponíveis para sua região. Entre em contato conosco.',
        'privacy policy'                      => 'política de privacidade',
        'terms and conditions'                => 'termos e condições',

        // --- Página do produto ---
        'Add to cart'                         => 'Adicionar ao carrinho 🛒',
        'Select options'                      => 'Selecionar opções',
        'Read more'                           => 'Ver detalhes',
        'Out of stock'                        => 'Esgotado',
        'In stock'                            => 'Em estoque',
        'Sale!'                               => 'Oferta!',
        'Description'                         => 'Descrição',
        'Additional information'              => 'Informações adicionais',
        'Reviews'                             => 'Avaliações',
        'Related products'                    => 'Produtos relacionados',
        'You may also like&hellip;'           => 'Você também pode gostar…',
        'Category:'                           => 'Categoria:',
        'Categories:'                         => 'Categorias:',
        'Tag:'                                => 'Tag:',
        'Tags:'                               => 'Tags:',
        'Brand:'                              => 'Marca:',
        'Brands:'                             => 'Marcas:',
        'SKU:'                                => 'Cód.:',
        'SKU'                                 => 'Código',
        'Quantity'                            => 'Quantidade',
        'Choose an option'                    => 'Escolha uma opção',
        'Clear'                               => 'Limpar',

        // --- Vitrine / listagem ---
        'Shop'                                => 'Loja',
        'Products'                            => 'Produtos',
        'Product categories'                  => 'Categorias de produtos',
        'No products were found matching your selection.' => 'Nenhum produto encontrado para os filtros selecionados.',
        'No products found which match your selection.' => 'Nenhum produto encontrado para sua seleção.',
        'Default sorting'                     => 'Ordem padrão',
        'Sort by popularity'                  => 'Mais populares',
        'Sort by average rating'              => 'Melhor avaliados',
        'Sort by latest'                      => 'Mais recentes',
        'Sort by price: low to high'          => 'Preço: menor para maior',
        'Sort by price: high to low'          => 'Preço: maior para menor',
        'Filter by price'                     => 'Filtrar por preço',
        'Price'                               => 'Preço',
        'Filter'                              => 'Filtrar',

        // --- Minha conta ---
        'My account'                          => 'Minha conta',
        'Log in'                              => 'Entrar',
        'Login'                               => 'Entrar',
        'Register'                            => 'Criar conta',
        'Lost your password?'                 => 'Esqueceu sua senha?',
        'Username or email address'           => 'Usuário ou e-mail',
        'Password'                            => 'Senha',
        'Remember me'                         => 'Lembrar de mim',
        'Orders'                              => 'Pedidos',
        'Downloads'                           => 'Downloads',
        'Addresses'                           => 'Endereços',
        'Account details'                     => 'Dados da conta',
        'Logout'                              => 'Sair',
        'Log out'                             => 'Sair',
        'No order has been made yet.'         => 'Você ainda não fez nenhum pedido.',
        'Browse products'                     => 'Explorar produtos',

        // --- Confirmação do pedido ---
        'Thank you. Your order has been received.' => 'Obrigado! Seu pedido foi recebido com sucesso.',
        'Order number:'                       => 'Número do pedido:',
        'Date:'                               => 'Data:',
        'Email:'                              => 'E-mail:',
        'Total:'                              => 'Total:',
        'Payment method:'                     => 'Forma de pagamento:',
        'Order details'                       => 'Detalhes do pedido',
        'Billing address'                     => 'Endereço de cobrança',

        // --- Avisos e notificações ---
        'has been added to your cart.'        => 'foi adicionado ao seu carrinho.',
        'have been added to your cart.'       => 'foram adicionados ao seu carrinho.',
        'Coupon code applied successfully.'   => 'Cupom aplicado com sucesso.',
        'Coupon "%s" does not exist!'         => 'O cupom "%s" não existe.',
        'Sorry, this coupon is not applicable to your cart contents.' => 'Este cupom não se aplica ao seu carrinho.',
        'Sorry, this coupon has expired.'     => 'Este cupom está expirado.',
        'Your cart'                           => 'Seu carrinho',
        'Apply'                               => 'Aplicar',
        'Search'                              => 'Buscar',
        'Search results for &ldquo;%s&rdquo;' => 'Resultados para &ldquo;%s&rdquo;',
        'Search for&hellip;'                  => 'Buscar…',
        'Search for:'                         => 'Buscar:',
        'Search products&hellip;'             => 'Buscar produtos…',

        // --- Breadcrumb ---
        'Home'                                => 'Início',
        'Breadcrumb'                          => 'Você está em',

        // --- Avaliações do produto ---
        'Add a review'                        => 'Escrever uma avaliação',
        'Be the first to review &ldquo;%s&rdquo;' => 'Seja o primeiro a avaliar &ldquo;%s&rdquo;',
        'Your review'                         => 'Sua avaliação',
        'Your rating'                         => 'Sua nota',
        'Submit'                              => 'Enviar',
        'There are no reviews yet.'           => 'Ainda não há avaliações.',
        'Your review is awaiting approval'    => 'Sua avaliação está aguardando aprovação',
        'verified owner'                      => 'comprador verificado',
        'reviewed by %s'                      => 'avaliado por %s',
        'You must be %1$slogged in%2$s to post a review.' => 'Você precisa estar %1$slogado%2$s para escrever uma avaliação.',
        'Average'                             => 'Média',
        'Very poor'                           => 'Muito ruim',
        'Poor'                                => 'Ruim',
        'OK'                                  => 'Regular',
        'Good'                                => 'Bom',
        'Great'                               => 'Ótimo',
        'Perfect'                             => 'Perfeito',
        'Rated %s out of 5'                   => 'Avaliado com %s de 5',
        'Rated %s out of %s'                  => 'Avaliado com %s de %s',

        // --- Galeria do produto ---
        'Close (Esc)'                         => 'Fechar (Esc)',
        'Toggle fullscreen'                   => 'Tela cheia',
        'Zoom in/out'                         => 'Zoom',

        // --- Minha conta ---
        'Add payment method'                  => 'Adicionar forma de pagamento',
        'Change address'                      => 'Alterar endereço',
        'The following addresses will be used on the checkout page by default.' => 'Os seguintes endereços serão usados como padrão na finalização do pedido.',
        'You have not set up this type of address yet.' => 'Você ainda não configurou esse tipo de endereço.',
        'Available downloads'                 => 'Downloads disponíveis',
        'Actions'                             => 'Ações',
        'Confirm new password'                => 'Confirmar nova senha',
        'Create an account?'                  => 'Criar uma conta?',
        'Click here to login'                 => 'Clique aqui para entrar',
        'A link to set a new password will be sent to your email address.' => 'Um link para redefinir sua senha será enviado para o seu e-mail.',
        'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.' => 'Um e-mail de redefinição de senha foi enviado. Aguarde alguns minutos antes de tentar novamente.',
        'This will be how your name will be displayed in the account section and in reviews' => 'É assim que seu nome será exibido na conta e nas avaliações.',

        // --- Estoque e produto ---
        'This product is currently out of stock and unavailable.' => 'Este produto está esgotado no momento.',
        'Want to be notified when this product is back in stock?' => 'Quer ser avisado quando este produto voltar ao estoque?',
        'Clear options'                       => 'Limpar opções',

        // --- Carrinho e checkout ---
        '&ldquo;%s&rdquo; has been removed from your cart' => '&ldquo;%s&rdquo; foi removido do seu carrinho.',
        'There are some issues with the items in your cart. Please go back to the cart page and resolve these issues before checking out.' => 'Há um problema com os itens do seu carrinho. Volte ao carrinho e resolva antes de continuar.',
        'Change'                              => 'Alterar',
        'Click here to enter your code'       => 'Clique aqui para inserir seu cupom',

        // --- Pedidos / rastreamento ---
        'Track'                               => 'Rastrear',
        'View order'                          => 'Ver pedido',
        'View order number %s'                => 'Ver pedido nº %s',
        'Thank you for your order'            => 'Obrigado pelo seu pedido',
        'There are no orders to display.'     => 'Nenhum pedido encontrado.',
        'No order has been made yet.'         => 'Você ainda não fez nenhum pedido.',

        // --- Endereço ---
        'First name'                          => 'Nome',
        'Last name'                           => 'Sobrenome',
        'Company name (optional)'             => 'Empresa (opcional)',
        'Country / Region'                    => 'País / Região',
        'Street address'                      => 'Endereço',
        'Apartment, suite, unit, etc. (optional)' => 'Complemento (opcional)',
        'Town / City'                         => 'Cidade',
        'State / County'                      => 'Estado',
        'ZIP / Postal code'                   => 'CEP',
        'Phone'                               => 'Telefone',
        'Email address'                       => 'E-mail',
        'Save address'                        => 'Salvar endereço',
        'Address saved successfully.'         => 'Endereço salvo com sucesso.',
    );

    if ( isset( $traducoes[ $original ] ) ) {
        return $traducoes[ $original ];
    }

    return $traduzido;
}

/**
 * Traduz strings no plural do WooCommerce (ex.: "X resultado / X resultados").
 * O filtro ngettext recebe: singular, plural e quantidade.
 */
add_filter( 'ngettext', 'vivaleve_traduzir_plurais_woocommerce', 20, 5 );

function vivaleve_traduzir_plurais_woocommerce( $traduzido, $singular, $plural, $numero, $dominio ) {
    if ( 'woocommerce' !== $dominio ) {
        return $traduzido;
    }

    $mapa = array(
        'Category:'  => array( 'Categoria:', 'Categorias:' ),
        'Tag:'       => array( 'Tag:', 'Tags:' ),
        'Brand:'     => array( 'Marca:', 'Marcas:' ),
        '%d result'  => array( '%d resultado', '%d resultados' ),
        '%d item removed.' => array( '%d item removido.', '%d itens removidos.' ),
    );

    if ( isset( $mapa[ $singular ] ) ) {
        return $numero === 1 ? $mapa[ $singular ][0] : $mapa[ $singular ][1];
    }

    return $traduzido;
}

/**
 * Traduz strings do tema pai Storefront (domínio 'storefront').
 * Cobre os itens do menu mobile, rodapé handheld e acessibilidade.
 */
add_filter( 'gettext', 'vivaleve_traduzir_storefront', 20, 3 );

function vivaleve_traduzir_storefront( $traduzido, $original, $dominio ) {
    if ( 'storefront' !== $dominio ) {
        return $traduzido;
    }

    $traducoes = array(
        // --- Navegação e acessibilidade ---
        'Search'                    => 'Buscar',
        'Cart'                      => 'Carrinho',
        'My Account'                => 'Minha conta',
        'View your shopping cart'   => 'Ver seu carrinho',
        'Search Results for: %s'    => 'Resultados para: %s',
        'Skip to navigation'        => 'Ir para o menu',
        'Skip to content'           => 'Ir para o conteúdo',
        'Primary Navigation'        => 'Navegação principal',
        'Secondary Navigation'      => 'Navegação secundária',
        'Post Navigation'           => 'Navegação de posts',
        'Menu'                      => 'Menu',
        'Expand child menu'         => 'Expandir submenu',
        'Collapse child menu'       => 'Recolher submenu',

        // --- Meta do post (data, autor, comentários) ---
        'Posted on %s'              => 'Publicado em %s',
        'by'                        => 'por',
        'Leave a comment'           => 'Deixar um comentário',
        '1 Comment'                 => '1 Comentário',
        '% Comments'                => '% Comentários',
        'Continue reading %s'       => 'Continuar lendo %s',
        'Pages:'                    => 'Páginas:',
        'Next post:'                => 'Próximo post:',
        'Previous post:'            => 'Post anterior:',

        // --- Comentários ---
        'Your comment is awaiting moderation.' => 'Seu comentário está aguardando moderação.',
        'Edit'                      => 'Editar',
        'Edit this section'         => 'Editar esta seção',

        // --- Rodapé ---
        'Built with WooCommerce'    => 'Desenvolvido com WooCommerce',
        'Built with Storefront'     => 'Desenvolvido com Storefront',
    );

    if ( isset( $traducoes[ $original ] ) ) {
        return $traducoes[ $original ];
    }

    return $traduzido;
}

/**
 * Traduz plurais do tema pai Storefront (ex.: "%d item / %d items" no cabeçalho).
 */
add_filter( 'ngettext', 'vivaleve_traduzir_plurais_storefront', 20, 5 );

function vivaleve_traduzir_plurais_storefront( $traduzido, $singular, $plural, $numero, $dominio ) {
    if ( 'storefront' !== $dominio ) {
        return $traduzido;
    }

    $mapa = array(
        '%d item|%d items'       => array( '%d item', '%d itens' ),
        'Category:|Categories:'  => array( 'Categoria:', 'Categorias:' ),
        'Tag:|Tags:'             => array( 'Tag:', 'Tags:' ),
    );

    $chave = $singular . '|' . $plural;

    if ( isset( $mapa[ $chave ] ) ) {
        return $numero === 1 ? $mapa[ $chave ][0] : $mapa[ $chave ][1];
    }

    return $traduzido;
}

/**
 * Traduz strings com contexto (_x) do tema pai Storefront.
 * Cobre paginação (Próximo / Anterior) e similares.
 */
add_filter( 'gettext_with_context', 'vivaleve_traduzir_contexto_storefront', 20, 4 );

function vivaleve_traduzir_contexto_storefront( $traduzido, $original, $contexto, $dominio ) {
    if ( 'storefront' !== $dominio ) {
        return $traduzido;
    }

    $traducoes = array(
        'Next|Next post'          => 'Próximo',
        'Previous|Previous post'  => 'Anterior',
    );

    $chave = $original . '|' . $contexto;

    if ( isset( $traducoes[ $chave ] ) ) {
        return $traducoes[ $chave ];
    }

    return $traduzido;
}

/**
 * Traduz strings com contexto (_x / esc_attr_x / esc_html_x) do WooCommerce.
 * O gettext não cobre _x() — é necessário gettext_with_context.
 */
add_filter( 'gettext_with_context', 'vivaleve_traduzir_contexto_woocommerce', 20, 4 );

function vivaleve_traduzir_contexto_woocommerce( $traduzido, $original, $contexto, $dominio ) {
    if ( 'woocommerce' !== $dominio ) {
        return $traduzido;
    }

    $traducoes = array(
        // Botão de busca
        'Search|submit button'          => 'Buscar',

        // Avaliações de produto
        '1|single'                      => '1',
        'rated|adjective'               => 'avaliado',

        // Ordenação
        'ASC|sort direction'            => 'Crescente',
        'DESC|sort direction'           => 'Decrescente',

        // Dias / tempo
        'N/A|not applicable'            => 'N/A',
    );

    $chave = $original . '|' . $contexto;

    if ( isset( $traducoes[ $chave ] ) ) {
        return $traducoes[ $chave ];
    }

    return $traduzido;
}


/* Tradução do rótulo "Brand" independente do text domain (WooCommerce Brands nativo ou plugin) */
add_filter( 'gettext', function( $traduzido, $original, $dominio ) {
    if ( 'Brand:'  === $original ) return 'Marca:';
    if ( 'Brands:' === $original ) return 'Marcas:';
    if ( 'Brand'   === $original ) return 'Marca';
    if ( 'Brands'  === $original ) return 'Marcas';
    return $traduzido;
}, 99, 3 );

add_filter( 'ngettext', function( $traduzido, $singular, $plural, $numero, $dominio ) {
    if ( 'Brand:' === $singular && 'Brands:' === $plural ) {
        return $numero === 1 ? 'Marca:' : 'Marcas:';
    }
    return $traduzido;
}, 99, 5 );


/* =========================================================
 * 11. VITRINE DE PRODUTOS — FILTROS LATERAIS
 * =========================================================
 * Layout full-width na vitrine (sidebar customizada via template).
 * Filtros por preço, avaliação e disponibilidade via query vars.
 * ========================================================= */

// Layout full-width em todas as páginas de arquivo de produtos
add_filter( 'storefront_layout', function( $layout ) {
    if ( is_shop() || is_product_category() || is_product_tag() || is_product() ) {
        return 'full-width';
    }
    return $layout;
} );

// Remove breadcrumb nas páginas de arquivo — o título da página já orienta o usuário
add_action( 'woocommerce_before_main_content', function() {
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
    }
}, 5 );

// Filtra por estoque quando ?apenas_em_estoque=1
add_action( 'woocommerce_product_query', function( $q ) {
    if ( empty( $_GET['apenas_em_estoque'] ) ) return;
    $meta = (array) $q->get( 'meta_query' );
    $meta[] = array(
        'key'   => '_stock_status',
        'value' => 'instock',
    );
    $q->set( 'meta_query', $meta );
} );

// Filtra por avaliação mínima quando ?min_rating=N
add_action( 'woocommerce_product_query', function( $q ) {
    if ( empty( $_GET['min_rating'] ) ) return;
    $min = (int) $_GET['min_rating'];
    if ( $min <= 0 || $min > 5 ) return;
    $meta = (array) $q->get( 'meta_query' );
    $meta[] = array(
        'key'     => '_wc_average_rating',
        'value'   => $min,
        'compare' => '>=',
        'type'    => 'DECIMAL(10,2)',
    );
    $q->set( 'meta_query', $meta );
} );

// Função auxiliar: verifica se um termo é descendente de outro
function is_child_term( $term_id, $parent_id ) {
    $ancestors = get_ancestors( $term_id, 'product_cat' );
    return in_array( $parent_id, $ancestors, true );
}


/* =========================================================
 * 5. LIMPEZA DO <HEAD>
 * =========================================================
 * Remove metadados desnecessários do <head> para reduzir
 * exposição de informações e deixar o HTML mais limpo.
 * ========================================================= */

// Remove a meta tag "generator" que expõe a versão do WordPress
remove_action( 'wp_head', 'wp_generator' );


/* =========================================================
 * 10. PÁGINA DE PRODUTO — ALTA CONVERSÃO
 * =========================================================
 * Ajusta layout, adiciona selos de confiança, urgência de
 * estoque e barra sticky de compra via hooks do WooCommerce.
 * ========================================================= */

// Remove sidebar na página de produto (layout full-width)
add_filter( 'storefront_layout', function( $layout ) {
    if ( is_product() ) return 'full-width';
    return $layout;
} );

// Remove compartilhamento (pouco relevante para conversão)
add_action( 'init', function() {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
} );

// Move a avaliação para antes do preço (prioridade 8) para dar credibilidade primeiro
add_action( 'init', function() {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 8 );
} );

// Botão WhatsApp logo abaixo do add-to-cart (prioridade 31)
add_action( 'woocommerce_single_product_summary', 'vivaleve_btn_whatsapp', 31 );

function vivaleve_btn_whatsapp() {
    $numero = preg_replace( '/\D/', '', get_theme_mod( 'vl_footer_whatsapp', '' ) );
    if ( ! $numero ) return;

    global $product;
    $mensagem = rawurlencode(
        sprintf(
            __( 'Olá! Tenho interesse no produto: %s — %s', 'viva-leve-child' ),
            get_the_title(),
            get_permalink()
        )
    );

    $url = 'https://wa.me/' . esc_attr( $numero ) . '?text=' . $mensagem;
    ?>
    <a href="<?php echo esc_url( $url ); ?>"
       class="vl-btn-whatsapp"
       target="_blank"
       rel="noopener noreferrer"
       aria-label="<?php esc_attr_e( 'Comprar pelo WhatsApp', 'viva-leve-child' ); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
        </svg>
        <?php esc_html_e( 'Comprar pelo WhatsApp', 'viva-leve-child' ); ?>
    </a>
    <?php
}

// Selos de confiança abaixo do botão de compra (prioridade 35)
add_action( 'woocommerce_single_product_summary', 'vivaleve_selos_confianca', 35 );

function vivaleve_selos_confianca() {
    ?>
    <div class="vl-produto-selos">
        <div class="vl-produto-selo">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
            <span><?php esc_html_e( 'Compra 100% segura', 'viva-leve-child' ); ?></span>
        </div>
        <div class="vl-produto-selo">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            <span><?php esc_html_e( 'Envio para todo o Brasil', 'viva-leve-child' ); ?></span>
        </div>
        <div class="vl-produto-selo">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span><?php esc_html_e( 'Devolução facilitada', 'viva-leve-child' ); ?></span>
        </div>
    </div>
    <?php
}

// Chamada para consulta com a Fisioterapeuta (prioridade 37 — após selos de confiança)
add_action( 'woocommerce_single_product_summary', 'vivaleve_consulta_especialista', 37 );

function vivaleve_consulta_especialista() {
    $numero = preg_replace( '/\D/', '', get_theme_mod( 'vl_footer_whatsapp', '' ) );
    if ( ! $numero ) return;

    $mensagem = rawurlencode(
        sprintf(
            'Olá! Tenho dúvidas sobre o produto "%s" e gostaria de falar com a fisioterapeuta especialista.',
            get_the_title()
        )
    );
    $url = 'https://wa.me/' . esc_attr( $numero ) . '?text=' . $mensagem;
    ?>
    <div class="vl-consulta-especialista">
        <div class="vl-consulta-icone" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="1.6"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
                <path d="M12 11v2m0 2h.01"/>
            </svg>
        </div>
        <div class="vl-consulta-corpo">
            <strong class="vl-consulta-titulo">
                <?php esc_html_e( 'Tem dúvidas sobre este produto?', 'viva-leve-child' ); ?>
            </strong>
            <span class="vl-consulta-subtitulo">
                <?php esc_html_e( 'Fale com nossa Fisioterapeuta especialista antes de comprar.', 'viva-leve-child' ); ?>
            </span>
        </div>
        <a href="<?php echo esc_url( $url ); ?>"
           class="vl-consulta-btn"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="<?php esc_attr_e( 'Falar com a fisioterapeuta pelo WhatsApp', 'viva-leve-child' ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
            </svg>
            <?php esc_html_e( 'Falar com especialista', 'viva-leve-child' ); ?>
        </a>
    </div>
    <?php
}

// Urgência de estoque logo antes do add-to-cart (prioridade 29)
add_action( 'woocommerce_single_product_summary', 'vivaleve_urgencia_estoque', 29 );

function vivaleve_urgencia_estoque() {
    global $product;
    if ( ! $product || ! $product->managing_stock() ) return;

    $estoque = $product->get_stock_quantity();
    if ( $estoque === null || $estoque > 10 ) return;

    if ( $estoque <= 0 ) return;

    $classe = $estoque <= 3 ? 'vl-estoque--critico' : 'vl-estoque--baixo';
    ?>
    <p class="vl-estoque-urgencia <?php echo esc_attr( $classe ); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <?php printf(
            esc_html( _n( 'Restam apenas %d unidade em estoque!', 'Restam apenas %d unidades em estoque!', $estoque, 'viva-leve-child' ) ),
            $estoque
        ); ?>
    </p>
    <?php
}

// Barra sticky de compra — dados via atributo data para o JS
add_action( 'woocommerce_after_single_product', 'vivaleve_barra_sticky' );

function vivaleve_barra_sticky() {
    global $product;
    if ( ! $product ) return;

    $preco = $product->get_price_html();
    $nome  = get_the_title();
    $img   = get_the_post_thumbnail_url( null, 'thumbnail' );
    ?>
    <div class="vl-sticky-bar" id="vl-sticky-bar" aria-hidden="true">
        <div class="vl-sticky-bar-inner">
            <div class="vl-sticky-bar-produto">
                <?php if ( $img ) : ?>
                    <img src="<?php echo esc_url( $img ); ?>" alt="" class="vl-sticky-bar-img" aria-hidden="true">
                <?php endif; ?>
                <div class="vl-sticky-bar-info">
                    <span class="vl-sticky-bar-nome"><?php echo esc_html( $nome ); ?></span>
                    <span class="vl-sticky-bar-preco"><?php echo wp_kses_post( $preco ); ?></span>
                </div>
            </div>
            <button class="vl-sticky-bar-btn button" id="vl-sticky-bar-btn">
                <?php esc_html_e( 'Adicionar ao carrinho', 'viva-leve-child' ); ?>
            </button>
        </div>
    </div>
    <?php
}


/* =========================================================
 * 8. CUSTOMIZER: SEÇÃO SOBRE A MARCA
 * =========================================================
 * Adiciona um painel em Aparência → Personalizar para editar
 * o conteúdo da seção "Sobre a marca" sem tocar em código.
 * ========================================================= */

add_action( 'customize_register', 'vivaleve_customizer_footer' );

function vivaleve_customizer_footer( $wp_customize ) {

    $wp_customize->add_section( 'vl_footer', array(
        'title'    => 'Rodapé',
        'priority' => 35,
    ) );

    $campos = array(
        array( 'id' => 'vl_footer_tagline',   'label' => 'Tagline da marca',    'default' => 'Saúde e bem-estar para o seu dia a dia.' ),
        array( 'id' => 'vl_footer_telefone',  'label' => 'Telefone / WhatsApp', 'default' => '' ),
        array( 'id' => 'vl_footer_email',     'label' => 'E-mail de contato',   'default' => '' ),
        array( 'id' => 'vl_footer_endereco',  'label' => 'Endereço',            'default' => '' ),
        array( 'id' => 'vl_footer_instagram', 'label' => 'Instagram (URL)',      'default' => '' ),
        array( 'id' => 'vl_footer_facebook',  'label' => 'Facebook (URL)',       'default' => '' ),
        array( 'id' => 'vl_footer_whatsapp',  'label' => 'WhatsApp (número com DDD, só números)', 'default' => '' ),
    );

    foreach ( $campos as $campo ) {
        $wp_customize->add_setting( $campo['id'], array(
            'default'           => $campo['default'],
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( $campo['id'], array(
            'label'   => $campo['label'],
            'section' => 'vl_footer',
            'type'    => 'text',
        ) );
    }
}

add_action( 'customize_register', 'vivaleve_customizer_sobre_marca' );

function vivaleve_customizer_sobre_marca( $wp_customize ) {

    $wp_customize->add_section( 'vl_sobre_marca', array(
        'title'    => 'Sobre a Marca',
        'panel'    => '',
        'priority' => 40,
    ) );

    $campos = array(
        array(
            'id'      => 'vl_sobre_exibir',
            'label'   => 'Exibir seção na página inicial',
            'type'    => 'checkbox',
            'default' => true,
        ),
        array(
            'id'      => 'vl_sobre_titulo',
            'label'   => 'Título',
            'type'    => 'text',
            'default' => 'Nossa missão é cuidar de você',
        ),
        array(
            'id'      => 'vl_sobre_texto',
            'label'   => 'Texto',
            'type'    => 'textarea',
            'default' => 'Nascemos com o propósito de levar saúde, conforto e bem-estar para o seu dia a dia. Cada produto é selecionado com cuidado para garantir qualidade e resultados reais.',
        ),
        array(
            'id'      => 'vl_sobre_btn_texto',
            'label'   => 'Texto do botão',
            'type'    => 'text',
            'default' => 'Conheça nossa história',
        ),
        array(
            'id'      => 'vl_sobre_btn_url',
            'label'   => 'Link do botão',
            'type'    => 'url',
            'default' => '',
        ),
    );

    foreach ( $campos as $campo ) {
        $wp_customize->add_setting( $campo['id'], array(
            'default'           => $campo['default'],
            'sanitize_callback' => $campo['type'] === 'checkbox' ? 'wp_validate_boolean'
                                 : ( $campo['type'] === 'url'  ? 'esc_url_raw' : 'sanitize_textarea_field' ),
            'transport'         => 'refresh',
        ) );

        $control_args = array(
            'label'   => $campo['label'],
            'section' => 'vl_sobre_marca',
            'type'    => $campo['type'],
        );

        $wp_customize->add_control( $campo['id'], $control_args );
    }

    // Upload de imagem separado
    $wp_customize->add_setting( 'vl_sobre_imagem', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'vl_sobre_imagem', array(
        'label'     => 'Imagem',
        'section'   => 'vl_sobre_marca',
        'mime_type' => 'image',
    ) ) );
}


/* =========================================================
 * 6. CPT: BANNERS DO SLIDER
 * =========================================================
 * Registra o tipo de post "vl_slide" para gerenciar os banners
 * da página inicial pelo painel do WordPress.
 * Cada slide suporta: título, imagem destacada, subtítulo,
 * texto do botão e URL do botão (via meta boxes nativos).
 * ========================================================= */

add_action( 'init', 'vivaleve_registrar_cpt_slide' );

function vivaleve_registrar_cpt_slide() {
    register_post_type( 'vl_slide', array(
        'labels' => array(
            'name'               => 'Banners',
            'singular_name'      => 'Banner',
            'add_new'            => 'Adicionar Banner',
            'add_new_item'       => 'Adicionar Novo Banner',
            'edit_item'          => 'Editar Banner',
            'new_item'           => 'Novo Banner',
            'view_item'          => 'Ver Banner',
            'search_items'       => 'Buscar Banners',
            'not_found'          => 'Nenhum banner encontrado',
            'not_found_in_trash' => 'Nenhum banner na lixeira',
            'menu_name'          => 'Banners',
        ),
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-images-alt2',
        'menu_position' => 5,
        'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
        'rewrite'       => false,
    ) );
}

// Meta box: subtítulo, texto do botão e URL do botão
add_action( 'add_meta_boxes', 'vivaleve_slide_meta_box' );

function vivaleve_slide_meta_box() {
    add_meta_box(
        'vl_slide_detalhes',
        'Detalhes do Banner',
        'vivaleve_slide_meta_box_html',
        'vl_slide',
        'normal',
        'high'
    );
}

function vivaleve_slide_meta_box_html( $post ) {
    ?>
    <p style="color:#555;font-size:0.9em;margin:0;">
        Defina a <strong>Imagem Destacada</strong> do banner (coluna à direita).<br>
        A ordem de exibição é controlada pelo campo <strong>Ordem</strong> em Atributos do post.
    </p>
    <?php
}


/* =========================================================
 * 7. CPT: DEPOIMENTOS DE CLIENTES
 * =========================================================
 * Gerencia os depoimentos exibidos na página inicial.
 * Cada depoimento tem: texto (conteúdo), nome do cliente (título),
 * cargo/cidade, avaliação em estrelas e foto (imagem destacada).
 * ========================================================= */

add_action( 'init', 'vivaleve_registrar_cpt_depoimento' );

function vivaleve_registrar_cpt_depoimento() {
    register_post_type( 'vl_depoimento', array(
        'labels' => array(
            'name'               => 'Depoimentos',
            'singular_name'      => 'Depoimento',
            'add_new'            => 'Adicionar Depoimento',
            'add_new_item'       => 'Adicionar Novo Depoimento',
            'edit_item'          => 'Editar Depoimento',
            'not_found'          => 'Nenhum depoimento encontrado',
            'not_found_in_trash' => 'Nenhum depoimento na lixeira',
            'menu_name'          => 'Depoimentos',
        ),
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-format-quote',
        'menu_position' => 6,
        'supports'      => array( 'title', 'editor', 'thumbnail' ),
        'rewrite'       => false,
    ) );
}

add_action( 'add_meta_boxes', 'vivaleve_depoimento_meta_box' );

function vivaleve_depoimento_meta_box() {
    add_meta_box(
        'vl_depoimento_detalhes',
        'Detalhes do Depoimento',
        'vivaleve_depoimento_meta_box_html',
        'vl_depoimento',
        'normal',
        'high'
    );
}

function vivaleve_depoimento_meta_box_html( $post ) {
    wp_nonce_field( 'vl_depoimento_salvar', 'vl_depoimento_nonce' );
    $cargo     = get_post_meta( $post->ID, '_vl_depoimento_cargo', true );
    $avaliacao = get_post_meta( $post->ID, '_vl_depoimento_avaliacao', true );
    if ( '' === $avaliacao ) $avaliacao = '5';
    ?>
    <p>
        <label for="vl_depoimento_cargo"><strong>Cargo / Cidade</strong></label><br>
        <input type="text" id="vl_depoimento_cargo" name="vl_depoimento_cargo"
               value="<?php echo esc_attr( $cargo ); ?>" style="width:100%"
               placeholder="Ex: São Paulo, SP">
    </p>
    <p>
        <label for="vl_depoimento_avaliacao"><strong>Avaliação (1 a 5 estrelas)</strong></label><br>
        <select id="vl_depoimento_avaliacao" name="vl_depoimento_avaliacao">
            <?php for ( $i = 5; $i >= 1; $i-- ) : ?>
                <option value="<?php echo $i; ?>" <?php selected( $avaliacao, (string) $i ); ?>>
                    <?php echo str_repeat( '★', $i ) . str_repeat( '☆', 5 - $i ) . ' (' . $i . ')'; ?>
                </option>
            <?php endfor; ?>
        </select>
    </p>
    <p style="color:#555;font-size:0.9em;margin:0;">
        O texto do depoimento vai no campo <strong>conteúdo</strong> abaixo.<br>
        O nome do cliente é o <strong>título</strong> do post.<br>
        A <strong>Imagem Destacada</strong> é usada como foto do cliente (opcional).
    </p>
    <?php
}

add_action( 'save_post_vl_depoimento', 'vivaleve_depoimento_salvar_meta' );

function vivaleve_depoimento_salvar_meta( $post_id ) {
    if ( ! isset( $_POST['vl_depoimento_nonce'] )
        || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vl_depoimento_nonce'] ) ), 'vl_depoimento_salvar' )
        || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        || ! current_user_can( 'edit_post', $post_id )
    ) {
        return;
    }

    if ( isset( $_POST['vl_depoimento_cargo'] ) ) {
        update_post_meta( $post_id, '_vl_depoimento_cargo', sanitize_text_field( wp_unslash( $_POST['vl_depoimento_cargo'] ) ) );
    }

    if ( isset( $_POST['vl_depoimento_avaliacao'] ) ) {
        $av = intval( $_POST['vl_depoimento_avaliacao'] );
        update_post_meta( $post_id, '_vl_depoimento_avaliacao', max( 1, min( 5, $av ) ) );
    }
}


/* =========================================================
 * 9. CPT: FAQ
 * =========================================================
 * Perguntas e respostas exibidas na página inicial.
 * Título = pergunta. Conteúdo = resposta.
 * Ordem controlada pelo campo Ordem nos Atributos do post.
 * ========================================================= */

add_action( 'init', 'vivaleve_registrar_cpt_faq' );

function vivaleve_registrar_cpt_faq() {
    register_post_type( 'vl_faq', array(
        'labels' => array(
            'name'               => 'FAQ',
            'singular_name'      => 'Pergunta',
            'add_new'            => 'Adicionar Pergunta',
            'add_new_item'       => 'Adicionar Nova Pergunta',
            'edit_item'          => 'Editar Pergunta',
            'not_found'          => 'Nenhuma pergunta encontrada',
            'not_found_in_trash' => 'Nenhuma pergunta na lixeira',
            'menu_name'          => 'FAQ',
        ),
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-editor-help',
        'menu_position' => 7,
        'supports'      => array( 'title', 'editor', 'page-attributes' ),
        'rewrite'       => false,
    ) );
}


/* =========================================================
 * 10. GRID DE PRODUTOS — COLUNAS FIXAS
 * =========================================================
 * Força 3 colunas por linha nas páginas de loja/categoria,
 * garantindo que a classe columns-3 seja sempre emitida no
 * ul.products e evitando conflito com regras float do Storefront.
 * ========================================================= */

add_filter( 'loop_shop_columns', 'vivaleve_loop_colunas' );
add_filter( 'woocommerce_loop_columns', 'vivaleve_loop_colunas' );
add_filter( 'storefront_loop_columns', 'vivaleve_loop_colunas' );

function vivaleve_loop_colunas( $colunas ) {
    return 3;
}
