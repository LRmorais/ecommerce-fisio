<?php
/**
 * Template part: Barra de benefícios
 *
 * @package VivaLeveChild
 */

$beneficios = array(
    array(
        'icone' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z"/><path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>',
        'titulo'    => 'Suporte Online Especializado',
        'descricao' => 'Nossa equipe está pronta para te ajudar a encontrar o produto ideal.',
    ),
    array(
        'icone' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
        'titulo'    => 'Envio para Todo o Brasil',
        'descricao' => 'Entregamos em qualquer cidade, com segurança e agilidade.',
    ),
    array(
        'icone' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>',
        'titulo'    => 'Compra 100% Segura',
        'descricao' => 'Ambiente protegido, seus dados sempre em segurança.',
    ),
);
?>

<section class="vl-beneficios" aria-label="<?php esc_attr_e( 'Nossos diferenciais', 'viva-leve-child' ); ?>">
    <div class="vl-beneficios-inner">
        <?php foreach ( $beneficios as $item ) : ?>
        <div class="vl-beneficio">
            <span class="vl-beneficio-icone"><?php echo $item['icone']; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
            <div class="vl-beneficio-texto">
                <strong class="vl-beneficio-titulo"><?php echo esc_html( $item['titulo'] ); ?></strong>
                <span class="vl-beneficio-descricao"><?php echo esc_html( $item['descricao'] ); ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
