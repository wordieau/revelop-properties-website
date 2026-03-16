<?php
/**
 * Module: Inner Page Banner (Splide.js - dynamic perPage based on slide count)
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$args = $args ?? [];
$prefix = $args['prefix'] ?? '';
$row_index = $args['row_index'] ?? 0;

$content = get_sub_field('contents_layout');

// Safety check
if ( ! $content || empty( $content['inner_page_banner_group'] ) ) {
    return;
}

// Global settings (outside repeater)
$innerpage            = $content['inner_page_toggle'] ?? '';
$texts_buttons_center = ! empty( $content['texts_button_center_page_banner'] ) ? 'margin-auto texts-center' : '';

$innerpage_check = $innerpage ? 'innerpage-active' : '';
$set_width       = $innerpage ? '' : 'maxw-520 contents-left';
$btn_align       = $innerpage ? 'self-center' : '';
$font_size       = $innerpage ? 'size-48' : '';
$height_swap = $innerpage ? 'height-unset' : '';
$fixed_height = '';
// Repeater rows
$banner_rows = $content['inner_page_banner_group'];
$slide_count = count( $banner_rows );

 $dragmode = $slide_count <= 1 ? 'false' : 'true';

// Dynamic perPage = show all slides at once if few, otherwise default behavior
$per_page = $slide_count; // You can cap it if needed, e.g. max 3: $per_page = min($slide_count, 3);

$slider_id = 'inner-page-banner-slider-' . uniqid();
?>

<div class="splide inner-page-banner-slider" id="<?php echo esc_attr( $slider_id ); ?>" aria-label="Inner Page Banner Slider" dragmode="<?php echo $dragmode;    ?>">
    <div class="splide__track">
        <ul class="splide__list">

            <?php foreach ( $banner_rows as $row ) :

                $top_texts       = $row['top_texts_page_banner'] ?? '';
                $text_type_color = $row['inner_page_text_type_color'] ?? '';
                $header_tag      = $row['inner_page_header_tags'] ?? 'h1';
                $title           = $row['inner_page_header_title'] ?? '';
                $description     = $row['inner_page_header_description'] ?? '';

                $show_buttons    = ! empty( $row['inner_page_header_show_button'] );
                $button_1        = $row['inner_page_header_breadcrumb_text_button_1'] ?? false;
                $button_2        = $row['inner_page_header_breadcrumb_text_button_2'] ?? false;

                $btn_icon1 = ! empty( $row['hide_icon_btn_page_banner'] ) ? 'hide-icon' : '';
                $btn_icon2 = ! empty( $row['hide_icon_btn2_page_banner'] ) ? 'hide-icon' : '';

                $show_bg         = ! empty( $row['inner_page_header_show_background_image'] );
                $bg_image        = $row['inner_page_header_background_image'] ?? false;
                $bg_color        = $row['inner_page_background_color'] ?? '';

                $section_mode = ( ( $button_1 || $button_2 ) && ( $top_texts || $description ) ) ? 'page-banner-full-contents' : '';

                /* Fallback title */
                if ( empty( $title ) ) {
                    $title = is_front_page() ? get_bloginfo( 'name' ) : get_the_title();
                }

                /* Background per slide */
                $bg_style = 'background-color:#fff;';
                $heading_color = 'text-black';
               

                if ( $show_bg && $bg_image ) {
                    $bg_url = '';
                    if ( is_array( $bg_image ) && ! empty( $bg_image['url'] ) ) {
                        $bg_url = $bg_image['url'];
                    } elseif ( is_numeric( $bg_image ) ) {
                        $bg_url = wp_get_attachment_image_url( $bg_image, 'full' );
                    }
                    if ( $bg_url ) {
                        $bg_style = 'background: linear-gradient(333deg, rgba(0, 0, 0, 0.00) 16.76%, rgba(0, 0, 0, 0.60) 62.15%), url(' . esc_url( $bg_url ) . ') lightgray 50% / cover no-repeat;';
                        $heading_color = '';
                    }
                    $fixed_height = $innerpage ? 'height:355px' : '';
                } else {
                    // $height_swap = 'height-unset';
                    $bg_style = 'background-color:' . esc_attr( $bg_color ) . ';';
                }

                $heading_color = $text_type_color ?: $heading_color;
                $top_texts_type = $text_type_color === 'text-black' ? '' : 'text-white';
                $desc_texts_type = $text_type_color === 'text-black' ? 'text-grey' : 'text-white';

                ?>

                <li class="splide__slide">
                    <section class="inner-page-banner <?php echo esc_attr( $innerpage_check . ' ' . $section_mode . ' ' . $height_swap ); ?>" style="<?php echo $fixed_height; ?>">
                        <div class="inner-page-banner__gradient" style="<?php echo esc_attr( $bg_style ); ?>"></div>
                        <div class="inner-page-banner__inner container display-flex flex-direction-column gap-2 <?php echo esc_attr( $texts_buttons_center ); ?>">

                            <?php if ( $top_texts ) : ?>
                                <span class="page-banner-top-texts text-upper spacing-1x2 size-12 <?php echo esc_attr( $top_texts_type ); ?> top-texts">
                                    <?php echo esc_html( $top_texts ); ?>
                                </span>
                            <?php endif; ?>

                            <div class="display-flex flex-direction-column gap-8 <?php echo esc_attr( $set_width ); ?>">
                                <div class="display-flex flex-direction-column gap-2">
                                    <<?php echo esc_attr( $header_tag ); ?> class="inner-page-banner__title <?php echo esc_attr( $font_size . ' ' . $heading_color ); ?>">
                                        <?php echo wp_kses_post( $title ); ?>
                                    </<?php echo esc_attr( $header_tag ); ?>>

                                    <?php if ( $description ) : ?>
                                        <div class="inner-page-banner__description no-margin-bottom-outer size-16 <?php echo esc_attr( $desc_texts_type ); ?>">
                                            <?php echo wp_kses_post( $description ); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php if ( $show_buttons && ( $button_1 || $button_2 ) ) : ?>
                                    <div class="inner-page-banner__actions gap-2 display-flex <?php echo esc_attr( $btn_align ); ?>">

                                        <?php if ( $button_1 && ! empty( $button_1['url'] ) ) : ?>
                                            <a href="<?php echo esc_url( $button_1['url'] ); ?>"
                                               class="site-btn banner-btn btn--primary <?php echo esc_attr( $btn_icon1 ); ?>"
                                               <?php echo ! empty( $button_1['target'] ) ? 'target="_blank" rel="noopener"' : ''; ?>>
                                                <?php echo esc_html( $button_1['title'] ); ?>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ( $button_2 && ! empty( $button_2['url'] ) ) : ?>
                                            <a href="<?php echo esc_url( $button_2['url'] ); ?>"
                                               class="site-btn banner-btn btn--secondary <?php echo esc_attr( $btn_icon2 ); ?>"
                                               <?php echo ! empty( $button_2['target'] ) ? 'target="_blank" rel="noopener"' : ''; ?>>
                                                <?php echo esc_html( $button_2['title'] ); ?>
                                            </a>
                                        <?php endif; ?>

                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </section>
                </li>

            <?php endforeach; ?>

        </ul>
    </div>

    <ul class="splide__pagination"></ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Splide('#<?php echo esc_js( $slider_id ); ?>', {
            type         : 'slide',           // Regular sliding (not loop unless many slides)
            perPage      : 1,
            perMove      : 1,
            rewind       : true,
            autoplay     : false,
            pauseOnHover : true,
            arrows       : true,              // Arrows appear automatically if needed
            pagination   : true,              // Dots appear if more slides than perPage
            cover        : true,
            gap          : '2rem',            // Optional: space between slides
            padding      : '0',
            drag         : <?php echo $dragmode; ?>,               // Optional: side padding

        }).mount();
    });
</script>