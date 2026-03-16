<?php
/**
 * Module: Enquiry Form (with Gravity Forms integration)
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

$bgcolor    = $content['background_color_ef'] ?? ''; // Light blue to match screenshot
$formID     = $content['gravity_form_id_ef'] ?? 0;
$formTitle  = $content['form_title_ef'] ?? '';
$subTexts   = $content['sub_texts_ef'] ?? '';
?>

<section class="enquiry-form-module" style="background-color: <?php echo esc_attr( $bgcolor ); ?>;">
    <div class="container">
        <div class="display-flex flex-direction-column gap-4">
            <div class="display-flex flex-direction-column gap-2">
                <?php if ( $formTitle ) : ?>
                    <h4 class="text-center no-margin-bottom">
                        <?php echo esc_html( $formTitle ); ?>
                    </h4>
                <?php endif; ?>

                <?php if ( $subTexts ) : ?>
                    <span class="text-center text-grey no-margin-bottom-outer">
                        <?php echo wp_kses_post( $subTexts ); ?>
                    </span>
                <?php endif; ?>
            </div>
            <?php 
            // Only render Gravity Form if a valid ID is selected and Gravity Forms is active
            if ( $formID && class_exists( 'GFForms' ) ) :
                // Parameters: 
                // 1. Form ID
                // 2. Display title? false (we already show custom title above)
                // 3. Display description? false
                // 4. Display inactive? false
                // 5. Field values (pre-populate) - empty
                // 6. Use AJAX? true (smooth submission without page reload)
                // 7. Tabindex - let GF handle it
                gravity_form( 
                    $formID, 
                    false,      // display_title
                    false,      // display_description
                    false,      // display_inactive
                    null,       // field_values
                    true,       // ajax_enabled
                    1           // tabindex
                ); 
            else : ?>
                <p class="text-center text-red-600">
                    <?php echo esc_html__( 'No Gravity Form selected or Gravity Forms plugin is not active.', 'your-textdomain' ); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.enquiry-form-module{padding:60px;}
.enquiry-form-module .container{
    padding:40px;

    max-width:620px;
    width:100%;
    border-radius: 8px; 
    background:  #F3F7FB;
}
.enquiry-form-module .gform-footer{margin-top:20px!important;}
@media(max-width:768px){
    .enquiry-form-module{padding:60px 16px;}
    body .enquiry-form-module .container{padding:40px 28px!important;}
}
</style>