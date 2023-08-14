<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Organey
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>
<main class="site-main" role="main">
    <div class="page-content container">
        <img class="img-404" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/404-img1.svg') ?>" alt="image-404">
        <div class="content-wrap text-center">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/404-img.svg') ?>" alt="image-404">
            <h1 class="page-title">Oops!</h1>
            <p class="sub-title"><?php esc_html_e('Page not found.', 'organey'); ?></p>
            <p class="error-text"><?php esc_html_e('Sorry, we couldn\'t find the page you where looking for. We suggest that you return to home page.', 'organey'); ?></p>
            <a href="javascript: history.go(-1)" class="go-back"><?php esc_html_e('Go back', 'organey'); ?></a>
        </div>
        <img class="img-404" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/404-img2.svg') ?>" alt="image-404">
    </div>
</main>
