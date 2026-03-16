<?php
/**
 * Global Module: Carousel - MODULE
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


$top_text        = $content['carousel_top_text'] ?? '';
$title           = $content['carousel_title'] ?? '';
$description     = $content['carousel_description'] ?? '';
$button          = $content['carousel_button'] ?? false;
$selected_posts  = $content['carousel_select_post_type'] ?? [];

$columns_per_row = $content['carousel_column_per_row'];
if ( ! $selected_posts ) {
    return;
}

?>

<section class="module-services-carousel">
    <div class="container display-flex flex-direction-column gap-8 overflow-hidden">
        <div class="display-flex flex-direction-row items-end justify-space-between flex-wrap gap-1x">
            <div class="display-flex flex-direction-column gap-5">
                <div class="display-flex flex-direction-column gap-3">
                <?php if ( $top_text ) : ?>
                    <p class="top-texts no-margin-bottom"><?php echo esc_html( $top_text ); ?></p>
                <?php endif; ?>

                <?php if ( $title ) : ?>
                    <h2 class="no-margin-bottom"><?php echo wp_kses_post( $title ); ?></h2>
                <?php endif; ?>
                </div>

                <div >
                <?php if ( $description ) : ?>
                    <p class="no-margin-bottom"><?php echo wp_kses_post( $description ); ?></p>
                <?php endif; ?>
                </div>
            </div>

            <div>
            <?php if ($button) : ?>
                <a class="explore-all-services-btn text-decoration-none icon-arrow" href="<?php echo esc_url( $button['url'] ); ?>">
               <?php echo esc_html( $button['title'] ); ?>
                </a>
            <?php endif; ?>
            </div>
        </div>

        <!-- Splide carousel -->
        <div class="splide services-carousel" data-splide='{"type":"loop"}' aria-label="Services carousel">
            <div class="splide__track">
                <ul class="splide__list">

                    <?php foreach ( $selected_posts as $post ) : ?>
                        <?php
                        $item_title   = get_field( 'post_type_title', $post->ID ) ?: get_the_title( $post );
                      
                        $item_excerpt = get_field( 'post_type_excerpt', $post->ID );
                        $item_image   = get_field( 'post_type_image', $post->ID );

                        // Handle image safely
                        $img_url = '';
                        if ( is_array( $item_image ) ) {
                            $img_url = $item_image['url'];
                        } elseif ( is_numeric( $item_image ) ) {
                            $img_url = wp_get_attachment_image_url( $item_image, 'large' );
                        } elseif ( $item_image ) {
                            $img_url = $item_image;
                        }

                        if ( ! $img_url ) {
                            continue;
                        }
                        ?>

                        <li class="splide__slide">
                    <!--<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="text-decoration-none simple-btn">-->
                            <a href="/services/" class="text-decoration-none simple-btn">
                                <div class="service-card img-border-radius" style="height: 378px; background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.50) 100%), url('<?php echo esc_url( $img_url ); ?>') center/cover no-repeat;">
                                    <div class="display-flex flex-direction-column gap-1x">
                                        <h3 class="text-white no-margin-bottom"><?php echo esc_html( $item_title ); ?></h3>
                                        <?php if ( $item_excerpt ) : ?>
                                            <div class="service-card-sub-text text-decoration-none text-white"><?php echo wp_kses_post( $item_excerpt ); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>

                        </li>

                    <?php endforeach; wp_reset_postdata(); ?>

                </ul>
            </div>
        </div>

    </div>

    <!-- Splide init – pixel-based peek (reliable even with few slides) -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        new Splide('.module-services-carousel .splide', {
            type        : 'loop',
            perPage     : <?php echo $columns_per_row; ?>,
            perMove     : 1,
            gap         : '24px',
            arrows      : true,
            pagination  : false,
            drag        : true,
            rewind      : true,

            // THESE 3 LINES ARE THE MAGIC
            trimSpace   : false,     // Don't cut off the last card
            focus       : 0,         // Left-align slides
            padding     : { right: '20%' },  // This creates the intentional half-visible last card

            breakpoints: {
                1200: {
                    perPage: 2,
                    padding: { right: '22%' },
                    gap: '2.5rem'
                },
                992: {
                    perPage: 2,
                    padding: { right: '8%' },
                    gap: '2rem'
                },
                768: {
                    perPage: 2,
                    padding: { right: '10%' },
                    gap: '1.5rem'
                },
                576: {
                    perPage: 1,
                    padding: { right: '10%' },   // mobile: more peek
                    gap: '1rem'
                }
            }
        }).mount();
    });
    </script>
</section>