<?php
/**
 * The template for displaying archive pages.
 *
 * @package Organey
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
$blog_style = organey_get_theme_option('blog_style');
?>
<main class="site-main" role="main">
    <div class="container clearfix">
        <?php if (apply_filters('organey_page_title', true)) : ?>
            <header class="page-header text-center">
                <?php
                the_archive_title('<h1 class="entry-title">', '</h1>');
                the_archive_description('<p class="archive-description">', '</p>');
                ?>
            </header>
        <?php endif; ?>
        <div id="primary" class="content-area">
            <div class="page-content <?php echo esc_attr('blog-style-' . $blog_style); ?>">
                <?php
                while (have_posts()): the_post();
                    get_template_part('template-parts/content/excerpt');
                endwhile; ?>
            </div>
            <?php

            $args = array(
                'type'      => 'list',
                'next_text' => '<i class="organey-icon-angle-right"></i>',
                'prev_text' => '<i class="organey-icon-angle-left"></i>',
            );

            the_posts_pagination($args);
            ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
</main>
