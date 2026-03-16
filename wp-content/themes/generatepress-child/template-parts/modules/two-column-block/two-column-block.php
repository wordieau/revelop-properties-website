<?php
/**
 * Module: Two Column Content Block
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$args      = $args ?? [];
$prefix    = $args['prefix'] ?? '';
$row_index = $args['row_index'] ?? 0;

// Optional: disable module via checkbox
if ( ! get_sub_field( 'module_enable' ) ) {
    return;
}

// Get the cloned field group (or direct fields if not cloned)
$content = get_sub_field( 'contents_layout' );

if ( ! $content ) {
    return;
}

$anchor_tag = $content['anchor_tag'] ?? '';

$bgcolor = $content['bgcolor_tccb'] ?? '';
$image_alignment = $content['image_alignment'] ?? 'left';
$image_after_content = $content['image_after_contents_tccb'] ? 'img-after-contents' : '';

$image       = $content['image_tccb'] ?? '';
$image_at_the_top = $content['image_at_the_top_tccb'] ? 'items-top' : 'items-center';
$title       = $content['title_tccb'] ?? '';
$sub_text    = $content['sub_texts_tccb'] ?? '';
$list_group  = $content['list_type_group'] ?? '';
$button = $content['button_tccb'] ?? '';
$another_sub_texts = $content['another_sub_texts_tccb'] ?? '';

$remove_divider = $list_group['remove_divider_tccb'] ?? '';
$remove_gap = $list_group['remove_divider_tccb'] ? 'gap-3' : '';

$img_status = $content['hide_this_on_mobile_tccb'] ? 'hidden-sm' : '';

$list_title  = $list_group['text_title_tccb'] ?? '';
$list_items  = $list_group['lists_tccb'] ?? []; // Repeater


$image_order = $image_alignment === 'right' ? 'order-2' : 'order-1';
$content_order = $image_alignment === 'right' ? 'order-1' : 'order-2';

// Footer Contents
$title_footer = $content['title_footer_tccb'] ?? '';
$card_items_footer = $content['card_items_tccb'] ?? [];

// Collapsible 
$enable_collapsible = $content['enable_collapsible_contents_tccb'] ?? false;
$items_group     = $content['collapsible_contents_tccb'] ?? [] ;
$items = $items_group['repeater_collapsible_c'];

//pricint table
$enable_pricing_table_tccb = $content['enable_pricing_table_tccb'] ?? false;
$pricing_title_tccb = $content['pricing_title_tccb'] ?? '';
$pricing_sub_texts_tccb = $content['pricing_sub_texts_tccb'] ?? '';
$pricing_table_lists_tccb = $content['pricing_table_lists_tccb'] ?? [];
$list_background_color_tccb = $content['list_background_color_tccb'] ?? '';
$desktop_columns_tccb = $content['desktop_columns_tccb'] ?? 2;
$tablet_columns_tccb = $content['tablet_columns_tccb'] ?? 2;
$mobile_columns_tccb = $content['mobile_columns_tccb'] ?? 2;
$footer_button_tccb = $content['footer_button_tccb'] ? 'flex-direction-column items-start' : 'items-center';
$btn_style = $content['footer_button_tccb'] ? 'btn--primary' : 'btn--primary btn-outline-dark gap-1 width-fit height-fit outward-arrow small-btn';
$outward_icon = $content['outward_arrow_tccb'] ? 'outward-arrow' : '';
/*Profiles*/
$enable_profiles = $content['enable_profiles_contents_tccb'] ?? false;
$profiles = $content['profiles_tccb'] ?? [];



?>

