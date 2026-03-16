<?php
/**
 * footer.php
 * 
 */


$footer_bg = get_field('footer_background_color','option') ?? '';
$text_type_color = get_field('footer_text_color_type','option') ?? '';
?>
<footer class="site-footer" role="contentinfo" style="background-color:<?php echo $footer_bg; ?>">


    <div class="display-flex flex-direction-row justify-space-between flex-wrap gap-7 container">
        <?php if (have_rows('footer_contact_repeater') || get_field('logo','option')): ?>
            <div class="footer-top-contact-bar">
                <div class="container">
                    <div class="footer-contact-inner display-flex flex-direction-column gap-5x">

                        <!-- Logo -->
                        <?php 
                        $logo = get_field('logo', 'option');
                        if ($logo): ?>
                            <div class="footer-logo">
                                <a href="<?php echo esc_url(home_url('/')); ?>">
                                    <img src="<?php echo esc_url($logo['url']); ?>" 
                                        alt="<?php echo esc_attr(get_bloginfo('name')); ?> Logo">
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Desktop Contact Repeater -->
                        <?php if (have_rows('footer_contact_repeater', 'option')): ?>
                            <div class="footer-contact-info display-flex flex-direction-column gap-3 desktop">
                                <?php while (have_rows('footer_contact_repeater', 'option')): the_row(); ?>
                                    <div class="contact-item">
                                        <?php 
                                        $icon = get_sub_field('footer-contact-icon-repeater');
                                        $button_footer_contact_repeater = get_sub_field('text-link-footer-contact-repeater');
                                        
                                        
                                        if ($button_footer_contact_repeater) {
                                            $link_url = $button_footer_contact_repeater['url'] ?? '';
                                            $link_title = $button_footer_contact_repeater['title'] ?? '';
                                            $link_target = $button_footer_contact_repeater['target'] ? $button_footer_contact_repeater['target'] : '_self';
                                            echo '<a class="size-16 text-black text-decoration-none display-flex flex-direction-row gap-1x" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">';
                                        }
                                        
                                        if ($icon) {
                                            echo '<img src="' . esc_url($icon['url']) . '" alt="" class="contact-icon img-size-24 ">';
                                        }
                                        
                                        if ($link_title) {
                                            echo '<span>' . wp_kses_post($link_title) . '</span>';
                                        }
                                        
                                        if ($link_url) echo '</a>';
                                        ?>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Mobile Contact Repeater (separate field) -->
                        <?php if (have_rows('footer_contact_repeater_mobile', 'option')): ?>
                            <div class="footer-contact-info mobile">
                                <?php while (have_rows('footer_contact_repeater_mobile', 'option')): the_row(); 
                                    // Same structure as desktop repeater
                                    $icon = get_sub_field('icon');
                                    $text = get_sub_field('text');
                                    $link = get_sub_field('link');
                                    // ... repeat same markup as above if needed
                                ?>
                                    <!-- Mobile version items here -->
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                        <?php 
                            $social_enable = get_field('social_enable', 'option');

                            if ( $social_enable ) : ?>
                                <div class="social-links">
                                    <?php 
                                    if ( have_rows('social', 'option') ) : 
                                        while ( have_rows('social', 'option') ) : the_row();
                                            
                                            // Icon is an Image field - adjust return format as needed
                                            $social_icon = get_sub_field('social_icon'); 
                                            $social_link = get_sub_field('social_link'); // plain text URL (string)
                                            
                                            // Safety check - only output if we have a link
                                            if ( $social_link && $social_icon) :
                                                // If icon is array (default ACF image return)
                                                if ( is_array($social_icon) ) {
                                                    $icon_url = $social_icon['url'];
                                                    $icon_alt = $social_icon['alt'] ?: 'Social icon';
                                                } else {
                                                    // If you set return format to "URL" or "ID"
                                                    $icon_url = $social_icon;
                                                    $icon_alt = 'Social icon';
                                                }
                                                ?>
                                                <a href="<?php echo esc_url( $social_link ); ?>" 
                                                target="_blank" 
                                                rel="noopener" 
                                                aria-label="Follow us">
                                                    <img src="<?php echo esc_url( $icon_url ); ?>" 
                                                        alt="<?php echo esc_attr( $icon_alt ); ?>" 
                                                        width="32" height="32">
                                                </a>
                                                <?php 
                                            endif;
                                        endwhile;
                                    endif; 
                                    ?>
                                </div>
                            <?php endif; ?>



                    </div>
                </div>
            </div>
        <?php endif; ?>



        <div class="footer-main">
            <div class="container">
                <div class="footer-columns display-flex justify-space-between flex-wrap gap-8">

                    <?php
            
                    $footer_columns_group = get_field('footer_column_widgets','option');
                    if($footer_columns_group) :
                        for ($i = 1; $i <= 5; $i++): ?>

                        
                        <?php $enable_column_field = "enable_column_{$i}";
                            $repeater_contents_field = "repeater_contents_column_{$i}";
                            
                        
                            $enable_column = $footer_columns_group[$enable_column_field] ?? '';
                            $repeater_contents = $footer_columns_group[$repeater_contents_field] ?? [];
                            


                            if($enable_column) : ?>
                                <div class="footer-column footer-column-<?php echo $i; ?> display-flex flex-direction-column gap-5x no-margin-bottom-outer"> 
                                    <?php if($repeater_contents) : ?>
                                        
                                        <?php foreach($repeater_contents as $content) : 
                                          
                                            $enable_soc_link = $content['enable_social_links'] ?? 0;
                                            $soc_links = $content['social_links'] ?? [];
                                            $enable_nav = $content['enable_nav'] ?? 0;
                                            $wd_nav_menus = $content['wd_nav_menus'] ?? [];
                                            $title = $content['title'] ?? '';
                                            $heading_type = $content['heading_type'] === 'big-heading' ? 'text-white' : 'top-texts';
                                            $subtext = $content['sub_texts'] ?? ''; 
                                            $enable_store_hours = $content['enable_store'] ?? false ;
                                            $store_hours = $content['store_hours'] ?? [];
                                            ?>

                                            

                                            <div class="display-flex flex-direction-column gap-3">
                                                <?php if ($title): ?>
                                                    <h4 class="footer-widget-title no-margin-bottom <?php echo $heading_type; ?>"><?php echo esc_html($title); ?></h4>
                                                <?php endif; ?>
                                                <?php if($subtext) : ?>
                                                    <span class="no-margin-bottom-outer <?php echo $text_type_color; ?> text-decoration-none-outer"><?php echo $subtext; ?></span>
                                                <?php endif; ?>
                                                <?php if ($wd_nav_menus && $enable_nav): ?>
                                                    <nav class="footer-nav text-white">
                                                        <?php
                                                        wp_nav_menu(array(
                                                            'menu'           => $wd_nav_menus,
                                                            'container'      => false,
                                                            'depth'          => 1,
                                                            'fallback_cb'    => false,
                                                            'items_wrap'     => '<ul class="footer-menu '.$text_type_color.'">%3$s</ul>',
                                                        ));
                                                        ?>
                                                    </nav>
                                                <?php endif; ?>

                                                <?php if ($soc_links && $enable_soc_link ): ?>
                                                    <span class="display-flex flex-direction-row gap-4">
                                                        <?php foreach($soc_links as $item) : 
                                                        
                                                            $icon = $item['icon'];
                                                            $url_soc = $item['link']; ?>

                                                                <?php if($icon && $url_soc) : ?>
                                                                    <a href="<?php echo $url_soc; ?>" class="text-decoration-none">
                                                                        <img src="<?php echo $icon['url']; ?>">
                                                                    </a>
                                                                <?php endif; ?>
                                                        <?php endforeach; ?> 
                                                    </span>
                                                <?php endif; ?>

                                                        <?php if($store_hours && $enable_store_hours) :  ?>
                                                            <ul class="list-type-format <?php echo $text_type_color; ?> store-hours">
                                                                <?php if($store_hours['monday']) : ?>
                                                                    <li><span>Monday </span><span><?php echo $store_hours['monday']; ?></span></li>
                                                                <?php endif; ?>

                                                                <?php if($store_hours['tuesday']) : ?>
                                                                    <li><span>Tuesday </span><span><?php echo $store_hours['tuesday']; ?></span></li>
                                                                <?php endif; ?>

                                                                <?php if($store_hours['wednesday']) : ?>
                                                                    <li><span>Wednesday </span><span><?php echo $store_hours['wednesday']; ?></span></li>
                                                                <?php endif; ?>

                                                                <?php if($store_hours['thursday']) : ?>
                                                                    <li><span>Thursday </span><span><?php echo $store_hours['thursday']; ?></span></li>
                                                                <?php endif; ?>

                                                                <?php if($store_hours['friday']) : ?>
                                                                    <li><span>Friday </span><span><?php echo $store_hours['friday']; ?></span></li>
                                                                <?php endif; ?>

                                                                <?php if($store_hours['saturday']) : ?>
                                                                    <li><span>Saturday </span><span><?php echo $store_hours['saturday']; ?></span></li>
                                                                <?php endif; ?>

                                                                <?php if($store_hours['sunday']) : ?>
                                                                    <li><span>Sunday </span><span><?php echo $store_hours['sunday']; ?></span></li>
                                                                <?php endif; ?>
                                                            </ul>
                                                        <?php endif; ?>
                                            </div>

                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endfor;
                    endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    $copyright_gp = get_field('copyright_group','option');
    
        $copyright = $copyright_gp['copyright_content'] ?? '';
        $copy_right_bgcolor = $copyright_gp['copyright_background_color'] ?? '';
        $website_by = $copyright_gp['website_by'] ?? '';
        $privacy_policy_link = $copyright_gp['privacy_policy'] ?? '';
        $terms_conditions = $copyright_gp['terms_conditions'] ?? '';
    ?>

    <div class="footer-bottom">
        <div class="container">
            <div class="footer-divider">
                <hr class="divider-line space-40-no-side">
            </div>
            <div class="footer-copyright-container text-center copy-right-texts no-margin-bottom-outer display-flex justify-space-between flex-wrap">
                <div class="<?php echo $text_type_color; ?> size-14 copy-right">
                    <?php echo wp_kses_post($copyright); ?>
                </div>
                <div class="<?php echo $text_type_color; ?> size-14 website-by">
                    <?php echo wp_kses_post($website_by); ?>
                </div>
                <div class="display-flex flex-direction-row items-center justify-space-between gap-4 privacy-terms">
                    <?php if($privacy_policy_link) :  ?>
                    <span><a href="<?php echo $privacy_policy_link['url']; ?>" class="text-white text-decoration-none size-14 weight-600"><?php echo $privacy_policy_link['title']; ?></a></span>
                    <?php endif; ?>

                    <?php if($terms_conditions) :  ?>
                    <span><a href="<?php echo $terms_conditions['url']; ?>" class="text-white text-decoration-none size-14 weight-600"><?php echo $terms_conditions['title']; ?></a></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php 
    $column_1_text = get_field('column_1_-_text','option');
    $column_1_company_logo = get_field('column_1_-_company_logo','option');
    $company_social_links = get_field('company_social_links','option');
    $column_3_related_centres = get_field('column_3_-_related_centres','option');
    $column_3_company_group_name = get_field('column_3_-_company_group_name','option');
