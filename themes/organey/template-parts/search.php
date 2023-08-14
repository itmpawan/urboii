<?php
/**
 * The template for displaying search results.
 *
 * @package Organey
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>
<main class="site-main" role="main">
    <div class="container clearfix">
        <?php if (apply_filters('organey_page_title', true)) : ?>
            <header class="page-header text-center">
                <h1 class="entry-title">
                    <?php esc_html_e('Search results for: ', 'organey'); ?>
                    <span><?php echo get_search_query(); ?></span>
                </h1>
            </header>
        <?php endif; ?>
        <div id="primary" class="content-area">

            <div class="page-content">

                <?php
                if (have_posts()) {
                    while (have_posts()): the_post();
                        get_template_part('template-parts/content/excerpt');
                    endwhile;
                } else {
                    ?>
                    <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'organey'); ?></p>
                    <?php get_search_form();
                }


                $args = array(
                    'type'      => 'list',
                    'next_text' => '<i class="organey-icon-angle-right"></i>',
                    'prev_text' => '<i class="organey-icon-angle-left"></i>',
                );

                the_posts_pagination($args);
                ?>
            </div>
        </div>
        <?php get_sidebar(); ?>
    </div>
</main>
