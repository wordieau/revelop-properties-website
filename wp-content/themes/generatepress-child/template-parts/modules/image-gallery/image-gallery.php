<?php
/**
 * Module: Image Gallery Row + Animated Vanilla Lightbox + Mobile Slider with Splide.js
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$args      = $args ?? [];
$prefix    = $args['prefix'] ?? '';
$row_index = $args['row_index'] ?? 0;

if ( ! get_sub_field( 'module_enable' ) ) {
    return;
}

$content = get_sub_field('contents_layout');
$gallery = $content['gallery'] ?? [];
$columns = $content['columns'] ?? '1';

$container_padding = $content['remove_containers_padding'] ? 'padding-0' : '';

if ( ! $gallery || ! is_array($gallery) ) {
    return;
}

$splide_id = 'gallery-splide-' . uniqid();
$gallery_id = 'gallery-' . uniqid();
?>

<style>
/* Hover effect on gallery items */
.gallery-item:hover img {
    transform: scale(1.03) !important;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2) !important;
}
#<?php echo esc_attr($splide_id); ?> .splide__slide {
        display: flex;
        justify-content: center;
    }
/* Mobile slider adjustments */
@media (max-width: 767px) {

    #<?php echo esc_attr($splide_id); ?> .gallery-item {
        width: 100%;
    }
}
</style>

<section class="image-gallery-row <?php echo esc_html($container_padding); ?>" id="<?php echo esc_attr($gallery_id); ?>">
    <div class="container">
        <div class="gallery-contents display-flex flex-direction-column gap-7">
            <!-- Splide carousel -->
            <div id="<?php echo esc_attr($splide_id); ?>" class="splide" data-splide='{"type":"loop"}'>
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php foreach ($gallery as $index => $image) :
                            $full_url  = esc_url($image['url']);
                            $thumb_url = $image['sizes']['large'] ?? $full_url;
                            $alt       = esc_attr($image['alt'] ?: ($image['title'] ?? ''));
                            $caption   = !empty($image['caption']) ? esc_html($image['caption']) : '';
                        ?>
                            <li class="splide__slide">
                                <div class="gallery-item" style="cursor: pointer;">
                                    <img src="<?php echo $thumb_url; ?>"
                                         alt="<?php echo $alt; ?>"
                                         class="object-cover"
                                         onclick="openLightbox(event, '<?php echo $gallery_id; ?>', <?php echo $index; ?>)"
                                         data-full="<?php echo $full_url; ?>"
                                         data-caption="<?php echo $caption; ?>"
                                         style="transition:transform .3s, box-shadow .3s; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.1); width: 100%;">
                                    <?php if ($caption): ?>
                                        <p style="text-align:center; margin-top:8px; font-size:0.9rem; color:#555;">
                                            <?php echo $caption; ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Animated Lightbox Overlay (unchanged) -->
<div id="animatedLightbox" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.98); z-index:99999; opacity:0; transition:opacity .4s ease; overflow:hidden;">
    <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; transform:scale(0.9); transition:transform .4s cubic-bezier(0.25,0.8,0.25,1);">
        <img id="lbImg" src="" alt="" style="max-width:94vw; max-height:94vh; object-fit:contain; border-radius:12px; box-shadow:0 20px 60px rgba(0,0,0,0.6); opacity:0; transform:scale(0.8); transition:all .5s cubic-bezier(0.25,0.8,0.25,1);">
    </div>

    <!-- Caption -->
    <div id="lbCaption" style="position:absolute; bottom:2rem; left:50%; transform:translateX(-50%); color:white; font-size:1.2rem; font-weight:300; max-width:90%; text-align:center; opacity:0; transition:opacity .4s ease .2s;"></div>

    <!-- Close Button -->
    <button onclick="closeLightbox()" style="position:absolute; top:20px; right:20px; background:rgba(255,255,255,0.1); backdrop-filter:blur(10px); border:none; width:50px; height:50px; border-radius:50%; color:white; font-size:28px; cursor:pointer; transition:all .3s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">×</button>

    <!-- Prev / Next Arrows -->
    <button id="prevBtn" onclick="changeImage(-1)" style="position:absolute; left:20px; top:50%; transform:translateY(-50%); background:rgba(255,255,255,0.1); backdrop-filter:blur(10px); border:none; width:60px; height:60px; border-radius:50%; color:white; font-size:36px; cursor:pointer; opacity:0.7; transition:all .3s;" onmouseover="this.style.opacity=1; this.style.transform='translateY(-50%) scale(1.1)'" onmouseout="this.style.opacity=0.7; this.style.transform='translateY(-50%) scale(1)'">‹</button>
    <button id="nextBtn" onclick="changeImage(1)" style="position:absolute; right:20px; top:50%; transform:translateY(-50%); background:rgba(255,255,255,0.1); backdrop-filter:blur(10px); border:none; width:60px; height:60px; border-radius:50%; color:white; font-size:36px; cursor:pointer; opacity:0.7; transition:all .3s;" onmouseover="this.style.opacity=1; this.style.transform='translateY(-50%) scale(1.1)'" onmouseout="this.style.opacity=0.7; this.style.transform='translateY(-50%) scale(1)'">›</button>
