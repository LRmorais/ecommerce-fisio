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
 * Exibe as páginas publicadas, excluindo páginas funcionais do WooCommerce.
 */
function vivaleve_menu_fallback() {
    $excluir = array_filter( array_map( 'intval', [
        get_option( 'woocommerce_cart_page_id' ),
        get_option( 'woocommerce_checkout_page_id' ),
        get_option( 'woocommerce_myaccount_page_id' ),
    ] ) );

    echo '<ul class="vl-menu">';
    wp_list_pages( array(
        'title_li' => '',
        'exclude'  => implode( ',', $excluir ),
        'echo'     => true,
    ) );
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

    // "%d result" / "%d results"
    if ( '%d result' === $singular && '%d results' === $plural ) {
        return $numero === 1 ? '%d resultado' : '%d resultados';
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


/* =========================================================
 * 5. LIMPEZA DO <HEAD>
 * =========================================================
 * Remove metadados desnecessários do <head> para reduzir
 * exposição de informações e deixar o HTML mais limpo.
 * ========================================================= */

// Remove a meta tag "generator" que expõe a versão do WordPress
remove_action( 'wp_head', 'wp_generator' );
