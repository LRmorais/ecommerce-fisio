<?php
/**
 * Override: myaccount/my-account.php — Viva Leve Child Theme
 *
 * @package VivaLeveChild
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="vl-account-wrap">

    <?php if ( is_user_logged_in() ) : ?>
    <div class="vl-account-header">
        <h1 class="vl-account-titulo"><?php esc_html_e( 'Minha Conta', 'viva-leve-child' ); ?></h1>
    </div>
    <?php endif; ?>

    <div class="vl-account-layout">

        <?php do_action( 'woocommerce_account_navigation' ); ?>

        <div class="woocommerce-MyAccount-content">
            <?php do_action( 'woocommerce_account_content' ); ?>
        </div>

    </div>

</div>
