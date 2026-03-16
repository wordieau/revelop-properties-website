<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package GeneratePress
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header(); ?>

    <div <?php generate_do_attr( 'content' ); ?>>
        <main <?php generate_do_attr( 'main' ); ?>>
            <div class="inside-article">
                <header class="entry-header">
                    <h1 class="entry-title">Custom 404: Page Not Found!</h1>
                </header>
                <div class="entry-content">
                    <p>Sorry, we couldn't find that page. Try searching or go home.</p>
                    <?php get_search_form(); ?>
                    <p><a href="<?php echo home_url(); ?>">Back to Home</a></p>
                </div>
            </div>
        </main>
    </div>

<?php

get_footer();