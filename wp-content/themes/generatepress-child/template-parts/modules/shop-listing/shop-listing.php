<?php
/**
 * Module: Shop Listing (Infinite Scroll - Fixed & Optimized)
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$args      = $args ?? [];
$prefix    = $args['prefix'] ?? '';
$row_index = $args['row_index'] ?? 0;

if ( ! get_sub_field( 'module_enable' ) ) {
    return;
}

$content = get_sub_field( 'contents_layout' );

$filter_bgcolor   = $content['filter_search_bgcolor'] ? 'background-color:'.$content['filter_search_bgcolor'] : '';
$alphabet_nav_bgcolor = $content['alphabet_nav_bgcolor'] ? 'background-color:'.$content['alphabet_nav_bgcolor'] : '';
$shop_grid_bgcolor = $content['background_color_bgcolor'] ? 'background-color:'.$content['background_color_bgcolor'] : '';

if ( ! post_type_exists( 'listing' ) ) {
    echo '<p style="color:red;">Error: Custom post type "listing" is not registered.</p>';
    return;
}

$tag_taxonomy = 'post_tag';

$tags = get_terms( array(
    'taxonomy'   => $tag_taxonomy,
    'hide_empty' => true,
) );

$initial_posts_per_page = 10; // You can change this

// Reliable total count using lightweight query
$count_query = new WP_Query( array(
    'post_type'      => 'listing',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',          // Faster, only IDs
    'orderby'        => 'title',
    'order'          => 'ASC',
) );
$total_posts = $count_query->found_posts;


// Initial visible posts
$shop_query = new WP_Query( array(
    'post_type'      => 'listing',
    'post_status'    => 'publish',
    'posts_per_page' => $initial_posts_per_page,
    'paged'          => 1,
    'orderby'        => 'title',
    'order'          => 'ASC',
) );

$has_more = $total_posts > $initial_posts_per_page;
?>

<section class="shop-listing"
         data-total="<?php echo esc_attr( $total_posts ); ?>"
         data-per-page="<?php echo esc_attr( $initial_posts_per_page ); ?>"
         data-paged="1"
         data-has-more="<?php echo $has_more ? '1' : '0'; ?>">
    <div class="">
    
        <div class="search-filter" style="<?php echo $filter_bgcolor; ?>">
            <div class="filter-container display-flex flex-direction-row gap-2">

                <div class="search-store">
                    <div class="search-wrapper">
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <mask id="mask0_4725_1880" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="20" height="20">
                                <rect width="20" height="20" fill="#D9D9D9"/>
                            </mask>
                            <g mask="url(#mask0_4725_1880)">
                                <path d="M16.2845 17.1474L11.0505 11.9133C10.6339 12.2573 10.1547 12.5265 9.61302 12.721C9.07135 12.9154 8.51101 13.0126 7.93198 13.0126C6.50767 13.0126 5.30226 12.5195 4.31573 11.5333C3.3292 10.547 2.83594 9.34188 2.83594 7.91785C2.83594 6.49396 3.32906 5.2884 4.31531 4.30118C5.30156 3.3141 6.5067 2.82056 7.93073 2.82056C9.35462 2.82056 10.5602 3.31382 11.5474 4.30035C12.5345 5.28688 13.028 6.49229 13.028 7.9166C13.028 8.51174 12.9282 9.08014 12.7284 9.62181C12.5286 10.1635 12.262 10.6346 11.9286 11.0351L17.1626 16.2691L16.2845 17.1474ZM7.93198 11.7628C9.00573 11.7628 9.91517 11.3902 10.6603 10.6449C11.4056 9.89979 11.7782 8.99035 11.7782 7.9166C11.7782 6.84285 11.4056 5.9334 10.6603 5.18826C9.91517 4.44299 9.00573 4.07035 7.93198 4.07035C6.85823 4.07035 5.94878 4.44299 5.20365 5.18826C4.45837 5.9334 4.08573 6.84285 4.08573 7.9166C4.08573 8.99035 4.45837 9.89979 5.20365 10.6449C5.94878 11.3902 6.85823 11.7628 7.93198 11.7628Z" fill="#A6A09B"/>
                            </g>
                        </svg>
                        <input type="search" id="search-input" placeholder="Search for a store...">
                    </div>
                </div>

                <div class="filter-store-tag">
                    <div class="select-wrapper">
                        <svg class="filter-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <mask id="mask0_4725_5094" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="20" height="20">
                                <rect width="20" height="20" fill="#D9D9D9"/>
                            </mask>
                            <g mask="url(#mask0_4725_5094)">
                                <path d="M4.3112 16.4584V10.577H2.64453V9.32696H7.22787V10.577H5.5612V16.4584H4.3112ZM4.3112 7.33987V3.54175H5.5612V7.33987H4.3112ZM7.7087 7.33987V6.08987H9.37537V3.54175H10.6254V6.08987H12.292V7.33987H7.7087ZM9.37537 16.4584V9.32696H10.6254V16.4584H9.37537ZM14.4395 16.4584V13.9103H12.7729V12.6603H17.3562V13.9103H15.6895V16.4584H14.4395ZM14.4395 10.6732V3.54175H15.6895V10.6732H14.4395Z" fill="#A6A09B"/>
                            </g>
                        </svg>
                        <select id="tag-filter">
                            <option value="" selected>All categories</option>
                            <?php if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) :
                                foreach ( $tags as $tag ) : ?>
                                    <option value="<?php echo esc_attr( $tag->slug ); ?>">
                                        <?php echo esc_html( $tag->name ); ?>
                                    </option>
                                <?php endforeach;
                            endif; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
       

        <div class="alphabet-nav" style="<?php echo $alphabet_nav_bgcolor; ?>">
            
                <a href="#" data-letter="all" class="active">All</a>
                <?php foreach ( range( 'A', 'Z' ) as $letter ) : ?>
                    <a href="#" data-letter="<?php echo $letter; ?>"><?php echo $letter; ?></a>
                <?php endforeach; ?>
                <a href="#" data-letter="num">#</a>
            
        </div>

        <div class="shop-grid-container" style="<?php echo $shop_grid_bgcolor; ?>">
            <div class="grid columns-5 md-columns-2 mobile-columns-2 gap-3 container shop-grid" id="shop-grid">
            <?php
                if ( $shop_query->have_posts() ) :
                    while ( $shop_query->have_posts() ) : $shop_query->the_post();
                        $custom_image = get_field( 'post_type_image' );          // ACF field: post_type_image
                        $store_logo   = get_field( 'store_logo_post_type' );     // ACF field: store_logo_post_type
                        
                        $custom_title = get_field( 'post_type_title' );
                        $title = ! empty( $custom_title ) ? $custom_title : get_the_title();

                        // Determine the logo image URL based on priority
                        $logo_img_url = '';

                        if ( $store_logo ) {
                            // Rule 1: Store logo has highest priority — use it if it exists
                            $logo_img_url = is_array( $store_logo ) ? $store_logo['url'] : $store_logo;
                        } 
                        elseif ( $custom_image ) {
                            // Rule 2: Only use custom_image if store_logo is NOT set
                            $logo_img_url = is_array( $custom_image ) ? $custom_image['url'] : $custom_image;
                        }
                        // Optional Rule 3: Fallback to featured image if both are missing
                        // else {
                        //     $logo_img_url = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'medium' ) : '';
                        // }

                        $tag_terms = get_the_terms( get_the_ID(), $tag_taxonomy );
                        $tag_slug  = $tag_terms && ! is_wp_error( $tag_terms ) ? $tag_terms[0]->slug : '';
                        $tag_name  = $tag_terms && ! is_wp_error( $tag_terms ) ? $tag_terms[0]->name : '';

                        $first_char = strtoupper( mb_substr( $title, 0, 1 ) );
                        if ( ! ctype_alpha( $first_char ) ) $first_char = 'num';
                        ?>

                        <div class="shop-item"
                            data-tag="<?php echo esc_attr( $tag_slug ); ?>"
                            data-title="<?php echo esc_attr( strtolower( $title ) ); ?>"
                            data-letter="<?php echo esc_attr( $first_char ); ?>">
                            <a href="<?php echo esc_url( get_permalink() ); ?>" class="shop-card-link text-decoration-none">
                                <div class="shop-card display-flex flex-direction-column justify-end">
                                    <?php if ( $logo_img_url ) : ?>
                                        <div class="shop-logo">
                                            <img src="<?php echo esc_url( $logo_img_url ); ?>"
                                                alt="<?php echo esc_attr( $title ); ?>"
                                                loading="lazy">
                                        </div>
                                    <?php endif; ?>

                                    <div class="shop-details display-flex flex-direction-column justify-center gap-2">
                                        <?php if ( $tag_name ) : ?>
                                            <p class="shop-tag no-margin-bottom"><?php echo esc_html( $tag_name ); ?></p>
                                        <?php endif; ?>

                                        <h4 class="shop-title"><?php echo esc_html( $title ); ?></h4>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <?php
                    endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p>No stores found.</p>
                <?php endif; ?>
                
            </div>
        </div>

        <!-- Spinner / Load trigger - always present if more posts exist -->
        <div class="loading-trigger" id="loading-trigger" style="<?php echo $has_more ? '' : 'display:none;'; ?>">
            <div class="spinner"></div>
            <p>Loading more stores...</p>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';

    const section = document.querySelector('.shop-listing');
    const grid = document.getElementById('shop-grid');
    const searchInput = document.getElementById('search-input');
    const tagFilter = document.getElementById('tag-filter');
    const alphabetLinks = document.querySelectorAll('.alphabet-nav a');
    const loadingTrigger = document.getElementById('loading-trigger');

    let currentLetter = 'all';
    let isLoading = false;
    let currentPage = 1;
    const perPage = parseInt(section.dataset.perPage);
    const totalPosts = parseInt(section.dataset.total);
    let hasMore = section.dataset.hasMore === '1';

    let hideTimeout = null; // To control the 5-second delay

    function filterItems() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedTag = tagFilter.value;
        const selectedLetter = currentLetter;

        const items = grid.querySelectorAll('.shop-item');

        items.forEach(item => {
            const title = item.dataset.title;
            const tag = item.dataset.tag;
            const letter = item.dataset.letter;

            const matchesSearch = title.includes(searchTerm);
            const matchesTag = selectedTag === '' || tag === selectedTag;
            const matchesLetter = selectedLetter === 'all' || letter === selectedLetter;

            item.style.display = matchesSearch && matchesTag && matchesLetter ? '' : 'none';
        });
    }

    function scheduleHide() {
    // Clear any existing timeout
    if (hideTimeout) clearTimeout(hideTimeout);

    // Hide after 5 seconds AND stop the spin animation
    hideTimeout = setTimeout(() => {
        loadingTrigger.style.opacity = '0'; // Fade out
        loadingTrigger.style.transition = 'opacity 0.8s ease';

        // Stop the spinner animation
        const spinner = loadingTrigger.querySelector('.spinner');
        if (spinner) {
            spinner.style.animation = 'none'; // Stops spinning
        }

        // Fully hide after fade
        setTimeout(() => {
            loadingTrigger.style.display = 'none';
        }, 800); // Match fade duration
    }, 5000); // 5 seconds delay
}

    function loadMorePosts() {
        if (isLoading || !hasMore) return;
        isLoading = true;

        loadingTrigger.style.opacity = '1';

        currentPage++;
        section.dataset.paged = currentPage;

        fetch(ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action: 'load_more_listings',
                page: currentPage,
                posts_per_page: perPage,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.html && data.data.html.trim() !== '') {
                grid.insertAdjacentHTML('beforeend', data.data.html);
                filterItems();

                // Continue loading if spinner still visible
                const rect = loadingTrigger.getBoundingClientRect();
                if (rect.top < window.innerHeight && hasMore) {
                    loadMorePosts();
                }
                
            } else {
                
                // No more posts → start the 5-second delay before hiding
                hasMore = false;
                scheduleHide();
            }
            isLoading = false;
        })
        .catch(err => {
            console.error('Load more failed:', err);
            isLoading = false;
            loadingTrigger.style.opacity = '0.5';
        });
    }

    // Observer
    if (loadingTrigger && hasMore) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !isLoading) {
                    loadMorePosts();
                }
            });
        }, { rootMargin: '600px' });

        observer.observe(loadingTrigger);
    }

    // Alphabet navigation
    alphabetLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            alphabetLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            currentLetter = this.dataset.letter;
            filterItems();

            if (currentLetter !== 'all') {
                const firstVisible = document.querySelector('.shop-item[data-letter="' + currentLetter + '"]:not([style*="none"])');
                if (firstVisible) firstVisible.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });

    searchInput.addEventListener('input', filterItems);
    tagFilter.addEventListener('change', filterItems);

    filterItems();
});
</script>