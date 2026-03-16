<?php
/**
 * Module: Social Media Gallery
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$args      = $args ?? [];
$prefix    = $args['prefix'] ?? '';
$row_index = $args['row_index'] ?? 0;

// Bail if module disabled
if ( ! get_sub_field( 'module_enable' ) ) {
    return;
}

// Local fields (from the row)
$content = get_sub_field( 'contents_layout' );

$enable_global = get_sub_field( 'use_global' );
$use_instagram_feed = get_sub_field( 'use_instagram_feed' );

if ( $enable_global ) {
    // Pull from Theme Options (ACF Options Page)
    $content = get_field( 'get_social_fields_global', 'option' );
    
}

// If still no content (safety), bail
if ( ! is_array( $content ) || empty( $content ) ) {
    return;
}

// Extract fields using clone structure
$section_title      = $content['section_title'] ?? "Let's get social!";
$section_subtitle   = $content['section_subtitle'] ?? '';

$facebook_url       = $content['facebook_url'] ?? '';
$instagram_url      = $content['instagram_url'] ?? '';
$tiktok_url         = $content['tiktok_url'] ?? '';

$photos             = $content['photos'] ?? []; // Gallery field
$show_captions      = ! empty( $content['show_captions'] );
$caption_style      = $content['caption_style'] ?? 'pink-circle';
$cta_buttons        = $content['cta_buttons'] ?? []; // Repeater
$hide_button_icon = $content['hide_button_icon_soc_med_gallery'] ? 'hide-icon' : '';

$background_color   = $content['background_color'] ?? '#3b4a8b';
$text_color         = $content['text_color'] ?? '#ffffff';

$columns_desktop = $content['columns_desktop'] ?? 6;
$columns_mobile = $content['columns_mobile'] ?? 2;
// Unique section ID
$section_id = 'get-social-' . $row_index;

// Unique IDs for Splide sliders
$main_id    = 'gallery-main-' . $row_index;
$thumbs_id  = 'gallery-thumbs-' . $row_index;
?>

<section 
    id="<?php echo esc_attr( $section_id ); ?>" 
    class="get-social-section"
    style="background-color: <?php echo esc_attr( $background_color ); ?>; color: <?php echo esc_attr( $text_color ); ?>;"
>
    <div class="container text-center display-flex flex-direction-column gap-8">
        <div class="display-flex flex-direction-column gap-4">
            <div class="display-flex flex-direction-column gap-2">
                <?php if ( $section_subtitle ) : ?>
                    <p class="top-texts no-margin-bottom">
                        <?php echo esc_html( $section_subtitle ); ?>
                    </p>
                <?php endif; ?>

                <?php if ( $section_title ) : ?>
                    <h2 class="text-white no-margin-bottom">
                        <?php echo wp_kses_post( $section_title ); ?>
                    </h2>
                <?php endif; ?>
            </div>
      
            <div class="display-flex flex-direction-row justify-center gap-5 flex-wrap row-gap-2">
                <?php if ( ! empty( $facebook_url ) ) : ?>
                    <a href="<?php echo esc_url( $facebook_url['url'] ); ?>" 
                       target="_blank" 
                       rel="noopener" 
                       class="text-white text-decoration-none display-flex flex-direction-row gap-1x"
                       aria-label="Follow us on Facebook">
                       
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/imgs/facebook.svg" 
                             alt="" 
                             width="48" 
                             height="48" 
                             loading="lazy"
                             class="img-size-28"><?php echo $facebook_url['title'] ?>
                    </a>
                <?php endif; ?>

                <?php if ( ! empty( $instagram_url ) ) : ?>
                    <a href="<?php echo esc_url( $instagram_url['url'] ); ?>" 
                       target="_blank" 
                       rel="noopener" 
                       class="text-white text-decoration-none display-flex flex-direction-row gap-1x"
                       aria-label="Follow us on Instagram">
                       
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/imgs/instagram.svg" 
                             alt="" 
                             width="48" 
                             height="48" 
                             loading="lazy"
                             class="img-size-28"><?php echo $instagram_url['title'] ?>
                    </a>
                <?php endif; ?>
            </div>
        

            <!-- CTA Buttons -->
            <?php if ( ! empty( $cta_buttons ) && is_array( $cta_buttons ) ) : ?>
                <div class="display-flex flex-direction-row gap-2 justify-center">
                    <?php foreach ( $cta_buttons as $btn ) : 
                        $link = $btn['link_social_m_gal'] ?? '';
                        $outline_style = $btn['outline_style_social_m_gal'] ? 'btn-outline-white': '';

                        $url  = $link['url'] ?? '';
                        $title = $link['title'] ?? '';
                        if ( $title && $url ) : ?>
                            <a href="<?php echo esc_url( $url ); ?>" class="btn--secondary text-decoration-none <?php echo $outline_style.' '.$hide_button_icon; ?>">
                                <?php echo esc_html( $title ); ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Used instagram feed instead of photo gallery -->
        <?php if($use_instagram_feed) : ?>
            <section class="insta-feed">
                <?php echo do_shortcode('[instagram-feed feed=1]');?>
            </section>
        <?php else : ?>
            <!-- Photo Gallery - Horizontal Scroll Style with SplideJS -->
            <?php if ( ! empty( $photos ) && is_array( $photos ) ) : ?>
                <section id="<?php echo esc_attr( $main_id ); ?>" class="splide horizontal-gallery-splide" aria-label="Social Media Gallery">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <?php foreach ( $photos as $image ) : 
                                $img_url = $image['sizes']['large'] ?? $image['url'];
                                $alt     = $image['alt'] ?: $image['title'];
                                $caption = $image['caption'] ?? '';
                                ?>
                                <li class="splide__slide">
                                    <div class="splide__item"> <!-- Adjust height here -->
                                        <img 
                                            src="<?php echo esc_url( $img_url ); ?>" 
                                            alt="<?php echo esc_attr( $alt ); ?>"
                                            class="w-full h-full object-cover"
                                            loading="lazy"
                                        >

                                        <?php if ( $show_captions && $caption ) : ?>
                                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                                <?php if ( $caption_style === 'pink-circle' ) : ?>
                                                    <span class="bg-pink-500 text-white px-6 py-3 rounded-full text-base md:text-lg font-semibold shadow-2xl">
                                                        <?php echo wp_kses_post( $caption ); ?>
                                                    </span>
                                                <?php elseif ( $caption_style === 'white-overlay' ) : ?>
                                                    <div class="absolute bottom-0 left-0 right-0 bg-white/95 text-gray-900 p-4 text-center">
                                                        <p class="font-bold"><?php echo wp_kses_post( $caption ); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </section>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php if ( ! empty( $photos ) && is_array( $photos ) ) : ?>
<style>

    #<?php echo esc_attr( $main_id ); ?> img {
        
        transition: transform 0.3s ease;
        height:100%;
        width:100%;
        object-fit: cover;
    }
    #<?php echo esc_attr( $main_id ); ?> .splide__item{height:100%;}
    #<?php echo esc_attr( $main_id ); ?> .splide__slide:hover img {
        transform: scale(1.05);
    }

    /* Responsive number of visible slides */
    @media (max-width: 640px) {
        #<?php echo esc_attr( $main_id ); ?> .splide__slide {
            width: 133px !important; /* ~1.5 visible on mobile */
            height:113px;
        }
    }
    @media (min-width: 641px) and (max-width: 1024px) {
        #<?php echo esc_attr( $main_id ); ?> .splide__slide {
            width: 350px !important; /* ~3-4 visible on tablet */
        }
    }
    @media (min-width: 1025px) {
        #<?php echo esc_attr( $main_id ); ?> .splide__slide {
            width: 188px !important; /* ~5-6 visible on desktop - matches screenshot */
            height:188px;   
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    new Splide('#<?php echo esc_attr( $main_id ); ?>', {
        type       : 'loop',
        drag       : 'free',
        focus      : 'left',
        perPage    : <?php echo $columns_desktop; ?>,        // Base number (desktop)
        perMove    : 1,
        gap        : "20px",
        arrows     : false,
        pagination : false,
        autoScroll : false,    // Remove if you want continuous auto-scroll
        breakpoints: {
            640: {
                perPage: <?php echo $columns_mobile; ?>,
                gap: '20px',
                focus      : 'left',
            },

        }
    }).mount();
});
</script>
<?php endif; ?>