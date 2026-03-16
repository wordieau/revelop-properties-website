<?php get_header(); ?>

<section class="single-post">

    <?php while ( have_posts() ) : the_post(); 
        $image   = get_field('post_type_image');
        $title   = get_field('post_type_title') ?: get_the_title();
        $excerpt = get_field('post_type_excerpt');

        $bg_color = get_field('image_background_color');
        $featured_img_bgcolor = $bg_color ? 'background-color:' . esc_attr($bg_color) . ';' : '';
      
        $terms     = get_the_terms(get_the_ID(), 'category');
        $category  = $terms && !is_wp_error($terms) ? $terms[0]->name : '';

        $enable_date = get_field('enable_date_post_type') ?? '';
        $start = get_field( 'date_events_start') ?? '';
        $end   = get_field( 'date_events_end') ?? '';

        $time = get_field('time_post_type') ?? '';
        $location = get_field('location_post_type') ?? '';

        $event_links = get_field('event_links_post_type') ?? [];

        /* Store Details */
        $store_info = get_field('store_info') ?: [];
        $phone = $store_info['phone'] ?? '';
        $email = $store_info['email'] ?? '';

        $website = $store_info['website'] ?? '';
        $fb = $store_info['facebook'] ?? '';
        $instagram = $store_info['instagram'] ?? '';

        $has_links = $website || $fb || $instagram;

        $title_bhours = $store_info['title_biz_hours'] ?? '';


        

        // Safely get each day group (default to empty array if not set)
        $mongp = $store_info['monday_biz'] ?? [];
        $mon_hr = !empty($mongp['close']) ? 'Closed' : (!empty($mongp['start']) && !empty($mongp['end']) ? $mongp['start'] . ' - ' . $mongp['end'] : '');

        $tuegp = $store_info['tuesday_biz'] ?? [];
        $tue_hr = !empty($tuegp['close']) ? 'Closed' : (!empty($tuegp['start']) && !empty($tuegp['end']) ? $tuegp['start'] . ' - ' . $tuegp['end'] : '');

        $wedgp = $store_info['wednesday_biz'] ?? [];
        $wed_hr = !empty($wedgp['close']) ? 'Closed' : (!empty($wedgp['start']) && !empty($wedgp['end']) ? $wedgp['start'] . ' - ' . $wedgp['end'] : '');

        $thugp = $store_info['thursday_biz'] ?? [];
        $thu_hr = !empty($thugp['close']) ? 'Closed' : (!empty($thugp['start']) && !empty($thugp['end']) ? $thugp['start'] . ' - ' . $thugp['end'] : '');

        $frigp = $store_info['friday_biz'] ?? [];
        $fri_hr = !empty($frigp['close']) ? 'Closed' : (!empty($frigp['start']) && !empty($frigp['end']) ? $frigp['start'] . ' - ' . $frigp['end'] : '');

        $satgp = $store_info['saturday_biz'] ?? [];
        $sat_hr = !empty($satgp['close']) ? 'Closed' : (!empty($satgp['start']) && !empty($satgp['end']) ? $satgp['start'] . ' - ' . $satgp['end'] : '');

        $sungp = $store_info['sunday_biz'] ?? [];
        $sun_hr = !empty($sungp['close']) ? 'Closed' : (!empty($sungp['start']) && !empty($sungp['end']) ? $sungp['start'] . ' - ' . $sungp['end'] : '');

        // Check if any day has hours
        $has_hours = $mon_hr || $tue_hr || $wed_hr || $thu_hr || $fri_hr || $sat_hr || $sun_hr;
        

        //locate us
        $link_locate_us = $store_info['link_locate_us'] ?? [];
        $title_locate_us = $store_info['title_locate_us'] ?? '';
        $image_locate_us = $store_info['image_locate_us'] ?? '';

        
    ?>

        <div class="single-post-header display-flex">
            <div class="container display-flex flex-direction-row items-center justify-space-between flex-wrap gap-8">
                <div class="featured-texts display-flex flex-direction-column gap-5x">
                    
                    <div class="display-flex flex-direction-column gap-5x">
                        <?php 
                        if ( $post_type === 'listing' ) :
                            my_theme_breadcrumbs('Shop', '/shop/'); 
                        elseif($post_type === 'event') :
                            my_theme_breadcrumbs('What’s On', '/whats-on/'); 
                        elseif($post_type === 'offer') :
                            my_theme_breadcrumbs('What’s On', '/whats-on/'); 
                        endif;
                        ?>

                        <?php if ( $title ): ?>
                            <h1 class="no-margin-bottom size-48">
                                <?php echo esc_html($title); ?>
                            </h1>
                        <?php endif; ?>
                    </div>

                    <?php if ( $start && $enable_date) : ?>
                        <div class="display-flex flex-direction-column gap-2">
                            <span class="top-texts">Event dates</span>
                            <span class="width-fit">
                                <?php echo date_i18n( 'j M Y', strtotime( $start ) ); ?>
                                <?php if ( $end && $end !== $start ) echo ' – ' . date_i18n( 'j M Y', strtotime( $end ) ); ?>
                            </span>

                            <?php if ( $time ): ?>
                                <span><?php echo $time; ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                
                    <?php if ( $excerpt ): ?>
                        <div>
                            <div class="no-margin-bottom-outer">
                                <?php echo wp_kses_post($excerpt); ?>
                            </div>

                        </div>
                    <?php endif; ?>
                        

                    <?php if ( $location ): ?>
                        <div class="display-flex flex-direction-column gap-2">
                            <span class="top-texts">Location</span>
                            <span><?php echo $location; ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if($link_locate_us): ?>
                        <a href="<?php echo $link_locate_us['ur;'] ?>" class="btn-alt text-decoration-none width-fit">Locate on Centre Directory</a>
                    <?php endif; ?>



                    <?php if($phone || $email): ?>
                        <div class="display-flex flex-direction-column gap-2">
                            <span class="top-texts">contact information</span>
                            <?php if($phone): ?>
                                <span><?php echo $phone; ?></span>
                            <?php endif; ?>
                            <?php if($email): ?>
                                <span><?php echo $email; ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ( $event_links ): ?>
                        <div>
                            <a href="<?php echo $event_links['url']; ?>" class="btn--primary text-decoration-none text-white"><?php echo $event_links['title']; ?></a>
                        </div>
                    <?php endif; ?>

                    <?php if($has_links) :  ?>
                        
                        <div class="display-flex flex-direction-column gap-2">
                            <span class="top-texts">links</span>
                            <ul class="list-type-format display-flex gap-4 flex-direction-row">
                                <?php if($website) : ?>
                                    <li class="">
                                        <a href="<?php echo $website; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                                            <mask id="mask0_4050_11991" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="28" height="28">
                                                <rect width="28" height="28" fill="#D9D9D9"/>
                                            </mask>
                                            <g mask="url(#mask0_4050_11991)">
                                                <path d="M14.0013 25.0834C12.4817 25.0834 11.0477 24.7921 9.69922 24.2096C8.35075 23.627 7.17475 22.8339 6.17122 21.8302C5.1675 20.8266 4.37436 19.6506 3.7918 18.3022C3.20925 16.9537 2.91797 15.5197 2.91797 14.0001C2.91797 12.4684 3.20925 11.0314 3.7918 9.68896C4.37436 8.34651 5.1675 7.17353 6.17122 6.17C7.17475 5.16628 8.35075 4.37314 9.69922 3.79058C11.0477 3.20803 12.4817 2.91675 14.0013 2.91675C15.5329 2.91675 16.97 3.20803 18.3124 3.79058C19.6549 4.37314 20.8279 5.16628 21.8314 6.17C22.8351 7.17353 23.6282 8.34651 24.2108 9.68896C24.7934 11.0314 25.0846 12.4684 25.0846 14.0001C25.0846 15.5197 24.7934 16.9537 24.2108 18.3022C23.6282 19.6506 22.8351 20.8266 21.8314 21.8302C20.8279 22.8339 19.6549 23.627 18.3124 24.2096C16.97 24.7921 15.5329 25.0834 14.0013 25.0834ZM14.0013 23.3086C14.5967 22.519 15.0978 21.7225 15.5046 20.9193C15.9113 20.116 16.2427 19.238 16.4986 18.2852H11.5041C11.7749 19.268 12.11 20.161 12.5094 20.9642C12.9086 21.7675 13.4059 22.5489 14.0013 23.3086ZM11.7421 22.9878C11.2948 22.3461 10.8932 21.6167 10.5372 20.7994C10.1811 19.982 9.90445 19.1439 9.70709 18.2852H5.74947C6.36566 19.4968 7.19205 20.5147 8.22863 21.339C9.26522 22.1631 10.4364 22.7127 11.7421 22.9878ZM16.2606 22.9878C17.5662 22.7127 18.7374 22.1631 19.774 21.339C20.8106 20.5147 21.6369 19.4968 22.2531 18.2852H18.2955C18.0606 19.1513 17.7652 19.9931 17.4091 20.8105C17.0533 21.6279 16.6704 22.3537 16.2606 22.9878ZM5.01564 16.5352H9.35272C9.27941 16.1016 9.22623 15.6765 9.19318 15.2598C9.16032 14.8433 9.14388 14.4234 9.14388 14.0001C9.14388 13.5768 9.16032 13.1569 9.19318 12.7404C9.22623 12.3237 9.27941 11.8985 9.35272 11.4649H5.01564C4.90344 11.8612 4.8175 12.2732 4.7578 12.701C4.69791 13.1288 4.66797 13.5618 4.66797 14.0001C4.66797 14.4384 4.69791 14.8714 4.7578 15.2992C4.8175 15.7269 4.90344 16.139 5.01564 16.5352ZM11.1024 16.5352H16.9002C16.9733 16.1016 17.0264 15.6803 17.0594 15.2712C17.0923 14.8621 17.1087 14.4384 17.1087 14.0001C17.1087 13.5618 17.0923 13.1381 17.0594 12.729C17.0264 12.3199 16.9733 11.8985 16.9002 11.4649H11.1024C11.0293 11.8985 10.9762 12.3199 10.9432 12.729C10.9103 13.1381 10.8939 13.5618 10.8939 14.0001C10.8939 14.4384 10.9103 14.8621 10.9432 15.2712C10.9762 15.6803 11.0293 16.1016 11.1024 16.5352ZM18.6499 16.5352H22.987C23.0992 16.139 23.1851 15.7269 23.2448 15.2992C23.3047 14.8714 23.3346 14.4384 23.3346 14.0001C23.3346 13.5618 23.3047 13.1288 23.2448 12.701C23.1851 12.2732 23.0992 11.8612 22.987 11.4649H18.6499C18.7232 11.8985 18.7764 12.3237 18.8094 12.7404C18.8423 13.1569 18.8587 13.5768 18.8587 14.0001C18.8587 14.4234 18.8423 14.8433 18.8094 15.2598C18.7764 15.6765 18.7232 16.1016 18.6499 16.5352ZM18.2955 9.71492H22.2531C21.6296 8.48836 20.8088 7.47044 19.7909 6.66116C18.773 5.85208 17.5962 5.29869 16.2606 5.001C16.7078 5.68 17.1056 6.42268 17.4541 7.22904C17.8027 8.03521 18.0832 8.86383 18.2955 9.71492ZM11.5041 9.71492H16.4986C16.2277 8.73958 15.887 7.84096 15.4766 7.01904C15.0659 6.19712 14.5741 5.42129 14.0013 4.69154C13.4285 5.42129 12.9367 6.19712 12.5261 7.01904C12.1156 7.84096 11.7749 8.73958 11.5041 9.71492ZM5.74947 9.71492H9.70709C9.91943 8.86383 10.1999 8.03521 10.5486 7.22904C10.897 6.42268 11.2948 5.68 11.7421 5.001C10.3988 5.29869 9.2202 5.85403 8.20618 6.667C7.19195 7.47978 6.37305 8.49575 5.74947 9.71492Z" fill="#212B45"/>
                                            </g>
                                            </svg>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if($fb) : ?>
                                    <li class="">
                                        <a href="<?php echo $fb; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                                        <path d="M25.487 14.0709C25.487 19.9397 21.1801 24.8147 15.5479 25.6666V17.4786H18.293L18.8136 14.0709H15.5479V11.8938C15.5479 10.9472 16.0212 10.0479 17.4884 10.0479H18.9556V7.16084C18.9556 7.16084 17.6304 6.92419 16.3052 6.92419C13.6547 6.92419 11.9035 8.58072 11.9035 11.5151V14.0709H8.92179V17.4786H11.9035V25.6666C6.27135 24.8147 2.01172 19.9397 2.01172 14.0709C2.01172 7.5868 7.26527 2.33325 13.7494 2.33325C20.2335 2.33325 25.487 7.5868 25.487 14.0709Z" fill="#212B45"/>
                                        </svg>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if($instagram) : ?>
                                    <li class="">
                                        <a href="<?php echo $instagram; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                                        <path d="M14.0247 7.98429C17.306 7.98429 20.0143 10.6926 20.0143 13.9739C20.0143 17.3072 17.306 19.9635 14.0247 19.9635C10.6914 19.9635 8.03516 17.3072 8.03516 13.9739C8.03516 10.6926 10.6914 7.98429 14.0247 7.98429ZM14.0247 17.8801C16.1602 17.8801 17.8789 16.1614 17.8789 13.9739C17.8789 11.8385 16.1602 10.1197 14.0247 10.1197C11.8372 10.1197 10.1185 11.8385 10.1185 13.9739C10.1185 16.1614 11.8893 17.8801 14.0247 17.8801ZM21.6289 7.77596C21.6289 6.99471 21.0039 6.36971 20.2227 6.36971C19.4414 6.36971 18.8164 6.99471 18.8164 7.77596C18.8164 8.55721 19.4414 9.18221 20.2227 9.18221C21.0039 9.18221 21.6289 8.55721 21.6289 7.77596ZM25.5872 9.18221C25.6914 11.1093 25.6914 16.8905 25.5872 18.8176C25.4831 20.6926 25.0664 22.3072 23.7122 23.7135C22.3581 25.0676 20.6914 25.4843 18.8164 25.5885C16.8893 25.6926 11.1081 25.6926 9.18099 25.5885C7.30599 25.4843 5.69141 25.0676 4.28516 23.7135C2.93099 22.3072 2.51432 20.6926 2.41016 18.8176C2.30599 16.8905 2.30599 11.1093 2.41016 9.18221C2.51432 7.30721 2.93099 5.64054 4.28516 4.28638C5.69141 2.93221 7.30599 2.51554 9.18099 2.41138C11.1081 2.30721 16.8893 2.30721 18.8164 2.41138C20.6914 2.51554 22.3581 2.93221 23.7122 4.28638C25.0664 5.64054 25.4831 7.30721 25.5872 9.18221ZM23.0872 20.8489C23.7122 19.3385 23.556 15.6926 23.556 13.9739C23.556 12.3072 23.7122 8.66138 23.0872 7.09888C22.6706 6.10929 21.8893 5.27596 20.8997 4.91138C19.3372 4.28638 15.6914 4.44263 14.0247 4.44263C12.306 4.44263 8.66016 4.28638 7.14974 4.91138C6.10807 5.32804 5.32682 6.10929 4.91016 7.09888C4.28516 8.66138 4.44141 12.3072 4.44141 13.9739C4.44141 15.6926 4.28516 19.3385 4.91016 20.8489C5.32682 21.8905 6.10807 22.6718 7.14974 23.0885C8.66016 23.7135 12.306 23.5572 14.0247 23.5572C15.6914 23.5572 19.3372 23.7135 20.8997 23.0885C21.8893 22.6718 22.7227 21.8905 23.0872 20.8489Z" fill="#212B45"/>
                                        </svg>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Featured Image -->
                <?php if ( $image ) : 
                    // Handle both Image Array and Image URL return formats
                    $img_url = is_array($image) ? $image['url'] : $image;
                    $img_alt = is_array($image) ? ($image['alt'] ?: $title) : $title;
                ?>
                    <div class="featured-img my-10">
                        <div style="<?php echo $featured_img_bgcolor; ?>">
                            <img 
                                src="<?php echo esc_url($img_url); ?>" 
                                alt="<?php echo esc_attr($img_alt); ?>" 
                                class="w-100 rounded-lg shadow-lg"
                                loading="lazy"
                            >
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php  $post_content = get_the_content(); ?>

        <?php if ( ! empty( trim( $post_content ) ) ) : ?>
            <div class="single-post-content">
                <div class="container">
                    <article class="single-post <?php echo $title_bhours && $has_hours ? 'display-flex flex-direction-row gap-8 flex-wrap' : 'maxw-977'; ?>">
                        <div class="the-content">
                            <?php the_content(); ?>
                        </div>          
                        
                        <!-- Business Hours Section - Similar to Screenshot -->
                        <?php if ( $title_bhours && $has_hours ) : ?>
                            <div class="business-hours display-flex flex-direction gap-2 flex-direction-column">
                                <span class="weight-600 top-texts"><?php echo esc_html($title_bhours); ?></span>
                                <div class="display-flex flex-direction-row justify-space-between gap-0 flex-wrap">
                                    <!-- Days Column -->
                                    
                                    <div class="display-flex flex-direction-column gap-0x text-gray">
                                        <?php if ($mon_hr) : ?><span>Monday</span><?php endif; ?>
                                        <?php if ($tue_hr) : ?><span>Tuesday</span><?php endif; ?>
                                        <?php if ($wed_hr) : ?><span>Wednesday</span><?php endif; ?>
                                        <?php if ($thu_hr) : ?><span>Thursday</span><?php endif; ?>
                                        <?php if ($fri_hr) : ?><span>Friday</span><?php endif; ?>
                                        <?php if ($sat_hr) : ?><span>Saturday</span><?php endif; ?>
                                        <?php if ($sun_hr) : ?><span>Sunday</span><?php endif; ?>
                                    </div>

                                    <!-- Title + Times Column -->
                                    <div class="display-flex flex-direction-column gap-0x text-left">
                                        
                                        <?php if ($mon_hr) : ?><span><?php echo esc_html($mon_hr); ?></span><?php endif; ?>
                                        <?php if ($tue_hr) : ?><span><?php echo esc_html($tue_hr); ?></span><?php endif; ?>
                                        <?php if ($wed_hr) : ?><span><?php echo esc_html($wed_hr); ?></span><?php endif; ?>
                                        <?php if ($thu_hr) : ?><span><?php echo esc_html($thu_hr); ?></span><?php endif; ?>
                                        <?php if ($fri_hr) : ?><span><?php echo esc_html($fri_hr); ?></span><?php endif; ?>
                                        <?php if ($sat_hr) : ?><span><?php echo esc_html($sat_hr); ?></span><?php endif; ?>
                                        <?php if ($sun_hr) : ?><span><?php echo esc_html($sun_hr); ?></span><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ( function_exists( 'sassy_social_share' ) || class_exists( 'Sassy_Social_Share' ) ) { ?>
                            <div class="display-flex flex-direction-row items-center gap-5x">
                                <span class="single-post-share-label">share this article</span>
                                <div><?php echo do_shortcode('[Sassy_Social_Share]'); ?></div>
                            </div>
                        <?php } ?>

                    </article>
                </div>
            </div>
            <?php if($title_locate_us && $image_locate_us) : ?>
                <div class="locate-us display-flex flex-direction-column gap-8">
                    <div class="container display-flex flex-direction-row gap-8 justify-space-between items-end flex-wrap">   
                        
                            <div class="display-flex flex-direction-column gap-2 locate-us-title">
                                <span class="top-texts">locate us</span>
                                <span><h2 class="no-margin-bottom"><?php echo $title_locate_us; ?></h2></span>
                            </div>
                            <?php if($link_locate_us) : 
                                $link_text = $link_locate_us['title'] ?? 'View Full Directory';
                                ?>
                                <a href="<?php echo $link_locate_us['url']; ?>" class="btn-alt text-decoration-none"><?php echo $link_text; ?></a>
                            <?php endif; ?>
                        
                    </div>
                    <div class="locate-us-map" style="background-image:url('<?php echo $image_locate_us['url']; ?>')"></div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    <?php endwhile; ?>
    
