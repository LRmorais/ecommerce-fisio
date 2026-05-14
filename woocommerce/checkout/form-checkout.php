<?php
/**
 * Override: checkout/form-checkout.php — Viva Leve Child Theme
 *
 * @package VivaLeveChild
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_checkout() ) {
	return;
}

if ( WC()->cart->is_empty() ) {
	wc_add_notice( 'Seu carrinho está vazio. Explore nossos produtos antes de finalizar a compra.', 'error' );
}

do_action( 'woocommerce_before_checkout_form', WC()->checkout() );

if ( ! WC()->checkout()->is_registration_enabled() && WC()->checkout()->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', 'Você precisa estar logado para finalizar a compra.' ) );
	return;
}
?>

<div class="vl-checkout-wrap">

    <div class="vl-checkout-header">
        <h1 class="vl-checkout-titulo"><?php esc_html_e( 'Finalizar pedido', 'viva-leve-child' ); ?></h1>
        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="vl-checkout-voltar">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.5"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            <?php esc_html_e( 'Voltar ao carrinho', 'viva-leve-child' ); ?>
        </a>
    </div>

    <form name="checkout" method="post"
          class="checkout woocommerce-checkout vl-checkout-layout"
          action="<?php echo esc_url( wc_get_checkout_url() ); ?>"
          enctype="multipart/form-data">

        <!-- ── Campos do formulário ── -->
        <div class="vl-checkout-form">

            <?php if ( $checkout->get_checkout_fields() ) : ?>

                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                <div id="customer_details">
                    <?php do_action( 'woocommerce_checkout_billing' ); ?>
                    <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                </div>

                <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

            <?php endif; ?>

        </div><!-- .vl-checkout-form -->

        <!-- ── Resumo do pedido ── -->
        <div class="vl-checkout-sidebar">

            <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

            <h3 id="order_review_heading"><?php esc_html_e( 'Seu pedido', 'viva-leve-child' ); ?></h3>

            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

            <div id="order_review" class="woocommerce-checkout-review-order">
                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
            </div>

            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

        </div><!-- .vl-checkout-sidebar -->

    </form>

</div><!-- .vl-checkout-wrap -->

<?php do_action( 'woocommerce_after_checkout_form', WC()->checkout() ); ?>
