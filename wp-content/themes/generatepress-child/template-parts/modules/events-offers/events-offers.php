<?php
/**
 * Module: Events & Offers
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$args      = $args ?? [];
$prefix    = $args['prefix'] ?? '';
$row_index = $args['row_index'] ?? 0;

if ( ! get_sub_field( 'module_enable' ) ) {
    return;
}

call_user_func( function() {

    $content       = get_sub_field( 'contents_layout' );
    $enable_global = get_sub_field( 'use_global' ) ?: false;

    if ( $enable_global ) {
        $content = get_field( 'card_items_fields', 'option' );
    }

    if ( ! is_array( $content ) ) return;

    // Fields
    $top_text          = $content['top_text_events_offers'] ?? '';
    $title             = $content['title_events_offers'] ?? '';
    $button            = $content['button_events_offers'] ?? [];
    $show_big          = $content['big_featured_image_events_offers'] ?? true; // Keep toggle if you want to disable big entirely
    $featured_posts_raw = $content['big_featured_post'] ?? []; // Now Relationship field (returns array of posts/objects/IDs)
    $bgcolor           = ! empty( $content['background_color_events_offers'] )
        ? 'background-color:' . esc_attr( $content['background_color_events_offers'] ) . ';'
        : '';

    // Helper to get image URL
    $img_url = function( $img, $size = 'large' ) {
        if ( ! $img ) return '';
        if ( is_numeric( $img ) ) return wp_get_attachment_image_url( $img, $size );
        if ( is_array( $img ) ) return $img['sizes'][$size] ?? $img['url'] ?? '';
        return (string) $img;
    };

    // Process the Relationship field – get up to 3 published posts
    $featured_posts = [];

    if ( ! empty( $featured_posts_raw ) ) {
        $items = is_array( $featured_posts_raw ) ? $featured_posts_raw : [ $featured_posts_raw ];

        foreach ( $items as $item ) {
            if ( count( $featured_posts ) >= 3 ) break; // Max 3 items

            $post_obj = null;

            if ( is_object( $item ) && isset( $item->ID ) ) {
                $post_obj = $item;
            } elseif ( is_numeric( $item ) ) {
                $post_obj = get_post( (int) $item );
            }

            if ( $post_obj && $post_obj->post_status === 'publish' ) {
                $featured_posts[] = $post_obj;
            }
        }
    }

    // If no posts selected or less than needed → nothing to show
    if ( empty( $featured_posts ) ) {
        return;
    }

    // Assign: First = Big, Next 2 = Small
    $big    = $show_big ? array_shift( $featured_posts ) : null;
    $events = $featured_posts; // Remaining (max 2)

    // If we disabled big, show up to 3 small instead
    if ( ! $show_big ) {
        $events = array_slice( $featured_posts, 0, 3 );
    }

    // Layout classes
    $has_big_featured       = $big ? 'has-big-featured' : '';
    $grid                   = $big ? 'grid' : '';
    $article_img_order      = $big ? 'order-1' : 'order-2';
    $article_content_order  = $big ? 'order-2' : 'order-1';
    $small_grid_columns     = $big ? 1 : ( count( $events ) >= 2 ? 2 : 1 );

    ?>

    <section class="events-offers-module" style="<?php echo $bgcolor; ?>">
        <div class="container display-flex flex-direction-column gap-8 <?php echo $has_big_featured; ?>">

            <div class="section-header display-flex justify-space-between items-center flex-wrap gap-8">
                <div class="display-flex flex-direction-column gap-2">
                    <?php if ( $top_text ) : ?>
                        <span class="top-texts"><?php echo esc_html( $top_text ); ?></span>
                    <?php endif; ?>
                    <h2 class="no-margin-bottom"><?php echo wp_kses_post( $title ?: "What's On at Lake Macquarie Square" ); ?></h2>
                </div>
                <?php if ( ! empty( $button['url'] ) ) : ?>
                    <a href="<?php echo esc_url( $button['url'] ); ?>" class="site-btn btn--primary">
                        <?php echo esc_html( $button['title'] ); ?>
                    </a>
                <?php endif; ?>
            </div>

            <div class="events-grid <?php echo $grid; ?>">

                <?php if ( $big ) :
                    $img   = $img_url( get_field( 'post_type_image', $big->ID ), 'large' );
                    $enable_date = get_field( 'enable_date_post_type', $big->ID );
                    $start = get_field( 'date_events_start', $big->ID );
                    $end   = get_field( 'date_events_end', $big->ID );


                    $start_date = DateTime::createFromFormat('d/m/Y', $start); 
                    $start = $start_date ? $start_date->format('Y-m-d') : false;

                    $end_date = DateTime::createFromFormat('d/m/Y', $end);
                    $end = $end_date ? $end_date->format('Y-m-d') : false;
                ?>
                    <article class="big-event">
                        <a class="display-block" href="<?php echo get_permalink( $big ); ?>">
                            <?php if ( $img ) : ?>
                                <div class="overlay" style="background: linear-gradient(180deg, rgba(0,0,0,0.00) 0%, rgba(0,0,0,0.50) 100%), url(<?php echo esc_url( $img ); ?>) lightgray 50% / cover no-repeat;"></div>
                            <?php endif; ?>
                            <div class="content display-flex flex-direction-column items-center gap-5 width-max">
                                <?php if ( $start && $enable_date) : ?>
                                    <span class="date-badge no-margin-bottom width-fit">
                                        <?php echo date_i18n( 'd M Y', strtotime( $start ) ); ?>
                                        <?php if ( $end && $end !== $start ) echo ' – ' . date_i18n( 'd M Y', strtotime( $end ) ); ?>
                                    </span>
                                <?php endif; ?>
                                <h2 class="text-white size-36 no-margin-bottom"><?php echo esc_html( get_the_title( $big ) ); ?></h2>
                            </div>
                        </a>
                    </article>
                <?php endif; ?>

                <?php if ( ! empty( $events ) ) : ?>
                    <div class="small-events gap-6 grid" style="grid-template-columns: repeat(<?php echo $small_grid_columns; ?>, 1fr);">
                        <?php foreach ( $events as $event ) :
                            $img         = $img_url( get_field( 'post_type_image', $event->ID ), 'medium_large' );
                            $enable_date = get_field( 'enable_date_post_type', $event->ID );
                            $start       = get_field( 'date_events_start', $event->ID );
                            $end         = get_field( 'date_events_end', $event->ID );
                            $excerpt     = get_field( 'post_type_excerpt', $event->ID );
                            $top_texts   = get_field( 'post_type_top_texts', $event->ID );

                            $start_date = DateTime::createFromFormat('d/m/Y', $start); 
                            $start = $start_date ? $start_date->format('Y-m-d') : false;

                            $end_date = DateTime::createFromFormat('d/m/Y', $end);
                            $end = $end_date ? $end_date->format('Y-m-d') : false;
                        ?>
                            <article class="display-flex flex-direction-row gap-4">
                                <?php if ( $img ) : ?>
                                    <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $event->post_title ); ?>" class="<?php echo $article_img_order; ?>">
                                <?php endif; ?>
                                <div class="small-content display-flex flex-direction-column gap-4 <?php echo $article_content_order; ?>">
                                    <div class="display-flex flex-direction-column gap-3">
                                        <?php if ( $start && $enable_date) : ?>
                                            <span class="date-badge no-margin-bottom width-fit">
                                                <?php echo date_i18n( 'd M Y', strtotime( $start ) ); ?>
                                                <?php if ( $end && $end !== $start ) echo ' – ' . date_i18n( 'd M Y', strtotime( $end ) ); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ( $top_texts ) : ?>
                                            <span class="top-texts"><?php echo esc_html( $top_texts ); ?></span>
                                        <?php endif; ?>
                                        <h4 class="no-margin-bottom"><?php echo esc_html( $event->post_title ); ?></h4>
                                        <?php if ( $excerpt ) : ?>
                                            <span class="size-14 text-black"><?php echo wp_kses_post( $excerpt ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <a class="btn--primary btn-outline btn-dark text-decoration-none width-fit" href="<?php echo get_permalink( $event ); ?>">
                                        Learn More
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </section>

    <?php
} );
?>