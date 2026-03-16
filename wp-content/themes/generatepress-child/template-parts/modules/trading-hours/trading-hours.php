<?php
/**
 * Module: Trading Hours Tabs
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$args      = $args ?? [];
$prefix    = $args['prefix'] ?? '';
$row_index = $args['row_index'] ?? 0;

// Disable module if unchecked
if ( ! get_sub_field( 'module_enable' ) ) {
    return;
}

$content = get_sub_field( 'contents_layout' );

if ( ! $content ) {
    return;
}

$anchor_tag = $content['anchor_tag'] ?? '';


$bgcolor     = $content['bgcolor_tht'] ?? '#ffffff'; // background color
$two_columns = $content['two_columns_tht'] ? 'flex-direction-row' : 'flex-direction-column';
$title       = $content['title_tht'] ?? '';
$description = $content['description_tht'] ?? '';

// Tab content repeaters
$general_hours   = $content['general_hours_tht'] ?? [];     // repeater: day, hours
$major_hours     = $content['major_hours_tht'] ?? [];       // repeater
$special_hours   = $content['special_hours_tht'] ?? [];     // repeater

$module_id = 'trading-hours-tabs-' . uniqid();


$text_align = $content['two_columns_tht'] ? '' : 'text-center';
?>

<section class="trading-hours-tabs-module" style="background-color:<?php echo esc_attr( $bgcolor ); ?>" data-anchor="<?php echo $anchor_tag; ?>">
    <div class="container display-flex <?php echo $two_columns; ?> gap-8">
        
        
        <!-- Header: Title + Description -->
        <?php if ( $title || $description ): ?>
        <div class="texts-container <?php echo $text_align; ?> display-flex flex-direction-column gap-4">
            <?php if ( $title ): ?>
                <h2 class="no-margin-bottom"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>

            <?php if ( $description ): ?>
                <span class="size-16 text-grey no-margin-bottom-outer max-width-800 mx-auto">
                    <?php echo wp_kses_post( $description ); ?>
                </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
            
        <div class="trading-hours-container width-max">
            <!-- Tabs Navigation -->
            <div class="tabs-wrapper" role="tablist" aria-label="Trading Hours Tabs">
                <div class="tabs-nav display-flex justify-space-between gap-8 border-bottom-thick">
                    <button 
                        class="tab-button active" 
                        data-tab="general" 
                        aria-selected="true" 
                        role="tab"
                        id="<?php echo esc_attr( $module_id ); ?>-tab-general"
                        aria-controls="<?php echo esc_attr( $module_id ); ?>-panel-general"
                    >
                        General Hours
                    </button>
                    <button 
                        class="tab-button" 
                        data-tab="major" 
                        aria-selected="false" 
                        role="tab"
                        id="<?php echo esc_attr( $module_id ); ?>-tab-major"
                        aria-controls="<?php echo esc_attr( $module_id ); ?>-panel-major"
                    >
                        Major Trading Hours
                    </button>
                    <?php if($special_hours) :  ?>
                    <button 
                        class="tab-button" 
                        data-tab="special" 
                        aria-selected="false" 
                        role="tab"
                        id="<?php echo esc_attr( $module_id ); ?>-tab-special"
                        aria-controls="<?php echo esc_attr( $module_id ); ?>-panel-special"
                    >
                        Special Trading Hours
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tab Panels -->
            <div class="tab-panels">

                <!-- General Hours Tab -->
                <div 
                    class="tab-panel active" 
                    id="<?php echo esc_attr( $module_id ); ?>-panel-general"
                    role="tabpanel" 
                    aria-labelledby="<?php echo esc_attr( $module_id ); ?>-tab-general"
                >
                    <?php if ( ! empty( $general_hours ) ): ?>
                    <div class="hours-table">
                        <?php foreach ( $general_hours as $row ): 
                            $day    = $row['day_tht'] ?? '';
                            $hours  = $row['hours_tht'] ?? '';
                            $highlight = ! empty( $row['highlight_row_tht'] );
                            if ( ! $day ) continue;
                        ?>
                            <div class="table-row display-flex justify-space-between items-center <?php echo $highlight ? 'highlighted-row' : ''; ?>">
                                <span class="day-label text-grey"><?php echo esc_html( $day ); ?></span>
                                <span class="hours-label text-grey"><?php echo esc_html( $hours ); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                        <p class="text-center size-16">No general hours specified.</p>
                    <?php endif; ?>
                </div>

                <!-- Major Trading Hours Tab -->
                <div 
                    class="tab-panel" 
                    id="<?php echo esc_attr( $module_id ); ?>-panel-major"
                    role="tabpanel" 
                    aria-labelledby="<?php echo esc_attr( $module_id ); ?>-tab-major"
                    hidden
                >
                    <?php if ( ! empty( $major_hours ) ): ?>
                    <div class="hours-table">
                        <?php foreach ( $major_hours as $row ): 
                            $day    = $row['day_tht'] ?? '';
                            $hours  = $row['hours_tht'] ?? '';
                            $highlight = ! empty( $row['highlight_row_tht'] );
                            if ( ! $day ) continue;
                        ?>
                            <div class="table-row display-flex justify-space-between items-center <?php echo $highlight ? 'highlighted-row' : ''; ?>">
                                <span class="day-label font-auxpro-medium"><?php echo esc_html( $day ); ?></span>
                                <span class="hours-label"><?php echo esc_html( $hours ); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                        <p class="text-center size-16">No major trading hours specified.</p>
                    <?php endif; ?>
                </div>

                <!-- Special Trading Hours Tab -->
                <div 
                    class="tab-panel" 
                    id="<?php echo esc_attr( $module_id ); ?>-panel-special"
                    role="tabpanel" 
                    aria-labelledby="<?php echo esc_attr( $module_id ); ?>-tab-special"
                    hidden
                >
                    <?php if ( ! empty( $special_hours ) ): ?>
                    <div class="hours-table">
                        <?php foreach ( $special_hours as $row ): 
                            $day    = $row['day_tht'] ?? '';
                            $hours  = $row['hours_tht'] ?? '';
                            $highlight = ! empty( $row['highlight_row_tht'] );
                            if ( ! $day ) continue;
                        ?>
                            <div class="table-row display-flex justify-space-between items-center <?php echo $highlight ? 'highlighted-row' : ''; ?>">
                                <span class="day-label font-auxpro-medium"><?php echo esc_html( $day ); ?></span>
                                <span class="hours-label"><?php echo esc_html( $hours ); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                        <p class="text-center size-16">No special trading hours specified.</p>
                    <?php endif; ?>
                </div>

            </div>
        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const module = document.querySelector('.trading-hours-tabs-module');
    if (!module) return;

    const buttons = module.querySelectorAll('.tab-button');
    const panels = module.querySelectorAll('.tab-panel');

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const targetTab = this.getAttribute('data-tab');

            // Update active state on buttons
            buttons.forEach(btn => {
                btn.classList.remove('active');
                btn.setAttribute('aria-selected', 'false');
            });
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');

            // Show/hide panels
            panels.forEach(panel => {
                const panelTab = panel.id.split('-').pop(); // gets "general", "major", or "special"

                if (panelTab === targetTab) {
                    panel.classList.add('active');
                    panel.removeAttribute('hidden');
                } else {
                    panel.classList.remove('active');
                    panel.setAttribute('hidden', 'hidden');
                }
            });
        });
    });
});
</script>