<?php
/**
 * Module: Acknowledgement
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$args      = $args ?? [];
$prefix    = $args['prefix'] ?? '';
$row_index = $args['row_index'] ?? 0;

if ( ! get_sub_field( 'module_enable' ) ) {
    return;
}

$content = get_sub_field('contents_layout');

$enable_global = get_sub_field( 'use_global' );

if ( $enable_global ) {
    $enable_site =  get_sub_field( 'enable_ack','module' );
    $content = get_field('site_settings_acknowledgement','option');
    
}

$bgcolor = $content['bgcolor_ack'] ? 'background-color:'.$content['bgcolor_ack'] : '';
$title = $content['title_ack'] ?? '';
$sub_texts = $content['sub_texts_ack'] ?? '';

?>
<?php if($enable_global || $enable_site) : ?>
<section class="acknowledgement-section" style="<?php echo $bgcolor; ?>">
    <div class="container display-flex flex-direction-column justify-center items-center text-center text-blue gap-1">
    <?php if($title) : ?>
        <span class="weight-700"><?php echo esc_html($title); ?></span>
        <span><?php echo wp_kses_post($sub_texts); ?></span>
    <?php endif; ?>
    </div>
</section>
<?php endif; ?>