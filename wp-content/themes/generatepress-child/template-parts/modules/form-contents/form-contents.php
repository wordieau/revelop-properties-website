<?php
/**
 * Module: Form & Contents
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$args      = $args ?? [];
$prefix    = $args['prefix'] ?? '';
$row_index = $args['row_index'] ?? 0;

$content = get_sub_field('contents_layout');

// Safety check
if ( ! $content ) {
    return;
}
$form_bgcolor = $content['form_background_color'] ? 'background-color:'.$content['form_background_color'] : '';
$gform_id = $content['gform_id'] ?? 0;
$hide_gform_title     = $content['hide_title_gform'] ? 'false' : '';
$form_alignment          = $content['form_alignment_gform'] ?? '';

$title_c    = $content['title_fc'] ?? '';
$description = $content['descriptions_fc'] ?? '';

$contact_details_gp = 'contact_details_fc';
$contact_details_repeater = $content['contact_details_fc'] ?? [];
$form_alignment_gform = $content['form_alignment_gform'] ?? '';

$form = $form_alignment_gform === 'right' ? 'order-2' : 'order-1';
$contact = $form_alignment_gform === 'right' ? 'order-1' : 'order-2';

$social_links = $content['social_links_fc'] ?? '';

$image_map_enabled = $content['map_image_type'] ?? 0;
$map_embed = $content['map_fc'] ?? '';
$map_image = $content['map_image_fc'] ?? '';

$gravity_form_or_hubspot_form     = $content['gravity_form_or_hubspot_form'];
$hubspot_embedded_code = $content['hubspot_embedded_code'] ?? '';

$has_hubspot = !empty($gravity_form_or_hubspot_form);
?>
<section class="form-contents">
    <div class="container display-flex flex-direction-row gap-8 flex-wrap">
        <div class="form-container <?php echo $form; ?> <?php echo $has_hubspot ? 'has-hubspot' : ''; ?>" style="<?php echo $form_bgcolor;?>">
            <?php
                if($gravity_form_or_hubspot_form){
                    echo $hubspot_embedded_code;
                }else{
                    if ( function_exists('gravity_form') && ! empty($gform_id) ) {
                        echo do_shortcode('[gravityform id="' . esc_attr($gform_id) . '" title="'.$hide_gform_title.'" description="true" ajax="true"]');
                    } else { ?>
                     
                            <?php echo '<p class="class="no-margin-bottom"">Form Coming Soon.</p>'; ?>
                        
                    <?php }
                };
            ?>
        </div>
        <div class="contact-info-contianer display-flex flex-direction-column gap-8 <?php echo $contact; ?>">

            <div class="display-flex flex-direction-column gap-2">
            <?php if ($title_c): ?>
                <h4 class="no-margin-bottom spacing-0x6"><?php echo esc_html($title_c); ?></h4>
            <?php endif; ?>
            <?php if ($description): ?>
                <div class="no-margin-bottom-outer text-grey">
                <?php echo wpautop($description); ?>
                </div>
            <?php endif; ?>
            </div>

            <div class="display-flex flex-direction-column gap-6">

            <!-- Contact Details -->
            <?php foreach($contact_details_repeater as $contact) : 
         
           
                    $icon = $contact['icon'];
                    $link = $contact['link'];
                    $text = $contact['texts_cdcd'];
                    $sub_text = $contact['sub_text'];
                    
                ?>
                <?php if ($link || $icon) : ?>
                    <div class="contact-item display-flex flex-direction-row gap-4">
                    
                        <?php if ($icon): ?>
                            <img src="<?php echo esc_url($icon['url']); ?>" alt="Icon" class="contact-icon img-size-28">
                        <?php endif; ?>
                        <div class="">
                            <?php if($link || $text) : ?>
                            
                                <?php if( $link  ) : ?>
                                    <a href="<?php echo $link; ?>" class="text-decoration-none">
                                <?php endif; ?>

                                <span class="spacing-1x4 text-grey font-uni-sans"><?php echo esc_html($text); ?></span>

                                <?php if( $link ) : ?>
                                    </a>
                                <?php endif; ?>

                            <?php endif; ?>
                            <span class="no-margin-bottom-outer"><?php echo wp_kses_post($sub_text); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            
            <?php
         
            $social_links = $content['social_links_fc'] ?? false;

            if ( ! empty( $social_links ) && is_array( $social_links ) ) : ?>
                <div class="display-flex flex-direction-row gap-3 mt-4">

                    <?php foreach ( $social_links as $social ) :

                        $icon = $social['icon_social_link'] ?? false;
                        $link = $social['link_social'] ?? false;

                        if ( ! $link || empty( $link['url'] ) ) {
                            continue;
                        }

                        $url    = esc_url( $link['url'] );
                        $title  = esc_html( $link['title'] ?? 'Follow us' );
                        $target = ! empty( $link['target'] ) ? 'target="_blank" rel="noopener"' : '';
                    
                        $icon_url = '';
                        $icon_alt = $title;

                        if ( ! empty( $icon ) ) {
                            if ( is_array( $icon ) ) {
                                $icon_url = $icon['url'] ?? '';
                                $icon_alt = $icon['alt'] ?? $title;
                            } else {
                                $icon_url = $icon; 
                            }
                        }
                    ?>

                        <div class="social-icon">
                            <a href="<?php echo $url; ?>" <?php echo $target; ?> aria-label="<?php echo $title; ?>">
                                <?php if ( $icon_url ) : ?>
                                    <img src="<?php echo esc_url( $icon_url ); ?>" alt="<?php echo esc_attr( $icon_alt ); ?>" style="height: 32px; width: auto;">
                                <?php else : ?>
                                    <?php echo $title; // fallback text ?>
                                <?php endif; ?>
                            </a>
                        </div>

                    <?php endforeach; ?>

                </div>
            <?php endif; ?>
            </div>
            
        <?php $dummy_map = get_stylesheet_directory_uri() . '/assets/imgs/dummymap.png'; ?>
        
        <?php if($image_map_enabled) : ?>
            <div class="map-embed rounded">
                    <img 
                        src="<?php echo esc_url( $map_image['url'] ); ?>" 
                        
                        class=""
                        loading="lazy">
            </div>

        <?php else : ?>
            <?php if ( trim( $map_embed ) ) : ?>
        
                <div class="map-embed rounded">
                    <?php echo $map_embed; ?>
                </div>

            <?php else : ?>
            
                <div class="map-placeholder position-relative">
                    <img 
                        src="<?php echo esc_url( $dummy_map ); ?>" 
                        alt="Facility location – 42/46 Villas Road, Dandenong VIC 3175" 
                        class=""
                        loading="lazy">
                </div>
            <?php endif; ?>

        <?php endif; ?>
        </div>
    </div>
</section>