<?php
/**
 * Override: myaccount/dashboard.php — Viva Leve Child Theme
 *
 * @package VivaLeveChild
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="vl-account-dashboard">

    <p class="vl-account-saudacao">
        <?php
        printf(
            /* translators: 1: nome do usuário 2: url de logout */
            wp_kses(
                __( 'Olá, <strong>%1$s</strong>! (<a href="%2$s">Não é você? Sair</a>)', 'viva-leve-child' ),
                array(
                    'strong' => array(),
                    'a'      => array( 'href' => array() ),
                )
            ),
            esc_html( $current_user->display_name ),
            esc_url( wc_logout_url() )
        );
        ?>
    </p>

    <p class="vl-account-descricao">
        <?php
        $desc = wc_shipping_enabled()
            ? __( 'No painel você pode acompanhar seus <a href="%1$s">pedidos recentes</a>, gerenciar seus <a href="%2$s">endereços de entrega e cobrança</a>, e <a href="%3$s">editar seus dados e senha</a>.', 'viva-leve-child' )
            : __( 'No painel você pode acompanhar seus <a href="%1$s">pedidos recentes</a>, gerenciar seu <a href="%2$s">endereço de cobrança</a>, e <a href="%3$s">editar seus dados e senha</a>.', 'viva-leve-child' );

        printf(
            wp_kses( $desc, array( 'a' => array( 'href' => array() ) ) ),
            esc_url( wc_get_endpoint_url( 'orders' ) ),
            esc_url( wc_get_endpoint_url( 'edit-address' ) ),
            esc_url( wc_get_endpoint_url( 'edit-account' ) )
        );
        ?>
    </p>

    <?php do_action( 'woocommerce_account_dashboard' ); ?>

</div>
