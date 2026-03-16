<?php
/**
 * Module: Map Interactive
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$args      = $args ?? [];
$prefix    = $args['prefix'] ?? '';
$row_index = $args['row_index'] ?? 0;

// Optional: disable module via checkbox
if ( ! get_sub_field( 'module_enable' ) ) {
    return;
}

// Get the cloned field group (or direct fields if not cloned)
$content = get_sub_field( 'contents_layout' );

if ( ! $content ) {
    return;
}

$map_static = $content['map_static'] ?? '';
$map_embed = $content['embed_map'] ?? '';

?>

<?php if($map_static || $map_embed) : ?>
<section class="map-interactive">
    <div>


        <?php if($map_static && !$map_embed) :?>
            <img src="<?php echo $map_static['url'] ?>" style="width:100%;height:100%;">
        <?php endif; ?>

        <?php if($map_embed) :?>
            <?php echo $map_embed ?>
        <?php endif; ?>
    </div>
</secion>
<?php endif; ?>