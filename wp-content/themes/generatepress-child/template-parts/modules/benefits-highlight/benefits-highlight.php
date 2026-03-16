<?php
/**
 * Module: Benefits Highligh
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$args      = $args ?? [];
$prefix    = $args['prefix'] ?? '';
$row_index = $args['row_index'] ?? 0;

if ( ! get_sub_field( 'module_enable' ) ) {
    return;
}

$content = get_sub_field('contents_layout');

// Safety check
if ( ! $content ) {
    return;
}

// Get values from the cloned group
$bg_image     = $content['background_bh_m'] ?? '';
$top_text     = $content['top_text_bh_m'] ?? '';
$title        = $content['title_bh_m'] ?? '';
$description  = $content['description_bh_m'] ?? '';
$columns      = $content['columns_per_row_bh_m'] ?? 1; // default 1
$content_alignment  = $content['content_alignment_bh_m'] ?? '';
$items        = $content['repeater_bh_m'] ?? []; 

$altgreen_bold_text = $content['green_color_bold_text_bh_m'] ? 'green-text-color-v1 font-uni-sans size-32' : 'font-auxpro-regular green-text-color size-20';

$remove_divider = $content['remove_divider_bh_m'] ?? '';
$gap_dynamic = $remove_divider ? 'gap-6' : 'gap-0';
$wider_width = $content['wider_width_bh_m'] ? 'max-width:682px;' : '';
// CSS classes based on columns
$column_class = 'cols-' . $columns;
$grid_class   = 'grid '.$gap_dynamic.' columns-' . $columns;

?>
<section class="benefits-highlight" class="bg-cover bg-center" style="background: url(<?php echo esc_url($bg_image['url']); ?>) lightgray 50% / cover no-repeat;
">

    <div class="container">
        <div class="contents align-<?php echo esc_html($content_alignment) ?> gap-7 display-flex flex-direction-column" style="<?php echo $wider_width ?>">
        <?php if ($description) : ?>
            <div class="display-flex flex-direction-column gap-5">
                <div class="display-flex flex-direction-column gap-3">
                <?php if ($top_text) : ?>
                    <p class="top-texts no-margin-bottom">
                        <?php echo esc_html($top_text); ?>
                    </p>
                <?php endif; ?>

                <?php if ($title) : ?>
                    <h2 class="no-margin-bottom">
                        <?php echo esc_html($title); ?>
                    </h2>
                <?php endif; ?>
                </div>
                <div class="no-margin-bottom-outer">
                <?php echo wp_kses_post($description); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($items && is_array($items)) : ?>
            <div class="<?php echo esc_attr($grid_class); ?>">
                <?php foreach ($items as $index => $item) :
                    $icon       = $item['icon_bh_m_repeater'] ?? '';
                    $item_title = $item['title_bh_m'] ?? '';
                    $sub_text   = $item['sub_texts_bh_m'] ?? '';

                   
                    $is_last = ($index === count($items) - 1);
                    ?>

                    <div class="benefit-item display-flex flex-direction-row gap-3">
                        <?php if ($icon) : ?>
                            <div class="">
                                <img src="<?php echo esc_url($icon['url']); ?>" 
                                    alt="<?php echo esc_attr($icon['alt'] ?: $item_title); ?>" 
                                    class="object-contain">
                            </div>
                        <?php endif; ?>

                        <div class="flex-80">
                            <?php if ($item_title) : ?>
                                <span class="<?php echo esc_html($altgreen_bold_text); ?>"><?php echo esc_html($item_title); ?></span>
                            <?php endif; ?>

                            <?php if ($sub_text) : ?>
                                <p class="no-margin-bottom"><?php echo esc_html($sub_text); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                   
                    <?php if (! $is_last && ! $remove_divider) : ?>
                        <div class="divider">
                            <hr class="divider-line">
                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php
        // Optional: Footer repeater (you have another one at the bottom)
        $footer_columns = $content['columns_per_row_footer_bh_m'] ?? 1;
        $footer_items = $content['repeater_footer_bh_m'] ?? [];
        $footer_title = $content['title_footer_bh_m'] ?? '';

        if ($footer_items && is_array($footer_items)) :
            ?>
            <div class="benefits-footer">
                <div class="display-flex flex-direction-column gap-2">
                    <?php if ($footer_title) : ?>
                        <span class="green-text-color size-16 font-auxpro-bold"><?php echo esc_html($footer_title); ?></span>
                    <?php endif; ?>
                    <div class="grid columns-<?php echo esc_html($footer_columns) ?> gap-2 column-gap-6">
                        <?php foreach ($footer_items as $f) :
                            $f_icon = $f['icon_bh_m_repeater'] ?? '';
                            $f_title = $f['title_bh_m_repeater'] ?? '';
                            
                            
                            ?>
                            <div class="display-flex gap-2">
                                <?php if ($f_icon) : ?>
                                    <img src="<?php echo esc_url($f_icon['url']); ?>" class="icon-check" alt="">
                                <?php endif; ?>
                                <?php if ($f_title) : ?><span class="text-black size-16"><?php echo esc_html($f_title); ?></span><?php endif; ?>
                                
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        </div>
    </div>
</section>