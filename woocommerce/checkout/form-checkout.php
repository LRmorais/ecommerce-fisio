<?php
/**
 * Override: checkout/form-checkout.php
 * Viva Leve Child Theme
 * Baseado no template original do WooCommerce — personalize abaixo
 *
 * COMO OBTER O ARQUIVO ORIGINAL:
 * Copie de:
 *   ~/Local Sites/viva-leve/app/public/wp-content/plugins/woocommerce/templates/checkout/form-checkout.php
 * Cole em:
 *   ~/Local Sites/viva-leve/app/public/wp-content/themes/viva-leve-child/woocommerce/checkout/form-checkout.php
 *
 * Este arquivo controla o layout do formulário de checkout:
 * dados do comprador, endereço de entrega, método de pagamento e confirmação.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Checkout
 */

defined( 'ABSPATH' ) || exit;

// Se o checkout não está habilitado, mostra o aviso padrão
if ( ! is_checkout() ) {
	return;
}

// Redireciona se o carrinho estiver vazio
if ( WC()->cart->is_empty() ) {
	wc_add_notice( __( 'Your cart is currently empty.', 'woocommerce' ), 'error' );
}

// Carrega os scripts de checkout do WooCommerce
do_action( 'woocommerce_before_checkout_form', WC()->checkout() );

// Se o checkout não está habilitado após as verificações
if ( ! WC()->checkout()->is_registration_enabled() && WC()->checkout()->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="col2-set" id="customer_details">
			<div class="col-1">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="col-2">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>

	<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

	<h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', WC()->checkout() ); ?>
