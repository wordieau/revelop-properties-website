<?php
/**
 * Module: Trading Hours Tabs
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$args      = $args ?? [];
$prefix    = $args['prefix'] ?? '';
$row_index = $args['row_index'] ?? 0;

// Disable module if unchecked
if ( ! get_sub_field( 'module_enable' ) ) {
    return;
}

$content = get_sub_field( 'contents_layout' );

if ( ! $content ) {
    return;
}

$texts_editor = $content['texts_editor'] ?? '';



?>
<section class="texts-editor-section">
    <div class="container">
        <?php echo wp_kses_post($texts_editor);?>
    </div>
</section>