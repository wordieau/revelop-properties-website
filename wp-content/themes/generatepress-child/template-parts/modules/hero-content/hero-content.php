<?php
/**
 * template-parts/modules/hero-content/hero-content.php
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$args      = $args ?? [];
$prefix    = $args['prefix'] ?? '';
$row_index = $args['row_index'] ?? 0;

if ( ! get_sub_field( 'module_enable' ) ) {
    return;
}

$content = get_sub_field('contents_layout');

// Safety check
if ( ! $content ) {
    return;
}

// === Fields ===
$top_texts = $content['hero_content_top_texts'] ?? '';
$heading_tag  = $content['hero_content_heading_tag'] ?: 'h2';
$title        = $content['hero_content_title'] ?? '';
$descriptions = $content['hero_content_descriptions'] ?? '';
$bg_color = $content['hero_content_bg_color'] ?? '';
$footer_img = $content['hero_content_footer_image'] ?? '';
$wider = $content['hero_content_wider_width'] ? 'maxw-977' : 'maxw-730';

if ( $title || $descriptions ) : ?>
<section class="hero-content-section hero-content-<?php echo esc_attr( $row_index ); ?>" style="background-color:<?php echo $bg_color; ?>">
    <div class="container display-flex flex-direction-column gap-5">
        <div class="<?php echo esc_html($wider); ?> display-flex flex-direction-column gap-5">

        <?php if($top_texts || $title) : ?>
        <div class="display-flex flex-direction-column gap-0x">
        <?php if ( $top_texts ) : ?>
            <span class="top-texts">
                <?php echo esc_html( $top_texts ); ?>
            </span>
        <?php endif; ?>

        <?php if ( $title ) : ?>
            <<?php echo esc_attr( $heading_tag ); ?> class="hero-content__title no-margin-bottom">
                <?php echo wp_kses_post( $title ); ?>
            </<?php echo esc_attr( $heading_tag ); ?>>
        <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php if ( $descriptions ) : ?>
            <div class="hero-content__descriptions">
                <?php echo wp_kses_post( $descriptions ); ?>
            </div>
        <?php endif; ?>
        </div>
        
        <?php if ( $footer_img ) : ?>
        <div class="maxw-404">
            <img src="<?php echo $footer_img['url'] ?>"></a>
        </div>
        <?php endif; ?>

    </div>
</section>
<?php endif; ?>