</section>

<!-- Rest of your code (related posts grid, acknowledgement, footer) remains unchanged -->
<?php

if ( $post_type === 'event' ) :
    simple_posts_grid( array(
        'perpage'   => 4,
        'title'     => 'Other Events For You',
        'bgcolor'   => '#FAFAF9',
        'post_type' => 'event',
        'btn_link'  => 'whats-on',
        'btn_text'  => 'See All Events'
    ) );
elseif ( $post_type === 'offer' ) :
    simple_posts_grid( array(
        'perpage'   => 4,
        'title'     => 'Other Offers',
        'bgcolor'   => '#FAFAF9',
        'post_type' => 'offer',
        'btn_link'  => 'special-offers',
        'btn_text'  => 'See All Offers'
    ) );
elseif ( $post_type === 'listing' ) :
    simple_posts_grid( array(
        'perpage'   => 4,
        'mobile_percolumn' => 2,
        'title'     => 'Related Shops For You',
        'bgcolor'   => '#FAFAF9',
        'post_type' => 'listing',
        'btn_link'  => 'shop',
        'btn_text'  => 'See All Shops'
    ) );
else :
    simple_posts_grid( array(
        'perpage'   => 4,
        'title'     => 'Other Articles For You',
        'bgcolor'   => '#FAFAF9',
        'post_type' => 'post',
        'btn_link'  => 'blog',
        'btn_text'  => 'See All Articles'
    ) );
endif;
?>

<!--Acknowledge-->
<?php
    $enable_site =  get_field( 'enable_ack','option' );
    $content = get_field('site_settings_acknowledgement','option');

    $bgcolor = $content['bgcolor_ack'] ? 'background-color:'.$content['bgcolor_ack'] : '';
    $title = $content['title_ack'] ?? '';
    $sub_texts = $content['sub_texts_ack'] ?? '';

    ?>
    <?php if($enable_site) : ?>
    <section class="acknowledgement-section" style="<?php echo $bgcolor; ?>">
        <div class="container display-flex flex-direction-column justify-center items-center text-center text-blue gap-1">
        <?php if($title) : ?>
            <span class="weight-700"><?php echo esc_html($title); ?></span>
            <span><?php echo wp_kses_post($sub_texts); ?></span>
        <?php endif; ?>
        </div>
    </section>
    <?php endif; 
?>

<?php get_footer(); ?>