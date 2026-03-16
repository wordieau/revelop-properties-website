<?php
/**
 *  Header.php
 */
if ( ! function_exists('get_field') ) {
    echo 'ACF plugin does not exist.';
    return;
}

$has_dark_header = $GLOBALS['has_dark_header'] ?? false;
$has_bgcolor = $GLOBALS['has_bgcolor'] ?? false;

// Logos
$logo_light = get_field('logo-light', 'option');
$logo_dark  = get_field('logo-dark', 'option');

$header_mode = $has_dark_header ? 'dark-mode' : 'light-mode';
$logo_to_use = $has_dark_header ? $logo_light : $logo_dark;

// Background & text colors
$bg_color    = $has_dark_header 
    ? (get_field('header_dark_bc', 'option') ?: 'transparent')
    : (get_field('header_light_bc', 'option') ?: '#FAF6F3');

$bg_color = $has_bgcolor ? 'transparent' : $bg_color;

$text_color  = $has_dark_header ? '#F9FAFB' : '#1a1f2b';

// Button
$button         = get_field('header_button', 'option');
$button_url     = $button['url'] ?? '';
$button_title   = $button['title'] ?? '';
$button_target  = $button['target'] ?? '_self';
$button_icon_hide = get_field('header_button_hide_icon', 'option') ? 'hide-icon' : '';
$top_bar_announcement = get_field('announcement_top_bar','option') ?? ''; 
$top_bar_biz_infos = get_field('business_infos_top_bar','option') ?? [];
$mobile_footer_image = get_field('mobile_footer_image','option') ?? [];

$compact_nav = get_field('compact_navigation_menu','option') ?? 0;
$compact_mode = $compact_nav ? 'compact-m' : '';
?>

<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script> -->
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if($top_bar_biz_infos || top_bar_announcement) : ?>
        <div class="top-bar bg-BF9A7A relative zindex-2">
            <div class="container display-flex justify-space-between flex-wrap gap-1 justify-center">
                <div class="no-margin-bottom-outer size-12">
                    <?php if($top_bar_announcement) : ?>
                        <?php echo $top_bar_announcement; ?>
                    <?php endif; ?>
                </div>
                <div class="display-flex flex-wrap justofy-space-between gap-4 justify-center">
                <?php foreach($top_bar_biz_infos as $index => $item) : ?>
                    <div class="display-flex gap-1">
                    <?php
                    $icon = $item['icon'] ?? '';   
                    $texts = $item['texts'] ?? ''; 
                    $link = $item['link'] ?? '';
                    ?>

                    <?php if($icon) : ?>
                        <a class="top-bar-link tbl-<?php echo $index; ?> href="<?php echo $link; ?>" target="_blank"><span><img src="<?php echo $icon; ?>"></span></a>
                    <?php endif;?>

                    <?php if($texts) : ?>
                        <a class="top-bar-link tbl-<?php echo $index; ?>" href="<?php echo $link; ?>" target="_blank"><span class="size-12"><?php echo $texts; ?></span></a>
                    <?php endif;?>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
<?php endif; ?>

<header id="site-header" class="wide-header <?php echo esc_attr($header_mode); ?> <?php echo $compact_mode; ?>"
        style="background-color: <?php echo esc_attr($bg_color); ?>;" data-attr="<?php echo $bg_color; ?>">
    <div class="header-inner">

        <!-- Site Branding - Show correct logo based on mode -->
        <div class="site-branding">
            <?php if ( $logo_light && $logo_dark ) : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                  
                        <img src="<?php echo esc_url(is_array($logo_light) ? $logo_light['url'] : $logo_light); ?>"
                             alt="<?php bloginfo('name'); ?> logo-light"
                             class="wide-logo logo-light">
                   
                        <img src="<?php echo esc_url(is_array($logo_dark) ? $logo_dark['url'] : $logo_dark); ?>"
                             alt="<?php bloginfo('name'); ?> logo-dark"
                             class="wide-logo logo-dark">
                    
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="wide-text-logo" style="color: <?php echo esc_attr($text_color); ?>;">
                    Revelop
                </a>
            <?php endif; ?>
        </div>

