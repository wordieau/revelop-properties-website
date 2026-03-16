<?php
/**
 * Module: CTA 
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$args      = $args ?? [];
$prefix    = $args['prefix'] ?? '';


if ( ! get_sub_field( 'module_enable' ) ) {
    return;
}

$content = get_sub_field('contents_layout');

$enable_global = get_sub_field( 'use_global' );

if ( $enable_global ) {
    $content = get_field('cta_fields','option');
    
}

if ( ! $content ) return;

// ACF Fields from your "CTA - MODULE" group
$bg_image = $content['background_cta'] ?? '';
$text_center = $content['text_center_cta'] ? 'text-center' : '';
$title    = $content['title_cta'] ?? '';
$subtitle = $content['sub_texts_cta'] ?? '';
$button   = $content['button_cta'] ?? []; 

$lists_cta_repeater = $content['lists_cta'] ?? [];


$anoter_sub_texts_cta = $content['anoter_sub_texts_cta'] ?? '';

 // safety
?>
<?php if(!$title && !$subtitle && !$button) return; ?>
<section class="cta-module relative bg-cover bg-center" style="
background: linear-gradient(0deg, rgba(0, 0, 0, 0.20) 0%, rgba(0, 0, 0, 0.20) 100%), url(<?php echo esc_url($bg_image['url']); ?>) lightgray 50% / cover no-repeat;
">


    <!-- Centered White Card -->
    <div class="container relative zindex-1">
        <div class="<?php echo esc_html($text_center); ?>">
            <div class="inner-container bg-white radius-6 display-flex flex-direction-column gap-5 items-center">
                <div class="display-flex flex-direction-column gap-6">
                    <div class="display-flex flex-direction-column gap-5">
                        <?php if ($title): ?>
                            <h3 class="no-margin-bottom">
                                <?php echo wp_kses_post($title); ?>
                            </h3>
                        <?php endif; ?>

                        <?php if ($subtitle): ?>
                            <div class="no-margin-bottom-outer">
                                <?php echo wp_kses_post($subtitle); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if($lists_cta_repeater) : ?>
                        <div>
                            <ul class="list-type-format display-flex flex-direction-column gap-2">
                                <?php foreach($lists_cta_repeater as $list) : 
                                    $list_icon = $list['icon_cta_repeater'] ?? '';
                                    $list_texts = $list['texts_cta_repeater'] ?? ''; 

                                    if($list_icon || $list_texts) : ?>
                                    <li class="display-flex flex-direction-row gap-2">

                                        <?php if($list_icon) : ?>
                                            <img src="<?php echo $list_icon['url']; ?>">
                                        <?php endif; ?>

                                        <?php if($list_texts) : ?>
                                            <span><?php echo esc_html($list_texts) ?></span>
                                        <?php endif; ?>

                                    </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($anoter_sub_texts_cta): ?>
                        <div class="no-margin-bottom-outer">
                            <?php echo wp_kses_post($anoter_sub_texts_cta); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($button && $button['url'] && $button['title']): ?>
                    <a 
                        href="<?php echo esc_url($button['url']); ?>"
                        target="<?php echo esc_attr($button['target'] ?? '_self'); ?>"
                        class="width-fit site-btn btn--primary"
                    >
                        <?php echo esc_html($button['title']); ?>

                    </a>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>