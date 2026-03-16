<?php
/**
 * Module: Card Items
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$args      = $args ?? [];
$prefix    = $args['prefix'] ?? '';
$row_index = $args['row_index'] ?? 0;

if ( ! get_sub_field( 'module_enable' ) ) {
    return;
}

$content = get_sub_field('contents_layout');

$enable_global = get_sub_field( 'use_global' );

if ( $enable_global ) {
    $content = get_field('card_items_fields','option');
    
}

// Fields
$bgcolor = $content['card_items_background_color'] ? 'background-color:'.$content['card_items_background_color'].';' : '';
$top_text    = $content['card_items_top_text'] ?? '';
$title       = $content['card_items_title'] ?? '';
$description = $content['card_items_description'] ?? '';
$cards       = $content['card_items_repeater'] ?? [];
$card_item_title_color = $content['card_items_text_color'] ?? '';
$card_columns = $content['card_items_columns'] ?? 6;
$columns_mobile = $content['card_items_columns_mobile'] ?? 2;
$columns_tablet = $content['card_items_columns_tablet'] ?? 3;

$pad_size = ($top_text || $title) ? : 'padding:20px 0;';

[$text_center, $margin_auto] = $content['card_items_texts_center'] ? ['text-center', 'margin-auto'] : ['text-left', ''];

if ( empty( $cards ) || ! is_array( $cards ) ) return;
?>

<section class="card-items-section card-items-<?php echo esc_attr( $row_index ).' '.esc_html($text_center); ?>" style="<?php echo $bgcolor;?> <?php echo $pad_size; ?>">
    <div class="container display-flex flex-direction-column gap-8">
        <?php if($top_text || $title || $description) : ?>
            <div class="display-flex flex-direction-column gap-5 top-section-container <?php echo esc_html($margin_auto); ?>">
                <div class="display-flex flex-direction-column gap-3">
                <?php if ( $top_text ) : ?>
                    <p class="top-texts no-margin-bottom <?php echo esc_html($text_center); ?>"><?php echo esc_html( $top_text ); ?></p>
                <?php endif; ?>

                <?php if ( $title ) : ?>
                    <h2 class="card-items__title no-margin-bottom <?php echo esc_html($text_center); ?>"><?php echo wp_kses_post( $title ); ?></h2>
                <?php endif; ?>
                </div>

                <?php if ( $description ) : ?>
                    <div class="card-items__description <?php echo esc_html($text_center); ?>">
                        <?php echo wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="card-items__grid gap-5 grid columns-<?php echo $card_columns; ?> mobile-columns-<?php echo $columns_mobile; ?> md-columns-<?php echo $columns_tablet; ?>">
            <?php foreach ( $cards as $card ) :
                $icon_array = $card[ 'card_items_icon_repeater' ] ?? false;
                $card_title = $card[ 'card_items_title_repeater' ] ?? '';
                $card_desc  = $card[ 'card_items_description_repeater' ] ?? '';
                $group_title = $card['card_group_list_title'] ?? '';
                $group_list = $card['card_items_group_list-items'] ?? [];
                $group_footer_texts = $card['card_items_footer_texts'] ?? '';
                $card_items_bg_image = $card['card_items_bg_image'] ?? '';
                $button = $card['card_items_button'] ?? '';

                $anchor_link = $card['card_items_anchor_link'] ? 'data-target="'.$card['card_items_anchor_link'].'"' : '';

                $has_bg = $card_items_bg_image ? 'has-bg' : '';
                
                $card_items_bg_image_style = $card_items_bg_image ? "background: linear-gradient(180deg, rgba(0, 0, 0, 0.00) 67.5%, rgba(0, 0, 0, 0.60) 100%), url(".$card_items_bg_image['url'].") lightgray 50% / cover no-repeat;" : '';
                
            ?>

                <a href="<?php echo $button ? $button['url'] : '#'; ?>" <?php echo $anchor_link; ?> class="card-item gap-1x image-border-radius <?php echo $has_bg; ?> display-flex flex-direction-column items-start radius-6 justify-end items-center text-decoration-none" style="<?php echo $card_items_bg_image_style; ?>">
  
                        <?php if ( $icon_array && ! empty( $icon_array['url'] ) ) : ?>
                            <div class="card-item__icon">
                                <img src="<?php echo esc_url( $icon_array['url'] ); ?>"
                                    alt="<?php echo esc_attr( $card_title ); ?>"
                                    width="80" height="80">
                            </div>
                        <?php endif; ?>

                        <?php if ( $card_title ) : ?>
                            <h4 class="card-item__title size-18 weight-600 no-margin-bottom text-center <?php echo $card_item_title_color; ?>"><?php echo esc_html( $card_title ); ?></h4>
                        <?php endif; ?>

                        <?php if ( $card_desc ) : ?>
                            <div class="card-item__desc text-center <?php echo $card_item_title_color; ?>"><?php echo wp_kses_post( $card_desc ); ?></div>
                        <?php endif; ?>

                        <?php if ( $button ) : ?>
                            <span href="<?php echo $button['url']; ?>" class="card-item__button <?php echo $card_item_title_color; ?> text-decoration-none btn--primary btn-outline size-16"><?php echo $button['title']; ?></span>
                        <?php endif; ?>

                        <?php if($group_list) : ?>
                            <div class="list-items display-flex flex-direction-column gap-2">
                                <?php if($group_title) : ?>  
                                    <span class="group-list-heading text-left display-block green-text-color"><?php echo $group_title; ?></span>
                                <?php endif; ?>
                                <ul class="list-type-format display-flex flex-direction-column gap-2">
                                    <?php foreach($group_list as $list) : 
                                        $list_item_icon = $list['icon'];
                                        $list_item_text = $list['text']?>
                                        <?php if($list_item_icon && $list_item_text) : ?>
                                        <li class="card-group-list-item display-flex flex-direction-row gap-2"><img src="<?php echo esc_url($list_item_icon['url']) ?>"><label class="text-left"><?php echo esc_html($list_item_text); ?></label></li>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if($group_footer_texts) : ?>
                            <div>
                                <span><?php echo $group_footer_texts; ?></span>
                            </div>
                        <?php endif; ?>

                    </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<script>
document.querySelectorAll('a[data-target]').forEach(link => {
  link.addEventListener('click', function(e) {
    e.preventDefault();
    const target = document.querySelector(`[data-anchor="${this.dataset.target}"]`);
    if (target) {
      target.scrollIntoView({ behavior: 'smooth' });
    }
  });
});
</script>