</div>

<script>
// Splide initialization for both mobile and desktop, with responsive breakpoints
let splideInstance = null;
const splideSelector = '#<?php echo esc_attr($splide_id); ?>';

function initSplide() {
    if (!splideInstance) {
        splideInstance = new Splide(splideSelector, {
            type        : 'loop',
            perPage     : <?php echo esc_attr($columns); ?>,
            perMove     : 1,
            gap         : '1rem',
            arrows      : true,
            pagination  : false,
            drag        : true,
            rewind      : true,
            trimSpace   : false,
            focus       : 0,
            //padding     : { right: '10%' },  // Peek effect on mobile
            breakpoints : {
                768: {
                    perPage : 2,
                    padding : { right: '10%' },
                    focus   : 0,
                },
                600: {
                    perPage : 1,
                    padding : { right: '10%' },
                    focus   : 0,
                }
            }
        }).mount();
    }
}

document.addEventListener('DOMContentLoaded', initSplide);

// Lightbox state (unchanged)
let currentImages = [];
let currentIndex = 0;

function openLightbox(e, galleryId, index) {
    e.stopPropagation();
    const imgs = document.querySelectorAll('#' + galleryId + ' .gallery-item img[data-full]');
    currentImages = Array.from(imgs);
    currentIndex = index;

    const overlay = document.getElementById('animatedLightbox');
    const img = document.getElementById('lbImg');
    const caption = document.getElementById('lbCaption');

    // Reset
    overlay.style.display = 'block';
    img.style.opacity = '0';
    img.style.transform = 'scale(0.8)';
    caption.style.opacity = '0';

    // Trigger open animation
    requestAnimationFrame(() => {
        overlay.style.opacity = '1';
        overlay.querySelector('div').style.transform = 'scale(1)';
        
        setTimeout(() => {
            showImage();
            img.style.opacity = '1';
            img.style.transform = 'scale(1)';
            caption.style.opacity = '1';
        }, 50);
    });

    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    const overlay = document.getElementById('animatedLightbox');
    const img = document.getElementById('lbImg');
    const caption = document.getElementById('lbCaption');

    overlay.style.opacity = '0';
    img.style.opacity = '0';
    img.style.transform = 'scale(0.8)';
    caption.style.opacity = '0';
    overlay.querySelector('div').style.transform = 'scale(0.9)';

    setTimeout(() => {
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }, 400);
}

function changeImage(dir) {
    currentIndex = (currentIndex + dir + currentImages.length) % currentImages.length;
    
    const img = document.getElementById('lbImg');
    const caption = document.getElementById('lbCaption');

    // Fade out
    img.style.opacity = '0';
    img.style.transform = 'scale(0.95)';
    caption.style.opacity = '0';

    setTimeout(() => {
        showImage();
        img.style.opacity = '1';
        img.style.transform = 'scale(1)';
        caption.style.opacity = '1';
    }, 10);
}

function showImage() {
    const imgEl = currentImages[currentIndex];
    document.getElementById('lbImg').src = imgEl.dataset.full;
    document.getElementById('lbCaption').textContent = imgEl.dataset.caption || '';
}

// Keyboard & Touch for lightbox
document.addEventListener('keydown', e => {
    if (document.getElementById('animatedLightbox').style.display === 'block') {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') changeImage(-1);
        if (e.key === 'ArrowRight') changeImage(1);
    }
});

let startX = 0;
document.addEventListener('touchstart', e => {
    if (document.getElementById('animatedLightbox').style.display === 'block') {
        startX = e.touches[0].clientX;
    }
});
document.addEventListener('touchend', e => {
    if (document.getElementById('animatedLightbox').style.display === 'block') {
        const diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) changeImage(diff > 0 ? 1 : -1);
    }
});

// Click outside image to close
document.getElementById('animatedLightbox').addEventListener('click', e => {
    if (e.target === document.getElementById('animatedLightbox')) closeLightbox();
});
</script>