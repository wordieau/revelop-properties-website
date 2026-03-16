jQuery(document).ready(function($) {
    // Find all posts-loop modules on the page (in case you have more than one)
    $('.posts-loop').each(function() {
        const $module   = $(this);
        const $grid     = $module.find('#posts-loop-grid');
        const $filters  = $module.find('.filters .filter-btn');
        const $search   = $module.find('.post-loop-search-input');
        let timeout;

        // Get featured post ID from data attribute (we’ll add it in PHP)
        const featuredId = $module.data('featured-id') || 0;

        function loadPosts(category = 'all', search = '') {
            $.ajax({
                url: postsLoopAjax.ajax_url,
                type: 'POST',
                data: {
                    action:   'posts_loop_filter',
                    nonce:    postsLoopAjax.nonce,
                    post_type: postsLoopAjax.post_type,
                    category: category,
                    search:   search,
                    exclude:  featuredId
                },
                beforeSend: function() {
                    $grid.addClass('loading').html('<div class="spinner">Loading...</div>');
                },
                success: function(response) {
                    $grid.removeClass('loading').html(response);
                }
            });
        }

        // Filter buttons
        $module.on('click', '.filters .filter-btn', function(e) {
            e.preventDefault();
            $filters.removeClass('active');
            $(this).addClass('active');
            const cat = $(this).data('filter');
            loadPosts(cat, $search.val() || '');
        });

        // Live search
        $search.on('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const activeCat = $module.find('.filters .filter-btn.active').data('filter') || 'all';
                loadPosts(activeCat, $(this).val());
            }, 400);
        });
    });
});