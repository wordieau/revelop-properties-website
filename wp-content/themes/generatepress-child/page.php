<?php
/**
 * Default Page Template → now with Flexible Content
 * This file is used for ALL pages automatically.
 */

 while ( have_posts() ) : the_post();


 $has_dark_header = false;   // default = light header
 $has_bgcolor = false;
 if ( have_rows('content_layouts_fc') ) {
     while ( have_rows('content_layouts_fc') ) : the_row();

         if ( get_row_layout() == 'inner_page_banner_layout' ) {
            
           

             $content = get_sub_field('contents_layout');

             $banner_rows = $content['inner_page_banner_group'];

           
             // Check if repeater has at least one row
             if ( $banner_rows && is_array( $banner_rows ) ) {
               
                 foreach ( $banner_rows as $row ) {
                     $bg_image = $row['inner_page_header_background_image'] ?? 'false';
                     $bg_color = $row['inner_page_background_color'] ?? 'false';
                     // If any banner row has a background image AND it's enabled, use dark header
                     $show_bg = $row['inner_page_header_show_background_image'] ?? 'false';
                     
                     if ( $show_bg && ! empty( $bg_image ) ) {
                        
                         $has_dark_header = true;
                         break 2; // Exit both foreach and have_rows loop early
                     }

                     if ( $bg_color  ) {
                        
                        $has_bgcolor = true;
                        break 2; // Exit both foreach and have_rows loop early
                    }
                 }
             }
         }

     endwhile;

     // Important: reset the rows pointer so the main content loop works correctly later!
     reset_rows();
 }

 // ← NOW pass it to header.php
 $GLOBALS['has_dark_header'] = $has_dark_header;
 $GLOBALS['has_bgcolor'] = $has_bgcolor;

endwhile;

get_header(); ?>

<div class="site-content">

    <?php
        // Reset the main loop pointer
        if ( have_posts() ) {
            the_post();
            rewind_posts();
        }


    while ( have_posts() ) : the_post(); ?>
    
        <?php 
        $has_dark_header = false;
        if ( have_rows( 'content_layouts_fc' ) ) : ?>
            <?php $default_prefix = 'contents_layout_'; // Consistent prefix for all layouts; adjust if needed ?>

            <?php while ( have_rows( 'content_layouts_fc' ) ) : the_row(); ?>

                <?php if ( get_row_layout() == 'inner_page_banner_layout' ) : ?>
                    
                    <?php 
                    $page_banner_group = 'inner_page_banner_group';
                    $content = get_sub_field('contents_layout');
                    $bg_image = $content[$page_banner_group]['inner_page_header_show_background_image'] ?? false;

                    // CRITICAL: Only set to true if image exists
                    // If no image → leave as false (light header)
                    if ( $bg_image) {
                        $has_dark_header = true;
                    } 
                    // No else needed — if no image, stays false
                    ?>
                    <?php get_template_part( 'template-parts/modules/page-banner/page-banner', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'hero_content_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/hero-content/hero-content', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'carousel_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/carousel/carousel', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'card_items_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/card-items/card-items', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'benefits_highlight_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/benefits-highlight/benefits-highlight', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'two-column_content_block_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/two-column-block/two-column-block', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'collapsible_contents_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/collapsible-contents/collapsible-contents', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'cta_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/cta/cta', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'gallery_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/image-gallery/image-gallery', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); 
                    ?>
                <?php endif; ?>


                <?php if ( get_row_layout() == 'credibility_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/credibility/credibility', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); 
                    ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'team_grid_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/team-grid/team-grid', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); 
                    ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'posts_loop_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/posts-loop/posts-loop', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); 
                    ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'form_contents_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/form-contents/form-contents', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); 
                    ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'events_offers_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/events-offers/events-offers', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); 
                    ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'social_media_gallery_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/social-media-gallery/social-media-gallery', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); 
                    ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'acknowledgement_section_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/acknowledgement/acknowledge', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); 
                    ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'trading_hours_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/trading-hours/trading-hours', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); 
                    ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'texts_editor_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/texts-editor/texts-editor', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); 
                    ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'enquiry_form_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/enquiry-form/enquiry-form', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); 
                    ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'map_interactive_layout' ) : ?>
                    <?php get_template_part( 'template-parts/modules/map-interactive/map-interactive', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); 
                    ?>
                <?php endif; ?>

                <?php if ( get_row_layout() == 'shop_listing' ) : ?>
                    <?php get_template_part( 'template-parts/modules/shop-listing/shop-listing', null, [
                        'prefix' => $default_prefix,
                        'row_index' => get_row_index(),
                    ] ); 
                    ?>
                <?php endif; ?>

            <?php endwhile; ?>

        <?php else : ?>
           
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
 

                <div class="entry-content">
                    <div class="container">
                        <?php
                        the_content();
                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'your-textdomain' ),
                            'after'  => '</div>',
                        ) );
                        ?>
                    </div>
                </div>
            </article>
        <?php endif; ?>

        <?php 
 
        $GLOBALS['has_dark_header'] = true;
        ?>
    <?php endwhile; ?>

</div>

<?php get_footer(); ?>