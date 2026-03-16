<?php
/**
 * Module Name: Team Grid
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$args      = $args ?? [];
$row_index = $args['row_index'] ?? 0;

if ( ! get_sub_field( 'module_enable' ) ) {
    return;
}

$content   = get_sub_field( 'contents_layout' );
$team_title = $content['team_title'] ?? 'OUR TEAM';
$team_list = $content['team_list'] ?? [];
$team_bg_color = $content['team_bg_color'] ?? '#fff';

if ( empty( $team_list ) || ! is_array( $team_list ) ) {
    return;
}


$module_id     = 'team-grid-' . $row_index;
?>

<section id="<?php echo esc_attr( $module_id ); ?>" class="team-grid" style="background-color:<?php echo $team_bg_color; ?>">
    <div class="container display-flex flex-direction-column gap-8">
        
        <?php if ( $team_title ) : ?>
            <h2 class="text-center no-margin-bottom">
                <?php echo esc_html( $team_title ); ?>
            </h2>
        <?php endif; ?>

        <div class="grid columns-3 gap-6 width-fit margin-auto">
            <?php foreach ( $team_list as $member ) : 
                $image     = $member['team_image'] ?? false;
                $name      = $member['team_name'] ?? '';
                $position  = $member['team_position'] ?? '';
                $linkedin  = $member['team_linkedin'] ?? '';
                $description = $member['team_description'] ?? '';

                $sanitized_description = wp_kses_post( $description );
                $encoded_description = base64_encode( $sanitized_description );

                // Handle image (supports both array and URL string)
                $image_url = '';
                $image_alt = esc_attr( $name );
                if ( is_array( $image ) && ! empty( $image['url'] ) ) {
                    $image_url = $image['url'];
                    $image_alt = $image['alt'] ?: $image_alt;
                } elseif ( is_string( $image ) && filter_var( $image, FILTER_VALIDATE_URL ) ) {
                    $image_url = $image;
                }

                // Fallback placeholder
                if ( empty( $image_url ) ) {
                    $image_url = 'https://via.placeholder.com/400x500/eeeeee/999999?text=' . urlencode( $name );
                }
            ?>
                <div class="team-member group display-flex flex-direction-column gap-3" data-description="<?php echo esc_attr( $encoded_description ); ?>">
                    <div class="relative">
                        <img 
                            src="<?php echo esc_url( $image_url ); ?>" 
                            alt="<?php echo esc_attr( $image_alt ); ?>"
                            class="radius-6"
                            loading="lazy"
                        >

                        <?php if ( $linkedin ) : ?>
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer"
                                   class="bg-white p-3 rounded-full hover:bg-gray-100 transition">
                                    <svg class="w-7 h-7 text-green-700" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                    </svg>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="display-flex flex-direction-row justify-space-between">
                        <div class="display-flex flex-direction-column gap-1">
                        <span class="text-left">
                            <?php echo esc_html( $name ); ?>
                        </span>
                        <p class="size-16 text-grey text-left no-margin-bottom">
                            <?php echo esc_html( $position ); ?>
                        </p>
                        </div>
                        <?php if ( $linkedin ) : ?>
                        
                            <a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer"
                            class="bg-green social-link radius-6">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path d="M10.2857 1C10.6652 1 11 1.33482 11 1.73661V10.2857C11 10.6875 10.6652 11 10.2857 11H1.69196C1.3125 11 1 10.6875 1 10.2857V1.73661C1 1.33482 1.3125 1 1.69196 1H10.2857ZM4.01339 9.57143V4.81696H2.54018V9.57143H4.01339ZM3.27679 4.14732C3.74554 4.14732 4.125 3.76786 4.125 3.29911C4.125 2.83036 3.74554 2.42857 3.27679 2.42857C2.78571 2.42857 2.40625 2.83036 2.40625 3.29911C2.40625 3.76786 2.78571 4.14732 3.27679 4.14732ZM9.57143 9.57143V6.95982C9.57143 5.6875 9.28125 4.68304 7.78571 4.68304C7.07143 4.68304 6.58036 5.08482 6.37946 5.46429H6.35714V4.81696H4.95089V9.57143H6.42411V7.22768C6.42411 6.60268 6.53571 6 7.31696 6C8.07589 6 8.07589 6.71429 8.07589 7.25V9.57143H9.57143Z" fill="white"/>
                                    </svg>
                                </span>
                            </a>
                        
                        <?php endif; ?>
                    </div>

                    <div>
                        <span class="icon-arrow text-decoration-none">Read more</span>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>

</style>

<div id="<?php echo esc_attr( $module_id . '-modal' ); ?>" class="modal">
    <div class="modal-content display-flex flex-direction-column">
        <span class="close">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                <path d="M0.878125 11.0896L0 10.2115L4.66667 5.54479L0 0.878126L0.878125 0L5.54479 4.66667L10.2115 0L11.0896 0.878126L6.42292 5.54479L11.0896 10.2115L10.2115 11.0896L5.54479 6.42292L0.878125 11.0896Z" fill="#F9FAFB"/>
            </svg>
        </span>

        <!-- Updated structure for flow -->
        <div class="modal-inner-content padding-8">
            <!-- Image (floated) -->
            <div class="modal-image-wrapper">
                <img id="modal-image" 
                     src="" 
                     alt="" 
                     class="radius-8" 
                     style="width: 260px; height: 320px; object-fit: cover;">
            </div>

            <!-- All text content (will flow around and below image) -->
            <div class="modal-text-content display-flex flex-direction-column gap-5">
                <div class="display-flex flex-direction-row justify-space-between items-start">
                    <div class="display-flex flex-direction-column gap-0x">
                        <span id="modal-name" class="modal-name size-24 no-margin-bottom"></span>
                        <p id="modal-position" class="modal-position size-18 no-margin-bottom"></p>
                    </div>
                    <a id="modal-linkedin" 
                       href="" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       style="display: none;"
                       class="bg-green social-link radius-6 p-3 hover:bg-green-600 transition">
                        <svg width="16" height="16" viewBox="0 0 12 12" fill="white" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.2857 1C10.6652 1 11 1.33482 11 1.73661V10.2857C11 10.6875 10.6652 11 10.2857 11H1.69196C1.3125 11 1 10.6875 1 10.2857V1.73661C1 1.33482 1.3125 1 1.69196 1H10.2857ZM4.01339 9.57143V4.81696H2.54018V9.57143H4.01339ZM3.27679 4.14732C3.74554 4.14732 4.125 3.76786 4.125 3.29911C4.125 2.83036 3.74554 2.42857 3.27679 2.42857C2.78571 2.42857 2.40625 2.83036 2.40625 3.29911C2.40625 3.76786 2.78571 4.14732 3.27679 4.14732ZM9.57143 9.57143V6.95982C9.57143 5.6875 9.28125 4.68304 7.78571 4.68304C7.07143 4.68304 6.58036 5.08482 6.37946 5.46429H6.35714V4.81696H4.95089V9.57143H6.42411V7.22768C6.42411 6.60268 6.53571 6 7.31696 6C8.07589 6 8.07589 6.71429 8.07589 7.25V9.57143H9.57143Z"/>
                        </svg>
                    </a>
                </div>

                <!-- Long description - will flow under image if needed -->
                <div id="modal-description" class="modal-description line-height-18 text-grey-700">
                    <!-- HTML goes here via innerHTML -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var modal = document.getElementById('<?php echo esc_attr( $module_id . '-modal' ); ?>');
  var closeBtn = modal.querySelector('.close');

  closeBtn.onclick = function() {
    modal.style.display = 'none';
  }

  window.onclick = function(event) {
    if (event.target === modal) {
      modal.style.display = 'none';
    }
  }

  function base64ToUtf8(base64) {
    const binString = atob(base64);
    const bytes = new Uint8Array(binString.length);
    for (let i = 0; i < binString.length; i++) {
      bytes[i] = binString.charCodeAt(i);
    }
    return new TextDecoder('utf-8').decode(bytes);
  }

  var members = document.querySelectorAll('.team-member');
  members.forEach(function(member) {
    member.addEventListener('click', function(event) {
      if (event.target.closest('a')) {
        return; // Allow link to be followed without opening modal
      }

      var imgSrc = member.querySelector('img').src;
      var name = member.querySelector('span').textContent.trim();
      var position = member.querySelector('p').textContent.trim();
      var encoded = member.dataset.description || '';
      var description = base64ToUtf8(encoded);
      var linkedin = member.querySelector('.social-link') ? member.querySelector('.social-link').href : '';

      document.getElementById('modal-image').src = imgSrc;
      document.getElementById('modal-name').textContent = name;
      document.getElementById('modal-position').textContent = position;
      document.getElementById('modal-description').innerHTML = description;

      var linkedinEl = document.getElementById('modal-linkedin');
      if (linkedin) {
        linkedinEl.href = linkedin;
        linkedinEl.style.display = 'inline-block';
      } else {
        linkedinEl.style.display = 'none';
      }

      modal.style.display = 'block';
    });
  });
});
</script>