<!-- Desktop Nav -->
<?php if ($compact_nav) echo '<div class="compact-nav display-flex flex-wrap gap-4 items-center bg-white radius-8 padding-12">'; ?>
<nav class="primary-navigation desktop-nav" aria-label="Primary menu">
    <?php
    wp_nav_menu(array(
        'theme_location'  => 'primary',
        'menu_class'      => 'site-nav-menu',
        'container'       => false,
        'fallback_cb'     => '__return_false',
        'depth'           => 0,
        'walker'          => new class extends Walker_Nav_Menu {
            function start_lvl(&$output, $depth = 0, $args = null) {
                $indent = str_repeat("\t", $depth);
                $output .= "\n$indent<ul class=\"sub-menu\">\n";
            }

            function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                $indent = ($depth) ? str_repeat("\t", $depth) : '';

                $classes = empty($item->classes) ? array() : (array) $item->classes;
                $classes[] = 'menu-item-' . $item->ID;

                $has_children = in_array('menu-item-has-children', $classes);
                if ($has_children) {
                    $classes[] = 'has-dropdown';
                }

                $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
                $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

                $id_attr = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
                $id_attr = $id_attr ? ' id="' . esc_attr($id_attr) . '"' : '';

                $output .= $indent . '<li' . $id_attr . $class_names . '>';

                $attributes  = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) . '"' : '';
                $attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target)     . '"' : '';
                $attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn)        . '"' : '';
                $attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url)        . '"' : '';

                // Fixed SVG arrow - properly escaped and concatenated
                $arrow = $has_children ? ' <svg class="dropdown-arrow" xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6" fill="none">
                    <path d="M4.71146 5.58958L0 0.878126L0.878125 0L4.71146 3.83333L8.54479 0L9.42292 0.878126L4.71146 5.58958Z" fill="currentColor"/>
                </svg>' : '';

                $item_output = ($args->before ?? '');
                $item_output .= '<a' . $attributes . '>';
                $item_output .= ($args->link_before ?? '') . apply_filters('the_title', $item->title, $item->ID) . ($args->link_after ?? '');
                $item_output .= $arrow;
                $item_output .= '</a>';
                $item_output .= ($args->after ?? '');

                $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
            }
        }
    ));
    ?>
</nav>

<!-- Desktop Button -->
 <?php if($button_title && $button_url) : ?>
<div class="header-contact desktop-contact">
    <a href="<?php echo esc_url($button_url); ?>" target="<?php echo esc_attr($button_target); ?>"
       class="site-btn btn--primary contact-us-btn gap-1 <?php echo $button_icon_hide; ?>">
        <?php echo esc_html($button_title); ?>
    </a>
</div>
<?php endif; ?>
<?php if ($compact_nav) echo '</div>'; ?>

        <!-- Mobile Toggle -->
        <button class="mobile-menu-toggle" aria-label="Open menu" aria-expanded="false">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay">
    <div class="mobile-menu-header">
        <div class="site-branding">
            <!-- Always use light logo in mobile menu (dark background) -->
            <?php if ( $logo_dark ) : ?>
                <img src="<?php echo esc_url(is_array($logo_dark) ? $logo_dark['url'] : $logo_dark); ?>"
                     alt="Revelop" class="mobile-logo-white">
            <?php else : ?>
                <span style="color:#fff;">Revelop</span>
            <?php endif; ?>
        </div>
        <button class="mobile-menu-close" aria-label="Close menu">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/imgs/close.svg" alt="Close">
        </button>
    </div>

    <nav class="mobile-menu-nav">
        <?php wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_class'     => 'mobile-nav-list',
            'container'      => false,
            'fallback_cb'    => '__return_false',
            'depth'          => 2,
            'walker'         => new class extends Walker_Nav_Menu {
                // public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                //     $output .= '<a href="' . esc_url($item->url) . '" class="mobile-nav-item">' . esc_html($item->title) . '</a>';
                // }
                public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
                    // Base class
                    $classes = 'mobile-nav-item';
                    // Check if this item has children
                    if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
                        $classes .= ' has-submenu';
                    }
                    $output .= sprintf(
                        '<a href="%s" class="%s">%s</a>',
                        esc_url( $item->url ),
                        esc_attr( $classes ),
                        esc_html( $item->title )
                    );
                }
            }
        )); ?>
    </nav>
    <?php if($button_url && $button_title) : ?>
    <div class="mobile-menu-footer">
        <a href="<?php echo esc_url($button_url); ?>"
           target="<?php echo esc_attr($button_target); ?>"
           class="site-btn btn--primary mobile-contact-btn width-max">
            <?php echo esc_html($button_title); ?>
        </a>
    </div>
    <?php endif; ?>

    <?php if($top_bar_biz_infos || top_bar_announcement) : ?>
        <div class="mobile-footer-bar relative zindex-2">
            <div class="container display-flex justify-space-between flex-wrap gap-1 justify-center">

                <div class="display-flex flex-wrap justofy-space-between gap-4 justify-center flex-direction-column">
                <?php foreach($top_bar_biz_infos as $item) : ?>
                    <div class="display-flex gap-1">
                    <?php
                    $icon = $item['icon'] ?? '';   
                    $texts = $item['texts'] ?? ''; ?>

                    <?php if($icon) : ?>
                        <span class="invert-1"><img src="<?php echo $icon; ?>"></span>
                    <?php endif;?>

                    <?php if($texts) : ?>
                        <span class="size-14 text-black"><?php echo $texts; ?></span>
                    <?php endif;?>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if($mobile_footer_image) : ?>
    <div class="text-right">
        <img src="<?php echo $mobile_footer_image['url']; ?>">
    </div>
    <?php endif; ?>