?>

<div class="footer-bottom-black bg-1C1917 ptb-40">
    <div class="container">
        <div class="fbb-column display-flex">
            <div class="col">
                <p class="p1"><?php echo $column_1_text; ?></p>
                <img src="<?php echo $column_1_company_logo['url']; ?>" alt="<?php echo $column_1_company_logo['alt']; ?>">
            </div>
            <div class="col">
                <ul class="fbb-social-link display-flex gap-28">
                    <?php foreach($company_social_links as $csl) : ?>
                        <li>
                            <a href="<?php echo $csl['link']['url']; ?>"><img src="<?php echo $csl['icon']['url']; ?>" alt="<?php echo $csl['icon']['alt']; ?>"></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col">
                <a href="javascript:void(0)" class="orc-btn-dropdown">Other Revelop Centres <img src="/wp-content/uploads/2025/12/dropdown-icon.svg" alt="Dropdown icon"></a>
                <ul class="other-related-centres" style="display: none;">
                    <?php foreach($column_3_related_centres as $rc) : ?>
                        <li>
                            <a href="<?php echo $rc['link']['url']; ?>" target="_blank"><?php echo $rc['link']['title']; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p class="cgn"><a href="<?php echo $column_3_company_group_name['url']; ?>" target="_blank"><?php echo $column_3_company_group_name['title']; ?><img src="/wp-content/uploads/2025/12/newtab-icon.svg" alt="New Tab"></a></p>
            </div>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>