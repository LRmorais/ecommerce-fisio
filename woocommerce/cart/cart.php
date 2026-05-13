<?php
/**
 * Cart — Viva Leve Child Theme
 *
 * @package VivaLeveChild
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
?>

<div class="vl-cart-wrap">

    <div class="vl-cart-header">
        <h1 class="vl-cart-titulo"><?php esc_html_e( 'Seu carrinho', 'viva-leve-child' ); ?></h1>
        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="vl-cart-continuar">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.5"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            <?php esc_html_e( 'Continuar comprando', 'viva-leve-child' ); ?>
        </a>
    </div>

    <div class="vl-cart-layout">

        <!-- ── Itens do carrinho ── -->
        <div class="vl-cart-itens">
            <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                <?php do_action( 'woocommerce_before_cart_table' ); ?>

                <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="product-remove"><span class="screen-reader-text"><?php esc_html_e( 'Remover', 'viva-leve-child' ); ?></span></th>
                            <th class="product-thumbnail"><span class="screen-reader-text"><?php esc_html_e( 'Imagem', 'viva-leve-child' ); ?></span></th>
                            <th class="product-name"><?php esc_html_e( 'Produto', 'viva-leve-child' ); ?></th>
                            <th class="product-price"><?php esc_html_e( 'Preço', 'viva-leve-child' ); ?></th>
                            <th class="product-quantity"><?php esc_html_e( 'Quantidade', 'viva-leve-child' ); ?></th>
                            <th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'viva-leve-child' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                        <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                            $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                            $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

                            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        ?>
                        <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                            <td class="product-remove">
                                <?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                    '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                    </a>',
                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                    esc_attr( sprintf( __( 'Remover %s do carrinho', 'viva-leve-child' ), wp_strip_all_tags( $product_name ) ) ),
                                    esc_attr( $product_id ),
                                    esc_attr( $_product->get_sku() )
                                ), $cart_item_key ); // phpcs:ignore ?>
                            </td>

                            <td class="product-thumbnail">
                                <?php $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                if ( ! $product_permalink ) {
                                    echo $thumbnail; // phpcs:ignore
                                } else {
                                    printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore
                                } ?>
                            </td>

                            <td class="product-name" data-title="<?php esc_attr_e( 'Produto', 'viva-leve-child' ); ?>">
                                <?php if ( ! $product_permalink ) {
                                    echo wp_kses_post( $product_name );
                                } else {
                                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                }
                                do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
                                echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore
                                if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Disponível sob encomenda', 'viva-leve-child' ) . '</p>', $product_id ) );
                                } ?>
                            </td>

                            <td class="product-price" data-title="<?php esc_attr_e( 'Preço', 'viva-leve-child' ); ?>">
                                <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore ?>
                            </td>

                            <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantidade', 'viva-leve-child' ); ?>">
                                <?php
                                if ( $_product->is_sold_individually() ) {
                                    $min_quantity = 1;
                                    $max_quantity = 1;
                                } else {
                                    $min_quantity = 0;
                                    $max_quantity = $_product->get_max_purchase_quantity();
                                }
                                echo apply_filters( 'woocommerce_cart_item_quantity', woocommerce_quantity_input( array(
                                    'input_name'   => "cart[{$cart_item_key}][qty]",
                                    'input_value'  => $cart_item['quantity'],
                                    'max_value'    => $max_quantity,
                                    'min_value'    => $min_quantity,
                                    'product_name' => $product_name,
                                ), $_product, false ), $cart_item_key, $cart_item ); // phpcs:ignore ?>
                            </td>

                            <td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'viva-leve-child' ); ?>">
                                <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore ?>
                            </td>

                        </tr>
                        <?php endif; endforeach; ?>

                        <?php do_action( 'woocommerce_cart_contents' ); ?>

                        <tr>
                            <td colspan="6" class="actions">
                                <?php if ( wc_coupons_enabled() ) : ?>
                                <div class="coupon">
                                    <label for="coupon_code"><?php esc_html_e( 'Cupom de desconto', 'viva-leve-child' ); ?></label>
                                    <div class="coupon-fields">
                                        <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Código do cupom', 'viva-leve-child' ); ?>" />
                                        <button type="submit" class="button vl-btn-cupom" name="apply_coupon" value="<?php esc_attr_e( 'Aplicar cupom', 'viva-leve-child' ); ?>"><?php esc_html_e( 'Aplicar', 'viva-leve-child' ); ?></button>
                                    </div>
                                    <?php do_action( 'woocommerce_cart_coupon' ); ?>
                                </div>
                                <?php endif; ?>

                                <button type="submit" class="button vl-btn-atualizar" name="update_cart" value="<?php esc_attr_e( 'Atualizar carrinho', 'viva-leve-child' ); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                                    <?php esc_html_e( 'Atualizar carrinho', 'viva-leve-child' ); ?>
                                </button>

                                <?php do_action( 'woocommerce_cart_actions' ); ?>
                                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                            </td>
                        </tr>

                        <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                    </tbody>
                </table>

                <?php do_action( 'woocommerce_after_cart_table' ); ?>
            </form>
        </div><!-- .vl-cart-itens -->

        <!-- ── Totais ── -->
        <div class="vl-cart-sidebar">
            <?php do_action( 'woocommerce_cart_collaterals' ); ?>
        </div>

    </div><!-- .vl-cart-layout -->

</div><!-- .vl-cart-wrap -->

<?php do_action( 'woocommerce_after_cart' ); ?>
