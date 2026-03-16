<?php
/**
 * Module: FAQ Collapsible 
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$args      = $args ?? [];
$prefix    = $args['prefix'] ?? '';
$row_index = $args['row_index'] ?? 0;

if ( ! get_sub_field( 'module_enable' ) ) {
    return;
}

$content = get_sub_field('contents_layout');
if ( ! $content ) return;


// ACF Fields
$anchor_tag = $content['anchor_tag'] ?? '';

$two_collumns = $content['two_columns_content_collapsible_c'] ? 'flex-direction-row justify-space-between' : 'flex-direction-columns';
$text_center = $content['texts_center_cc'] ? 'text-center' : '';
$top_text  = $content['top_texts_collapsible_c'] ?? '';
$title     = $content['title_collapsible_c'] ?? '';
$sub_texts = $content['sub_texts_collapsible_c'] ?? '';
$items     = $content['repeater_collapsible_c'] ?? [];
$bg_color = $content['background_color_collapsible_c'] ? 'background-color:'.$content['background_color_collapsible_c'] : '';
$module_id = 'collapsible-contents-' . uniqid();
?>

<section class="collapsible-contents" id="<?php echo esc_attr($module_id); ?>" style="<?php echo $bg_color; ?>" data-anchor="<?php echo $anchor_tag; ?>">
    <div class="container display-flex <?php echo $two_collumns; ?> gap-8">
        <div class="heading-subtexts<?php echo $text_center; ?> display-flex flex-direction-column gap-5">
            <div class="display-flex flex-direction-column gap-3">
                <?php if ($top_text): ?>
                    <p class="no-margin-bottom top-texts">
                        <?php echo esc_html($top_text); ?>
                    </p>
                <?php endif; ?>

                <?php if ($title): ?>
                    <h2 class="no-margin-bottom">
                        <?php echo wp_kses_post($title); ?>
                    </h2>
                <?php endif; ?>
            </div>
            <div>
                <?php if ($sub_texts): ?>
                        <span class="no-margin-bottom-outer size-16">
                        <?php echo wp_kses_post($sub_texts); ?>
                        </span>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($items) : ?>
        <div class="questions display-flex flex-direction-column gap-2">
            <?php foreach ($items as $index => $item):
                $question = $item['title_cc_repeater'] ?? '';
                $answer   = $item['descriptions_cc_repeater'] ?? '';
                if ( ! $question ) continue;
            ?>
                <div class="collapsible-item display-flex flex-direction-column">
                    <button 
                        class="collapsible-toggle gap-4 display-flex flex-direction-row items-center"
                        aria-expanded="false"
                        aria-controls="answer-<?php echo $module_id . '-' . $index; ?>"
                    >
                        <div class="flex items-start display-flex flex-direction-row gap-4 item-container width-max text-left">
                            <span class="collapsible-count-number font-uni-sans">
                                <?php echo sprintf('%02d', $index + 1); ?>
                            </span>
                            <span class="question-text text-black text-left weight-400 size-20">
                                <?php echo esc_html($question); ?>
                            </span>
                        </div>

                        <!-- X Icon -->
                        <span class="close-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="7" viewBox="0 0 13 7" fill="none">
                        <path d="M6.31525 6.31525L0 0H12.6305L6.31525 6.31525Z" fill="#1C1917"/>
                        </svg>
                        </span>
                    </button>

                    <div 
                        id="answer-<?php echo $module_id . '-' . $index; ?>"
                        class="collapsible-answer"
                    >
                        <div class="no-margin-bottom-outer size-16">
                            <?php echo wp_kses_post($answer); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const section = document.getElementById('<?php echo $module_id; ?>');
    const toggles = section.querySelectorAll('.collapsible-toggle');

    toggles.forEach(toggle => {
        toggle.addEventListener('click', function () {
            const isOpen = this.getAttribute('aria-expanded') === 'true';

            // Close all others
            section.querySelectorAll('.faq-toggle').forEach(other => {
                if (other !== this) {
                    other.setAttribute('aria-expanded', 'false');
                }
            });

            // Toggle current
            this.setAttribute('aria-expanded', !isOpen);
        });
    });
});
</script>