</div>

<style>
    #site-header:not(.compact-m) .site-nav-menu a { color: <?php echo esc_attr($text_color); ?>; }
    #site-header.compact-m .site-nav-menu a { color: #212B45; }
    .hamburger-line { 
        background: <?php echo esc_attr($text_color); ?> !important; 
        display: block;
        width: 24px;
        height: 2px;
        margin: 5px 0;
        transition: 0.3s;
    }


/* */
/* Desktop Menu */
.desktop-nav .site-nav-menu {
    display: flex;
    align-items: center;
    gap: 2rem; /* adjust to your liking */
    list-style: none;
    margin: 0;
    padding: 0;
}

.desktop-nav .site-nav-menu > li {
    position: relative;
}

.desktop-nav .site-nav-menu a {
    display: flex;
    align-items: center;
    text-decoration: none;
    padding: 0.5rem 0;
    color: inherit;
    font-weight: 500;
    transition: opacity 0.2s;
    white-space: nowrap;
}

.desktop-nav .dropdown-arrow {
    margin-left: 0.5rem;
    transition: transform 0.25s ease;
}

/* Hover state for parent item */
.desktop-nav .menu-item-has-children:hover > a {
    opacity: 0.8;
}

/* Show dropdown on hover */
.desktop-nav .has-dropdown:hover > .sub-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

/* Submenu styles */
.desktop-nav .sub-menu {
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #fff;
    min-width: 220px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    border-radius: 8px;
    padding: 0.75rem 0;
    margin: 0;
    list-style: none;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.25s ease;
    z-index: 1000;
}

.desktop-nav .sub-menu li {
    width: 100%;
}

.desktop-nav .sub-menu a {
    display: block;
    padding: 0.75rem 1.5rem;
 
    font-weight: 400;
    transition: background 0.2s;
}

.desktop-nav .sub-menu a:hover {
    background-color: #f8f8f8;
}

/* Compact mode (white background) */
#site-header.compact-m .sub-menu {
    background-color: #fff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}


#site-header.compact-m .sub-menu a:hover{
    color:#BF9A7A;
}
/* Dark mode header */
/* #site-header.dark-mode .sub-menu {
    background-color: #fff;
}

#site-header.dark-mode .sub-menu a {
    color: #212B45 !important;
} */

/* #site-header.dark-mode .sub-menu a:hover {
    background-color: rgba(255, 255, 255, 0.1);
} */

/* Optional: rotate arrow on hover */
.desktop-nav .has-dropdown:hover .dropdown-arrow {
    transform: rotate(180deg);
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const header = document.getElementById('site-header');
    if (window.scrollY > 10) header.classList.add('is-scrolled');

    let ticking = false;
    function updateHeader() {
        if (window.scrollY > 10) {
            header.classList.add('is-scrolled');
        } else {
            header.classList.remove('is-scrolled');
        }
        ticking = false;
    }
    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(updateHeader);
            ticking = true;
        }
    });

    const toggle = document.querySelector('.mobile-menu-toggle');
    const overlay = document.querySelector('.mobile-menu-overlay');
    const closeBtn = document.querySelector('.mobile-menu-close');
    const body = document.body;

    const openMenu = () => { overlay.classList.add('open'); body.style.overflow = 'hidden'; toggle.setAttribute('aria-expanded', 'true'); };
    const closeMenu = () => { overlay.classList.remove('open'); body.style.overflow = ''; toggle.setAttribute('aria-expanded', 'false'); };

    toggle?.addEventListener('click', openMenu);
    closeBtn?.addEventListener('click', closeMenu);
    overlay?.addEventListener('click', e => e.target === overlay && closeMenu());
    document.addEventListener('keydown', e => e.key === 'Escape' && closeMenu());
});
</script>