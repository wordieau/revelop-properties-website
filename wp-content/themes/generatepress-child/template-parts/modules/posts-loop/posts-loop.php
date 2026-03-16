<?php
/**
 * Posts Loop Module - Supports up to 2 Featured Posts
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$layout = get_sub_field( 'contents_layout' );
if ( ! is_array( $layout ) ) return;

$bgcolor          = $layout['background_color_post_loop'] ? 'background-color:'.$layout['background_color_post_loop'] :';' ;
$top_text         = $layout['top_text_posts_loop'] ?? '';
$title            = $layout['title_posts_loop'] ?? '';
$title_featured   = $layout['title_featured_posts_loop'] ?? '';
$by_post_type     = ! empty( $layout['by_post_type'] );
$select_post_type = $layout['select_post_type'] ?? '';
$manual_posts     = $layout['posts_loop'] ?? [];
$featured_post_raw = $layout['featured_post'] ?? []; // Relationship field: array of posts/IDs
$enable_search    = ! empty( $layout['enable_search_field'] );
$enable_filters   = ! empty( $layout['enable_filters'] );
$texts_center     = ! empty( $layout['texts_center'] );
$show_category    = empty( $layout['show_category'] ) ? true : ! empty( $layout['show_category'] );
$show_date        = empty( $layout['show_date'] ) ? true : ! empty( $layout['show_date'] );

$desktop_columns = $layout['desktop_columns_post_loop'] ?? 3;
$tablet_columns = $layout['tablet_columns_post_loop'] ?? 2;
$mobile_columns = $layout['mobile_columns_post_loop'] ?? 1;

$posts_to_show = [];
$featured_posts = [];
$featured_ids = [];

$text_alignment = $layout['enable_style_type_2'] ? '' : 'text-center';
$gap_num = $layout['enable_style_type_2'] ? '40' : '16';
$items_alignment = $layout['enable_style_type_2'] ? '' : 'items-center';
$justify_alignment = $layout['enable_style_type_2'] ? 'justify-center' : '';
$enable_alt_style = $layout['enable_style_type_2'] ? 'alt-style' : '';
$flex_direction = $layout['enable_style_type_2'] ? 'flex-direction-row-reverse' : 'flex-direction-column';
$heading_type = $layout['enable_style_type_2'] ? 'h4' : 'h2';
// Process featured posts (up to 2)
if ( ! empty( $featured_post_raw ) ) {
    $items = is_array( $featured_post_raw ) ? $featured_post_raw : [ $featured_post_raw ];

    foreach ( $items as $item ) {
        if ( count( $featured_posts ) >= 2 ) break; // Limit to 2

        $post_obj = null;

        if ( is_object( $item ) && isset( $item->ID ) ) {
            $post_obj = $item;
        } elseif ( is_numeric( $item ) ) {
            $post_obj = get_post( (int) $item );
        }

        if ( $post_obj && $post_obj->post_status === 'publish' ) {
            $featured_posts[] = $post_obj;
            $featured_ids[] = $post_obj->ID;
        }
    }
}

// Fetch main posts by post type
if ( $by_post_type && $select_post_type ) {

    $post_types = array();

    // Ensure it's an array (ACF can return string if single select)
    if ( ! is_array( $select_post_type ) ) {
        $select_post_type = array( $select_post_type );
    }

    foreach ( $select_post_type as $choice ) {
        switch ( $choice ) {
            case 'posts':
                $post_types[] = 'post';
                break;
            case 'offer':
                $post_types[] = 'offer';  
                break;
            case 'listing':
                $post_types[] = 'listing';
                break;
            case 'event':
                $post_types[] = 'event';
                break;
            
        }
    }


    $post_types = array_unique( $post_types );

    if ( ! empty( $post_types ) ) {
        $posts_to_show = get_posts( array(
            'post_type'      => $post_types,
            'posts_per_page' => 12,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );
    }
}

// Fallback
if ( empty( $posts_to_show ) ) {
    $posts_to_show = get_posts( [
        'post_type'      => 'post',
        'posts_per_page' => 9,
        'post_status'    => 'publish',
    ] );
}

// Exclude featured posts from the grid
if ( ! empty( $featured_ids ) ) {
    $posts_to_show = array_filter( $posts_to_show, function( $post ) use ( $featured_ids ) {
        return is_object( $post ) && ! in_array( $post->ID, $featured_ids );
    } );
}

if ( empty( $posts_to_show ) && empty( $featured_posts ) ) return;

$categories = get_categories( ['hide_empty' => true, 'orderby' => 'name'] );
$text_align = $texts_center ? 'text-center' : '';
$featured_count = count( $featured_posts );
?>

<section 
    class="posts-loop" 
    style="<?php echo esc_attr( $bgcolor ); ?>"
    data-featured-ids="<?php echo esc_attr( implode( ',', $featured_ids ) ); ?>">
    <div class="display-flex flex-direction-column gap-8">

    <?php if ( $featured_count > 0 ): ?>
        <div class="featured-articles container display-flex flex-direction-column gap-4">
            <h3 class="no-margin-bottom"><?php echo esc_html( $title_featured ); ?></h3>

            <!-- Featured Posts Wrapper -->
            <div class="featured-posts-wrapper display-flex flex-wrap <?php echo $enable_alt_style; ?> gap-8">

                <?php 
                $use_splide = ( $featured_count > 1 && empty( $layout['enable_style_type_2'] ) );

                if ( $use_splide ): 
                ?>
                    <!-- Splide Carousel (only when NOT in alt style and more than 1 post) -->
                    <div class="featured-splide splide">
                        <div class="splide__track">
                            <ul class="splide__list">
                <?php endif; ?>

                <?php foreach ( $featured_posts as $f ): 
                    $f_img     = get_field( 'post_type_image', $f );
                    $f_title   = get_field( 'post_type_title', $f ) ?: get_the_title( $f );
                    $f_excerpt = get_field( 'post_type_excerpt', $f ) ?: wp_trim_words( get_the_content( null, false, $f ), 50 );
                    $f_url     = is_array( $f_img ) ? $f_img['url'] : get_the_post_thumbnail_url( $f, 'large' );
                    $f_url     = $f_url ?: 'https://via.placeholder.com/800x600';
                    $f_link    = get_permalink( $f );

                    $top_texts = get_field('post_type_top_texts', $f->ID);

                    $enable_date = get_field('enable_date_post_type', $f->ID);
                    $start_raw   = get_field( 'date_events_start', $f->ID );
                    $end_raw     = get_field( 'date_events_end', $f->ID );

                    $start = $start_raw ? DateTime::createFromFormat('d/m/Y', $start_raw)->format('Y-m-d') : false;
                    $end   = $end_raw   ? DateTime::createFromFormat('d/m/Y', $end_raw)->format('Y-m-d')   : false;
                ?>
                    <?php if ( $use_splide ): ?>
                        <li class="splide__slide featured-item">
                    <?php else: ?>
                        <div class="featured-item ">
                    <?php endif; ?>

                        <a class="display-block text-decoration-none" href="<?php echo esc_url( $f_link ); ?>">
                            <div class="display-flex <?php echo $flex_direction; ?> gap-6 relative">
                                <div class="featured-image overflow-hidden radius-6" style="background: linear-gradient(180deg, rgba(0, 0, 0, 0.00) 0%, rgba(0, 0, 0, 0.50) 100%), url(<?php echo esc_url( $f_url ); ?>) lightgray 50% / cover no-repeat;">

                                </div>

                                <div class="featured-contents display-flex flex-direction-column gap-5 width-max <?php echo $items_alignment . ' ' . $text_alignment . ' ' . $justify_alignment; ?>">
                                    <?php if ( $start && $enable_date ): ?>
                                        <span class="date-badge no-margin-bottom width-fit">
                                            <?php echo date_i18n( 'd M Y', strtotime( $start ) ); ?>
                                            <?php if ( $end && $end !== $start ): ?>
                                                – <?php echo date_i18n( 'd M Y', strtotime( $end ) ); ?>
                                            <?php endif; ?>
                                        </span>
                                    <?php endif; ?>

                                    <div class="content display-flex flex-direction-column gap-2 width-max">
                                        <?php if ( $top_texts ): ?>
                                            <span class="top-texts"><?php echo esc_html( $top_texts ); ?></span>
                                        <?php endif; ?>
                                        <<?php echo $heading_type; ?> class="featured-title text-white no-margin-bottom">
                                            <?php echo esc_html( $f_title ); ?>
                                        </<?php echo $heading_type; ?>>
                                    </div>

                                    <?php if ( ! empty( $layout['enable_style_type_2'] ) ): ?>
                                        <span href="<?php echo esc_url( $f_link ); ?>" class="btn-alt text-decoration-none gap-1 display-flex items-center">
                                            Learn More 
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>

                    <?php if ( $use_splide ): ?>
                        </li>
                    <?php else: ?>
                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>

                <?php if ( $use_splide ): ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    <?php endif; ?>

        <div class="container">
             <?php if ( $enable_search ): ?>
                <!-- Search + Filters -->
                <div class="search-filters display-flex flex-direction-row gap-4 <?php echo $text_align; ?> justify-space-between margin-bottom-8">
                    
                        <input type="search" placeholder="Search for an article..." class="post-loop-search-input">
                    <?php endif; ?>

                    <?php if ( $enable_filters && $categories ): ?>
                        <div class="filters display-flex justify-content-center gap-3">
                            <button class="filter-btn active" data-filter="all">All Articles</button>
                            <?php foreach ( $categories as $cat ): ?>
                                <button class="filter-btn" data-filter="<?php echo esc_attr( $cat->slug ); ?>">
                                    <?php echo esc_html( $cat->name ); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                
                </div>
            <?php endif; ?>
            <!-- Header -->
            <?php if ( $top_text || $title && ! empty( $posts_to_show ) ): ?>
                <div class="section-header display-flex flex-direction-column gap-0x <?php echo $text_align; ?> margin-bottom-10">
                    <?php if ( $top_text ): ?>
                        <span class="top-texts"><?php echo esc_html( $top_text ); ?></span>
                    <?php endif; ?>
                    <?php if ( $title ): ?>
                        <h3 class="no-margin-bottom"><?php echo wp_kses_post( $title ); ?></h3>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Grid -->
            <?php if ( ! empty( $posts_to_show ) ): ?>
                <div class="grid columns-<?php echo $desktop_columns; ?> md-columns-<?php echo $tablet_columns; ?> mobile-columns-<?php echo $mobile_columns; ?> gap-3 row-gap-8" id="posts-loop-grid">
                    <?php foreach ( $posts_to_show as $post ): setup_postdata( $post ); ?>
                        <?php
                        $img     = get_field( 'post_type_image', $post );
                        $title   = get_field( 'post_type_title', $post ) ?: get_the_title();
                        $store_name = get_field('store_name_post_type',$post);
                        $excerpt = get_field( 'post_type_excerpt', $post ) ?: wp_trim_words( get_the_content(), 35 );
                        $img_url = is_array( $img ) ? $img['url'] : get_the_post_thumbnail_url( $post, 'large' );
                        
                        $img_url = $img_url ?: 'https://via.placeholder.com/800x600';
                        $link    = get_permalink();
                        $date    = get_the_date( 'F j, Y' );

                        $terms    = get_the_terms( $post, 'category' );
                        $cat      = $terms && ! is_wp_error( $terms ) ? $terms[0]->name : '';
                        $cat_slug = $terms && ! is_wp_error( $terms ) ? $terms[0]->slug : '';

                        $enable_date = get_field('enable_date_post_type',$post->ID);
                        $start = get_field( 'date_events_start', $post->ID );
                        $end   = get_field( 'date_events_end', $post->ID );

                        $start_date = DateTime::createFromFormat('d/m/Y', $start); // $input_start = '14/12/2025'
                        $start = $start_date ? $start_date->format('Y-m-d') : false;

                        $end_date = DateTime::createFromFormat('d/m/Y', $end);
                        $end = $end_date ? $end_date->format('Y-m-d') : false;
                        ?>
                        <article data-category="<?php echo esc_attr( $cat_slug ); ?>">
                            <a href="<?php echo esc_url( $link ); ?>" class="display-flex flex-direction-column gap-4 text-decoration-none height-100">
                                <div class="overflow-hidden posts-loop-img rounded">
                                    <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
                                </div>

                                <div class="display-flex flex-direction-column gap-5">
                                    <div class="display-flex flex-direction-column gap-3">
                                        <?php if ( $start && $enable_date) : ?>
                                            <span class="date-badge no-margin-bottom width-fit">
                                                <?php echo date_i18n( 'd M Y', strtotime( $start ) ); ?>
                                                <?php if ( $end && $end !== $start ) echo ' – ' . date_i18n( 'd M Y', strtotime( $end ) ); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if($store_name) :?>
                                            <span class="top-texts"><?php echo $store_name; ?></span>
                                        <?php endif; ?>
                                        <h4 class="no-margin-bottom size-24"><?php echo esc_html( $title ); ?></h4>
                                        <?php if($excerpt) : ?>
                                        <div class="no-margin-bottom-outer text-black"><?php echo wp_kses_post( $excerpt ); ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="margin-top-auto">
                                        <span class="btn-alt text-decoration-none display-flex gap-1">Read more</span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; wp_reset_postdata(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php 


wp_enqueue_script('posts-loop-ajax', get_stylesheet_directory_uri() . '/js/posts-loop-ajax.js', ['jquery'], '1.0', true);
wp_localize_script('posts-loop-ajax', 'postsLoopAjax', [
    'ajax_url'      => admin_url('admin-ajax.php'),
    'nonce'         => wp_create_nonce('posts_loop_nonce'),
    'featured_ids'  => $featured_ids,
    'post_type'     => 'posts',
]);
?>

<?php 
// Only initialize Splide if we are NOT in alt style AND have more than 1 featured post
if ( $use_splide ): 
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const splideElement = document.querySelector('.featured-splide');
    if (splideElement) {
        new Splide('.featured-splide', {
            type         : 'slide',
            perPage      : 1,
            perMove      : 1,
            gap          : '1rem',
            padding      : { right: '13%' },
            focus        : 'left',
            pagination   : false,
            arrows       : false,
            autoplay     : false,
            drag         : true,
            pauseOnHover : false,
            resetProgress: false,
            mediaQuery   : 'min',
            breakpoints  : {
                769: {
                    perPage : 2,
                    padding : 0,
                    focus   : 'center',
                    drag    : false,
                    gap     : '<?php echo $gap_num; ?>px'
                }
            }
        }).mount();
    }
});
</script>
<?php endif; ?>