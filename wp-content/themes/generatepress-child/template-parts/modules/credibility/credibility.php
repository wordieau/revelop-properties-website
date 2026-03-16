<?php
/**
 * Module: Credibility Bar
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$args      = $args ?? [];
$prefix    = $args['prefix'] ?? '';
$row_index = $args['row_index'] ?? 0;

// Bail if module is disabled
if ( ! get_sub_field( 'module_enable' ) ) {
    return;
}

$content   = get_sub_field( 'contents_layout' );
$title     = $content['cred_title'] ?? 'CERTIFICATIONS & RECOGNITION';
$subtext   = $content['cred_sub_texts'] ?? '';
$gallery   = $content['cred_gallery'] ?? []; 

if ( empty( $gallery ) ) {
    return;
}
?>

<section class="credibility bg-white">
    <div class="container display-flex flex-direction-column gap-8">

        <div class="display-flex flex-direction-column gap-5 maxw-730">
        <?php if ( $title ) : ?>
            <h2 class="text-center no-margin-bottom">
                <?php echo esc_html( $title ); ?>
            </h2>
        <?php endif; ?>


        <?php if ( $subtext ) : ?>
            <div class="size-16 no-margin-bottom-outer">
                <?php echo wp_kses_post(  $subtext  ); ?>
            </div>
        <?php endif; ?>
        </div>

        <div class="credibility-marquee-wrapper">
            <div class="credibility-marquee">
                <?php 
                // Duplicate the gallery once for seamless infinite loop
                $all_images = array_merge($gallery, $gallery);

                foreach ( $all_images as $image ) :
                    $img_url = is_array($image) ? $image['url'] : $image['url'] ?? '';
                    $img_alt = is_array($image) ? ($image['alt'] ?: $image['title']) : ($image['alt'] ?: 'Certification');
                    if ( !$img_url ) continue;
                ?>
                    <div class="credibility-logo-item">
                        <img 
                            src="<?php echo esc_url($img_url); ?>" 
                            alt="<?php echo esc_attr($img_alt); ?>"
                            loading="lazy"
                        >
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</section>