<section class="two-column-block" style="background-color:<?php echo $bgcolor; ?>" data-anchor="<?php echo $anchor_tag; ?>">
    <div class="container display-flex flex-direction-column gap-8">
        <div class="grid <?php echo $image_at_the_top; ?> gap-8 <?php echo $image_after_content; ?> columns-<?php echo $desktop_columns_tccb; ?> md-columns-<?php echo $tablet_columns_tccb; ?> mobile-columns-<?php echo $mobile_columns_tccb; ?>">

            <!-- Left Column: Image -->
            <div class="default-left <?php echo esc_attr( $image_order ); ?> img-column <?php echo $img_status; ?> ">
                <?php if ( $image ): ?>
                    <img src="<?php echo esc_url( $image['url'] ); ?>" 
                         alt="<?php echo esc_attr( $image['alt'] ?: $title ); ?>"
                         class="">
                <?php endif; ?>
            </div>

            <!-- Right Column: Content -->
            <div class="default-right <?php echo esc_attr( $content_order ); ?> contents-column display-flex flex-direction-column gap-4">
                
                <div class="display-flex flex-direction-column gap-4">
                  
                        <div class="display-flex flex-direction-column gap-2">
                            <?php if ( $title ): ?>
                                <h2 class="no-margin-bottom"><?php echo esc_html( $title ); ?></h2>
                            <?php endif; ?>

                            <div class="display-flex justify-space-between gap-8 <?php echo $footer_button_tccb; ?>">
                                <?php if ( $sub_text ): ?>
                                    <span class="no-margin-bottom-outer size-16 text-grey">
                                        <?php echo wp_kses_post( $sub_text ); ?>
                                    </span>
                                <?php endif; ?>

                                <!-- Top Button -->
                                <?php if ( $button ): ?>
                                    <a class="no-margin-bottom site-btn m-not-wm <?php echo $btn_style.' '.$outward_icon; ?> " 
                                    href="<?php echo esc_url( $button['url'] ); ?>">
                                        <?php echo esc_html( $button['title'] ); ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <!--PROFILEs-->
                            <?php if($enable_profiles) : ?>
                                <div class="profiles-container margin-top-28">
                                    <?php foreach($profiles['profiles'] as $profile) :  
                                            $img = $profile['image_pr'] ?? [];
                                            $name = $profile['name_pr'] ?? '';
                                            $position = $profile['position_pr'] ?? '';
                                            $contact_list = $profile['contact_lists_pr'] ?? [];
                                            $btn = $profile['button_pr'] ?? [];
                                        ?>
                                            <div class="profile display-flex gap-4 flex-wrap">
                                                
                                                <?php if($img) : ?>
                                                    <img class="img-size-130 object-fit-cover" src="<?php echo $img['url']; ?>">
                                                <?php endif; ?>
                                                
                                                <div class="display-flex flex-direction-column gap-1">
                                                    <div class="display-flex flex-direction-column gap-0x">
                                                        <?php if($name) : ?>
                                                            <span class="size-18 weight-700"><?php echo $name; ?></span>
                                                        <?php endif; ?>
                                                        
                                                        <?php if($position) : ?>
                                                            <span><?php echo $position; ?></span>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div>
                                                        <?php if($contact_list) : ?>
                                                            <ul class="list-type-format display-flex flex-direction-column gap-1">
                                                                <?php foreach($contact_list as $list) : 
                                                                $icon = $list['icon'] ?? [];
                                                                $texts = $list['texts'] ?? ''; ?>
                                                                        <li class="display-flex flex-direction-row gap-2">

                                                                        <?php if($icon) : ?>
                                                                            <img src="<?php echo $icon; ?>">
                                                                        <?php endif; ?>

                                                                        <?php if($texts) : ?>
                                                                            <span><?php echo $texts; ?></span>
                                                                        <?php endif; ?>

                                                                        </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        <?php endif; ?>
                                                    </div>

                                                    <?php if($btn) : ?>
                                                        <a class="btn--primary site-btn outward-arrow margin-top-12 m-not-wm" href="<?php echo $btn['url'] ?>"><?php echo $btn['title'] ?></a>
                                                    <?php endif; ?>
                                                </div>
                                                
                                            </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            

                        </div>

                        <!--Pricing Table-->
                        <?php if ($pricing_table_lists_tccb && $enable_pricing_table_tccb) : ?>
                        <div class="pricing-tiers display-flex flex-direction-column gap-3">
                                <div class="display-flex flex-direction-column gap-2">
                                    <?php if($pricing_title_tccb) : ?>
                                        <h4 class="no-margin-bottom"><?php echo esc_html($pricing_title_tccb); ?></h4>
                                    <?php endif; ?>

                                    <?php if($pricing_sub_texts_tccb) : ?>
                                        <p class="no-margin-bottom text-grey"><?php echo wp_kses_post($pricing_sub_texts_tccb); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <?php foreach ($pricing_table_lists_tccb as $pricing) : 
                                        $time = $pricing['time'] ?? '';
                                        $price = $pricing['price'] ?? ''; ?>
                                        <div class="pricing-row">
                                            <span class="time-range text-grey"><?php echo esc_html($time); ?></span>
                                            <span class="price text-grey"><?php echo esc_html($price); ?></span>
                                        </div>
                                    
                                    <?php endforeach; ?>
                                </div>

                        </div>
                        <?php endif; ?>
                    

                </div>

                <?php if($enable_collapsible) : ?>
                <div class="display-flex flex-direction-column gap-6">
                    <?php if(!$enable_collapsible) : ?>
                        <!-- List Section -->
                        <div class="display-flex flex-direction-column gap-2">
                            <?php if ( $list_title ): ?>
                                <span class="list-heading no-margin-bottom">
                                    <?php echo esc_html( $list_title ); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ( $list_items && is_array( $list_items ) ): 
                                $first = true; ?>
                                <div class="two-column-lists display-flex flex-direction-column <?php echo $remove_gap; ?>" style="background-color:<?php echo $list_background_color_tccb; ?>">
                                    <?php foreach ( $list_items as $item ): 
                                        $icon = $item['icon_tccb'] ?? '';
                                        $text = $item['texts_tccb'] ?? '';
                                        $title = $item['title_tccb'] ?? '';

                                        if ( ! $first && !$remove_divider ) : ?>
                                            <div class="divider"><hr class="divider-20"></div>
                                        <?php endif;
                                        $first = false; ?>

                                        <div class="column-list display-flex items-start gap-4 group items-center">
                                            <?php if ( $icon ): ?>
                                                <div class="">
                                                    <img src="<?php echo esc_url( $icon['url'] ); ?>" 
                                                        alt="<?php echo esc_attr( $icon['alt'] ); ?>">
                                                </div>
                                            <?php endif; ?>

                                            <div class="display-flex flex-direction-column gap-2">
                                                <?php if ( $title ): ?>
                                                    <h5 class="no-margin-bottom text-black">
                                                        <?php echo esc_html( $title ); ?>
                                                    </h5>
                                                <?php endif; ?>

                                                <?php if ( $text ): ?>
                                                    <p class="no-margin-bottom size-16 text-grey">
                                                        <?php echo wp_kses_post( $text ); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Collapsible Section -->
                    <?php if ( $enable_collapsible && ! empty( $items ) ) : 
                        $collapsible_id = 'two-column-collapsible-' . uniqid(); ?>
                        <div id="<?php echo esc_attr( $collapsible_id ); ?>" class="display-flex flex-direction-column gap-2 collapsible-wrapper">
                            <?php foreach ( $items as $index => $item ):
                                $question = $item['title_cc_repeater'] ?? '';
                                $answer   = $item['descriptions_cc_repeater'] ?? '';
                                if ( ! $question ) continue;
                            ?>
                                <div class="collapsible-item display-flex flex-direction-column">
                                    <button 
                                        class="collapsible-toggle gap-4 display-flex flex-direction-row items-center justify-space-between"
                                        aria-expanded="false"
                                        aria-controls="answer-<?php echo esc_attr( $collapsible_id . '-' . $index ); ?>"
                                    >
                                        <div class="flex items-start display-flex flex-direction-row gap-4 item-container text-left">
                                            <span class="collapsible-count-number font-uni-sans">
                                                <?php echo sprintf('%02d', $index + 1); ?>
                                            </span>
                                            <span class="question-text text-black text-left weight-400 size-20">
                                                <?php echo esc_html( $question ); ?>
                                            </span>
                                        </div>

                                        <!-- Chevron/X Icon -->
                                        <span class="close-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="7" viewBox="0 0 13 7" fill="none">
                                                <path d="M6.31525 6.31525L0 0H12.6305L6.31525 6.31525Z" fill="#1C1917"/>
                                            </svg>
                                        </span>
                                    </button>

                                    <div 
                                        id="answer-<?php echo esc_attr( $collapsible_id . '-' . $index ); ?>"
                                        class="collapsible-answer"
                                    >
                                        <div class="no-margin-bottom-outer size-16">
                                            <?php echo wp_kses_post( $answer ); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Additional text after list/collapsible -->
                    <?php if ( $another_sub_texts ): ?>
                        <span class="no-margin-bottom-outer size-16">
                            <?php echo wp_kses_post( $another_sub_texts ); ?>
                        </span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            </div>

            
            <?php if($title_footer && $card_items_footer) : ?>
            <div class="display-flex flex-direction-column gap-4">

                <?php if($title_footer) : ?>
                    <span class="green-text-color font-auxpro-bold text-center display-block"><?php echo esc_html($title_footer); ?></span>
                <?php endif; ?>
                <div class="grid columns-3  gap-5">
                <?php foreach ( $card_items_footer as $card_item ): 
                    $card_icon = $card_item['icon_card_item_tccb']; 
                    $card_texts = $card_item['texts_card_item_tccb'];
                    ?>

                    <div class="card-item items-center display-flex flex-direction-column gap-2">
                        <?php if($card_icon) : ?>
                        <img class="img-size-72" src="<?php echo $card_icon['url']; ?>">
                        <?php endif; ?>

                        <?php if($card_texts) : ?>
                        <span><?php echo wp_kses_post($card_texts); ?></span>
                        <?php endif; ?>
                    </div>

                <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<!-- Script (scoped to this module only) -->
<?php if ( $enable_collapsible && ! empty( $items ) ) : ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const collapsibleSection = document.getElementById('<?php echo esc_js( $collapsible_id ); ?>');
    if (!collapsibleSection) return;

    const toggles = collapsibleSection.querySelectorAll('.collapsible-toggle');

    toggles.forEach(toggle => {
        toggle.addEventListener('click', function () {
            const isOpen = this.getAttribute('aria-expanded') === 'true';
            const targetId = this.getAttribute('aria-controls');
            const answer = document.getElementById(targetId);

            if (!answer) return;

            // Toggle the current item
            this.setAttribute('aria-expanded', !isOpen);

            // Optional: Accordion behavior - close others in THIS MODULE only
            if (!isOpen) {
                toggles.forEach(other => {
                    if (other !== this) {
                        other.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });
    });
});
</script>
<?php endif; ?>