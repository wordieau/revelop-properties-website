<?php
error_log('functions.php loaded at ' . date('Y-m-d H:i:s'));
/**
 * GeneratePress child theme functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Load parent theme stylesheet correctly
add_action( 'wp_enqueue_scripts', 'generatepress_child_enqueue_styles', 20 );
function generatepress_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    
    // Optional: Load child theme's own style.css
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( 'parent-style' ) );

    wp_enqueue_script(
        'custom-script',
        get_stylesheet_directory_uri() . '/assets/js/custom.js',
        ['jquery'],
        '1.0.0',
        true
    );
    
    // Optional: Remove GeneratePress Google Fonts (for performance)
    // wp_dequeue_style( 'generatepress-fonts' );
}

/* =============================================
   Your custom PHP code below this line
   ============================================= */



   function enqueue_local_splide() {
    // Local Splide CSS
    wp_enqueue_style(
        'splide',
        get_stylesheet_directory_uri() . '/assets/dist/splide.min.css',
        [],
        '4.1.4' // or filemtime for cache busting - see bonus below
    );

    // Local Splide JS (loads in footer)
    wp_enqueue_script(
        'splide',
        get_stylesheet_directory_uri() . '/assets/dist/splide.min.js',
        [],
        '4.1.4',
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueue_local_splide');

/**
 * ONLY detects if we should use dark header
 * Does NOT interfere with banner rendering
 */


add_filter( 'acf/load_field/name=wd_nav_menus', 'wd_nav_menus_load' );
function wd_nav_menus_load( $field ) {

     $menus = wp_get_nav_menus();

     if ( ! empty( $menus ) ) {

          foreach ( $menus as $menu ) {
               $field['choices'][ $menu->slug ] = $menu->name;
          }

     }

     return $field;

}

// Enqueue Posts Loop AJAX script – works with your exact folder structure
add_action( 'wp_enqueue_scripts', 'posts_loop_enqueue_ajax' );
function posts_loop_enqueue_ajax() {
    wp_enqueue_script(
        'posts-loop-ajax',
        get_stylesheet_directory_uri() . '/template-parts/modules/posts-loop/posts-loop-ajax.js', // Adjust path if needed
        ['jquery'],
        '1.1',
        true
    );

    // Pass static data; featured_id is per-module via data attribute
    wp_localize_script( 'posts-loop-ajax', 'postsLoopAjax', [
        'ajax_url'    => admin_url( 'admin-ajax.php' ),
        'nonce'       => wp_create_nonce( 'posts_loop_nonce' ),
        'post_type'   => 'post',
    ] );
}

add_filter( 'gform_submit_button', 'force_gf_submit_as_button', 10, 2 );
function force_gf_submit_as_button( $button, $form ) {
    
    // Get the current button text (e.g. "Send Message")
    $button_text = $form['button']['text'];
    
    // Build a real <button> with all the same classes/attributes Gravity Forms uses
    $new_button = '<button class="gform_button button btn--secondary hide-icon width-max" type="submit" id="gform_submit_button_' . $form['id'] . '">
                     <span class="hide-icon">' . esc_html( $button_text ) . '</span>
                   </button>';

    //Enquiry,
    if ( in_array( $form['id'], array( 3 ) ) ) {
    $new_button = '<button class="gform_button button btn--secondary  margin-auto" type="submit" id="gform_submit_button_' . $form['id'] . '">
                    <span class="">' . esc_html( $button_text ) . '</span>
                    </button>';
    }
    //Contact us,
    if ( in_array( $form['id'], array( 2 ) ) ) {
        $new_button = '<button class="gform_button button btn--secondary" type="submit" id="gform_submit_button_' . $form['id'] . '">
                        <span class="">' . esc_html( $button_text ) . '</span>
                        </button>';
        }
    
    return $new_button;
}

function simple_posts_grid( $args = array() ) {

    // Default values
    $defaults = array(
        'bgcolor'        => '',
        'top_text'       => '',
        'title'          => 'RELATED ARTICLES',
        'texts_center'   => false,
        'show_category'  => true,
        'show_date'      => true,
        'perpage'        => 9,
        'mobile_percolumn' => 1,
        'manual_posts'   => array(),
        'post_type'      => 'post',
        'btn_link'       => '',   
        'btn_text'       => '',// Empty = auto-generate from category
    );

    $args = wp_parse_args( $args, $defaults );

    // Extract values
    $bgcolor       = $args['bgcolor'] ?: ( get_field('posts_loop_fields', 'option')['background_color_post_loop'] ?? '' );
    $top_text      = $args['top_text'] ?: ( get_field('posts_loop_fields', 'option')['top_text_posts_loop'] ?? '' );
    $title         = $args['title'] ?: ( get_field('posts_loop_fields', 'option')['title_posts_loop'] ?? 'RELATED ARTICLES' );
    $texts_center  = $args['texts_center'];
    $show_category = $args['show_category'];
    $show_date     = $args['show_date'];
    $perpage       = intval( $args['perpage'] );
    $manual_posts  = $args['manual_posts'];
    $post_type     = $args['post_type'];
    $btn_link      = $args['btn_link']; // Can be custom URL/slug or empty for auto
    $btn_name      = $args['btn_text'];
    $mobile_percolumn = $args['mobile_percolumn'];
    $text_align = $texts_center ? 'text-center' : '';

    // === Get Posts ===
    $posts_to_show = array();

    if ( ! empty( $manual_posts ) && is_array( $manual_posts ) ) {
        foreach ( $manual_posts as $p ) {
            if ( is_object( $p ) && isset( $p->ID ) && $p->post_status === 'publish' ) {
                $posts_to_show[] = $p;
            }
        }
    } elseif ( $post_type ) {
        $posts_to_show = get_posts( array(
            'post_type'      => $post_type,
            'posts_per_page' => $perpage,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );
    }

    if ( empty( $posts_to_show ) ) {
        $posts_to_show = get_posts( array(
            'post_type'      => 'post',
            'posts_per_page' => $perpage,
            'post_status'    => 'publish',
        ) );
    }

    // Exclude current post on single pages
    if ( is_singular() && ! empty( $posts_to_show ) ) {
        $current_id = get_the_ID();
        $posts_to_show = array_filter( $posts_to_show, function( $post ) use ( $current_id ) {
            return $post->ID !== $current_id;
        } );
    }

    if ( empty( $posts_to_show ) ) return;

    // === Determine "See All" button link and text ===
    $view_all_link = '';
    $view_all_text = '';

    if ( is_singular() ) {
        $category = false;

        // Yoast primary category
        if ( function_exists( 'yoast_get_primary_term_id' ) ) {
            $primary_id = yoast_get_primary_term_id( 'category', get_the_ID() );
            if ( $primary_id ) {
                $category = get_term( $primary_id, 'category' );
            }
        }

        // Rank Math fallback
        if ( ! $category ) {
            $primary_id = get_post_meta( get_the_ID(), 'rank_math_primary_category', true );
            if ( $primary_id ) {
                $category = get_term( $primary_id, 'category' );
            }
        }

        // Final fallback: first category
        if ( ! $category ) {
            $categories = get_the_category();
            if ( ! empty( $categories ) ) {
                $category = $categories[0];
            }
        }

        if ( $category && ! is_wp_error( $category ) ) {
            //$cat_link = get_category_link( $category->term_id );
            //$cat_name = esc_html( $category->name );

            // Use custom btn_link if provided, otherwise use category link
            //$view_all_link = ! empty( $btn_link ) ? home_url( trim( $btn_link, '/' ) . '/' ) : $cat_link;
            $view_all_link = ! empty( $btn_link ) ? home_url( trim( $btn_link, '/' ) . '/' ) : '';
            //$view_all_text = "See All {$cat_name}";
            $view_all_text = $btn_name;
        }
    }
    ?>

    <section class="simple-posts-loop" style="background-color:<?php echo esc_attr( $bgcolor ); ?>">
        <div class="container display-flex flex-direction-column gap-8">

            <!-- Header -->
            <?php if ( $top_text || $title || ! empty( $view_all_link ) ): ?>
                <div class="section-header <?php echo esc_attr( $text_align ); ?> display-flex flex-direction-row justify-space-between items-center flex-wrap gap-8">

                  

                    <div class="display-flex flex-direction-column gap-5x">
                        <?php if ( $top_text ): ?>
                            <span class="top-texts"><?php echo esc_html( $top_text ); ?></span>
                        <?php endif; ?>
                        <?php if ( $title ): ?>
                            <h2 class="no-margin-bottom"><?php echo wp_kses_post( $title ); ?></h2>
                        <?php endif; ?>
                    </div>

                    <?php if ( ! empty( $view_all_link ) ): ?>
                        <a href="<?php echo esc_url( $view_all_link ); ?>" class="btn--primary text-white text-decoration-none">
                            <?php echo esc_html( $view_all_text ); ?>
                        </a>
                    <?php endif; ?>

                </div>
            <?php endif; ?>

            <!-- Grid -->
            <div class="grid columns-4 gap-3 row-gap-8 md-columns-2 mobile-columns-<?php echo $mobile_percolumn; ?>">
                <?php foreach ( $posts_to_show as $post ): setup_postdata( $post ); ?>
                    <?php
                    $img     = get_field( 'post_type_image', $post->ID );
                    $img_url = is_array( $img ) ? $img['url'] : get_the_post_thumbnail_url( $post->ID, 'large' );
                    $img_url = $img_url ?: 'https://via.placeholder.com/600x400';

                    $title   = get_field( 'post_type_title', $post->ID ) ?: get_the_title();
                    $excerpt = get_field( 'post_type_excerpt', $post->ID ) ?: wp_trim_words( get_the_content(), 30 );
                    $link    = get_permalink( $post->ID );

                    $enable_date = get_field( 'enable_date_post_type', $post->ID );
                    $start_raw   = get_field( 'date_events_start', $post->ID );
                    $end_raw     = get_field( 'date_events_end', $post->ID );

                    // Safely convert date format d/m/Y → Y-m-d for strtotime
                    $start = $start_raw ? DateTime::createFromFormat( 'd/m/Y', $start_raw ) : false;
                    $start = $start ? $start->format( 'Y-m-d' ) : false;

                    $end = $end_raw ? DateTime::createFromFormat( 'd/m/Y', $end_raw ) : false;
                    $end = $end ? $end->format( 'Y-m-d' ) : false;
                    ?>

                    <article class="<?php echo $post_type === 'listing' ? 'listing-item' : ''; ?>">
                        <a href="<?php echo esc_url( $link ); ?>" class="display-flex flex-direction-column gap-4 text-decoration-none height-100">
                            <div class="overflow-hidden posts-loop-img rounded">
                                <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" class="w-100 transition hover:scale-105">
                            </div>

                            <div class="texts-info display-flex flex-direction-column gap-5">
                                <div class="display-flex flex-direction-column gap-3">
                                    <?php if ( $start && $enable_date ): ?>
                                        <span class="date-badge no-margin-bottom width-fit">
                                            <?php echo date_i18n( 'd M Y', strtotime( $start ) ); ?>
                                            <?php if ( $end && $end !== $start ): ?>
                                                – <?php echo date_i18n( 'd M Y', strtotime( $end ) ); ?>
                                            <?php endif; ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    // Only show tag if post type is 'listing'
                                    if ( $post->post_type === 'listing' ) {
                                        $tag_terms = get_the_terms( $post->ID, 'post_tag' );
                                        if ( $tag_terms && ! is_wp_error( $tag_terms ) ) {
                                            $first_tag = $tag_terms[0]; // Get the first tag
                                            echo '<div class="post-tag top-texts">' . esc_html( $first_tag->name ) . '</div>';
                                        }
                                    }
                                    ?>

                                    <h4 class="no-margin-bottom size-24"><?php echo esc_html( $title ); ?></h4>

                                    <?php if($excerpt) : ?>
                                        <span class="text-black"><?php echo wp_kses_post( $excerpt ); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if(!$post_type === 'listing') : ?>
                                <div class="margin-top-auto">
                                    <span class="btn-alt display-flex gap-1">Read more</span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </a>
                    </article>
                <?php endforeach; wp_reset_postdata(); ?>
            </div>
        </div>
    </section>

    <style>
        .simple-posts-loop { padding: 60px 0; }
        .simple-posts-loop img { transition: transform .4s ease; width: 100%; }
        .simple-posts-loop article:hover img { transform: scale(1.05); }
        .simple-posts-loop article { transition: transform .3s ease; }
        .simple-posts-loop article:hover { transform: translateY(-8px); }
        @media(max-width:768px) { .simple-posts-loop article:hover { transform: none; } }
    </style>

    <?php
}

add_action( 'wp_ajax_posts_loop_filter', 'posts_loop_filter_handler' );
add_action( 'wp_ajax_nopriv_posts_loop_filter', 'posts_loop_filter_handler' );
function posts_loop_filter_handler() {
    check_ajax_referer( 'posts_loop_nonce', 'nonce' );

    $category = sanitize_text_field( $_POST['category'] ?? 'all' );
    $search   = sanitize_text_field( $_POST['search'] ?? '' );
    $exclude  = intval( $_POST['exclude'] ?? 0 );
    $post_type = sanitize_text_field( $_POST['post_type'] ?? 'post' ); // Defaults to 'post'

    $args = [
        // 'post_type'      => $post_type,
        'post_type'=>'post',
        'posts_per_page' => 12, // Match your initial query; adjust if needed
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    if ( $category !== 'all' ) {
        $args['category_name'] = $category; // Or use 'tax_query' if needed for custom taxonomies
    }

    if ( ! empty( $search ) ) {
        $args['s'] = $search;
    }

    if ( $exclude > 0 ) {
        $args['post__not_in'] = [ $exclude ];
    }

    $posts = get_posts( $args );

    if ( empty( $posts ) ) {
        echo '<p>No posts found.</p>';
        wp_die();
    }

    foreach ( $posts as $post ) {
        setup_postdata( $post );
        $img     = get_field( 'post_type_image', $post );
        $title   = get_field( 'post_type_title', $post ) ?: get_the_title();
        $excerpt = get_field( 'post_type_excerpt', $post ) ?: wp_trim_words( get_the_content(), 35 );
        $img_url = is_array( $img ) ? $img['url'] : get_the_post_thumbnail_url( $post, 'large' );
        $img_url = $img_url ?: 'https://via.placeholder.com/800x600';
        $link    = get_permalink();
        $date    = get_the_date( 'F j, Y' );

        $terms    = get_the_terms( $post, 'category' );
        $cat      = $terms && ! is_wp_error( $terms ) ? $terms[0]->name : '';
        $cat_slug = $terms && ! is_wp_error( $terms ) ? $terms[0]->slug : '';

        // Output the article HTML (matches posts-loop.php)
        ?>
        <article data-category="<?php echo esc_attr( $cat_slug ); ?>">
            <a href="<?php echo esc_url( $link ); ?>" class="display-flex flex-direction-column gap-5 text-decoration-none height-100">
                <div class="overflow-hidden posts-loop-img rounded">
                    <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
                </div>

                <div class="display-flex flex-direction-column gap-3">
                    <div class="display-flex flex-direction-row gap-5x items-center">
                        <?php if ( $cat ): ?>
                            <span class="post-loop-category green-text-color-v1 size-12 text-upper spacing-1x2 font-auxpro-medium">
                                <?php echo esc_html( $cat ); ?>
                            </span>
                        <?php endif; ?>
                        <span class="top-texts"><?php echo esc_html( $date ); ?></span>
                    </div>

                    <h3 class="no-margin-bottom alt-text-color size-24"><?php echo esc_html( $title ); ?></h3>
                    <div class="no-margin-bottom-outer text-black"><?php echo wp_kses_post( $excerpt ); ?></div>
                </div>

                <div class="margin-top-auto">
                    <span class="alt-text-color btn-alt font-auxpro-bold">Read more</span>
                </div>
            </a>
        </article>
        <?php
    }
    wp_reset_postdata();
    wp_die();
}


//Breadcrumbs

function my_theme_breadcrumbs(
    $section_title = 'What\'s On',
    $section_url = '',
    $separator = ' / ',
    $home_title = 'Home'
) {
    // Default section URL if not provided
    if (empty($section_url)) {
        $section_url = home_url('/whats-on/'); // Change this if your default section has a different slug
    }

    $breadcrumbs = array();

    // 1. Home
    $breadcrumbs[] = '<a href="' . home_url('/') . '">' . esc_html($home_title) . '</a>';

    // 2. Middle section (e.g., What's On) - always shown
    $breadcrumbs[] = '<a href="' . esc_url($section_url) . '">' . esc_html($section_title) . '</a>';

    // 3. Dynamic levels before current (e.g., category for singles, parents for pages)
    if (is_single() && !is_page()) {
        // For posts or custom post types: show primary category if available
        $categories = get_the_category();
        if (!empty($categories)) {
            $breadcrumbs[] = '<a href="' . get_category_link($categories[0]->term_id) . '">'
                . esc_html($categories[0]->name) . '</a>';
        }
        // Or use a custom taxonomy if your events use one (e.g., 'event_type')
        // Example:
        // $terms = get_the_terms(get_the_ID(), 'event_type');
        // if ($terms && !is_wp_error($terms)) {
        //     $breadcrumbs[] = '<a href="' . get_term_link($terms[0]) . '">' . esc_html($terms[0]->name) . '</a>';
        // }
    } elseif (is_page() && !is_front_page()) {
        global $post;
        if ($post->post_parent) {
            // Add parent pages
            $parent_id = $post->post_parent;
            $parent_crumbs = array();
            while ($parent_id) {
                $page = get_post($parent_id);
                $parent_crumbs[] = '<a href="' . get_permalink($page->ID) . '">' . esc_html(get_the_title($page->ID)) . '</a>';
                $parent_id = $page->post_parent;
            }
            $breadcrumbs = array_merge($breadcrumbs, array_reverse($parent_crumbs));
        }
    }

    // 4. Current page/post/archive title (non-clickable) - only if not already added
    $current_added = false;
    if (is_singular()) {
        $breadcrumbs[] = get_the_title();
        $current_added = true;
    } elseif (is_category() || is_tag() || is_tax()) {
        $breadcrumbs[] = single_term_title('', false);
        $current_added = true;
    } elseif (is_archive()) {
        // Fallback for other archives (e.g., date, author) - remove prefix if needed
        $archive_title = get_the_archive_title();
        $archive_title = preg_replace('/^\w+:\s*/', '', $archive_title); // Strip "Category: ", etc.
        $breadcrumbs[] = $archive_title;
        $current_added = true;
    } elseif (is_search()) {
        $breadcrumbs[] = __('Search results for: ') . '"' . get_search_query() . '"';
        $current_added = true;
    } elseif (is_404()) {
        $breadcrumbs[] = __('Page Not Found');
        $current_added = true;
    }

    // Make the last item non-clickable (if a current was added)
    if ($current_added && !empty($breadcrumbs)) {
        $last = array_pop($breadcrumbs);
        $breadcrumbs[] = '<span class="current">' . strip_tags($last) . '</span>';
    }

    // Output
    echo '<nav class="breadcrumbs" aria-label="breadcrumb">';
    echo implode($separator, $breadcrumbs);
    echo '</nav>';
}


function enqueue_local_color_thief() {
    // Enqueue the local Color Thief script
    wp_enqueue_script(
        'color-thief', 
        get_stylesheet_directory_uri() . '/assets/thief-color/thief-color.js', 
        array(), 
        '2.6.0', // Optional: version number (latest as of now)
        true     // Load in footer
    );

    // Add our custom script that uses Color Thief
    wp_add_inline_script('color-thief', '
        document.addEventListener("DOMContentLoaded", function() {
        
            const img = document.querySelector(".featured-img img");
            if (!img) return;

            // Wait for image to load
            if (img.complete && img.naturalHeight !== 0) {
                applyDominantColor(img);
            } else {
                img.addEventListener("load", function() {
                    applyDominantColor(img);
                });
            }

            function applyDominantColor(image) {
                if (typeof ColorThief === "undefined") {
                    console.error("ColorThief not loaded");
                    return;
                }
                const colorThief = new ColorThief();
                const dominant = colorThief.getColor(image, 2); // 10 = quality, higher = more accurate but slower

                const rgb = "rgb(" + dominant[0] + "," + dominant[1] + "," + dominant[2] + ")";

                // Apply to the container background
                image.closest(".featured-img").style.backgroundColor = rgb;

                // Optional tweaks:
                // Make it softer: rgba with low alpha
                // image.closest(".featured-img").style.backgroundColor = "rgba(" + dominant[0] + "," + dominant[1] + "," + dominant[2] + ", 0.4)";
                
                // Or use a color from palette (e.g., 2nd most vibrant)
                // const palette = colorThief.getPalette(image, 2);
                // const rgb = "rgb(" + palette[1][0] + "," + palette[1][1] + "," + palette[1][2] + ")";
            }
        });
    ');
}
add_action('wp_enqueue_scripts', 'enqueue_local_color_thief');

// AJAX handler for loading more listings
add_action('wp_ajax_load_more_listings', 'load_more_listings_callback');
add_action('wp_ajax_nopriv_load_more_listings', 'load_more_listings_callback');

function load_more_listings_callback() {
    // REMOVED this line → it was causing the 403 error
    // check_ajax_referer('load_more_listings', '_ajax_nonce');

    $page = intval($_POST['page']);
    $posts_per_page = intval($_POST['posts_per_page']);

    $args = array(
        'post_type'      => 'listing',
        'post_status'    => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged'          => $page,
        'orderby'        => 'title',
        'order'          => 'ASC',
    );

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            $custom_image = get_field('post_type_image');
            $custom_title = get_field('post_type_title');
            $title = !empty($custom_title) ? $custom_title : get_the_title();

            $img_url = $custom_image
                ? (is_array($custom_image) ? $custom_image['url'] : $custom_image)
                : (has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : '');

            $tag_terms = get_the_terms(get_the_ID(), 'post_tag');
            $tag_slug = $tag_terms && !is_wp_error($tag_terms) ? $tag_terms[0]->slug : '';
            $tag_name = $tag_terms && !is_wp_error($tag_terms) ? $tag_terms[0]->name : '';

            $first_char = strtoupper(mb_substr($title, 0, 1));
            if (!ctype_alpha($first_char)) $first_char = 'num';
            ?>



            <div class="shop-item"
                data-tag="<?php echo esc_attr($tag_slug); ?>"
                data-title="<?php echo esc_attr(strtolower($title)); ?>"
                data-letter="<?php echo esc_attr($first_char); ?>">
                <a href="<?php echo esc_url( get_permalink() ); ?>" class="shop-card-link text-decoration-none">
                    <div class="shop-card display-flex flex-direction-column justify-end">
                        <?php if ($img_url) : ?>
                            <div class="shop-logo">
                                <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
                            </div>
                        <?php endif; ?>
                        <div class="shop-details display-flex flex-direction-column justify-center gap-2">
                            <?php if ($tag_name) : ?>
                                <p class="shop-tag no-margin-bottom"><?php echo esc_html($tag_name); ?></p>
                            <?php endif; ?>

                            <h3 class="shop-title"><?php echo esc_html($title); ?></h3>
                        </div>
                    </div>
                </a>
            </div>

            <?php
        endwhile;
    endif;

    wp_reset_postdata();

    $html = ob_get_clean();

    wp_send_json_success(array('html' => $html));
}