<?php

if (get_query_var('woosw_id')) {
    $key = get_query_var('woosw_id');
} else {
    $key = WPCleverWoosw::get_key();
}
$return_html = '';
$share_url_raw = WPCleverWoosw::get_url($key, true);
$share_url     = urlencode($share_url_raw);
ob_start();
include get_theme_file_path('woocommerce/wishlist-table.php');
$return_html .= ob_get_clean();
$return_html .= '<div class="woosw-actions">';

if (get_option('woosw_page_share', 'yes') === 'yes') {
    $return_html .= '<div class="woosw-share">';
    $return_html .= '<span class="woosw-share-label">' . esc_html__('Share on:', 'organey') . '</span>';
    $return_html .= '<a class="woosw-share-facebook" href="https://www.facebook.com/sharer.php?u=' . esc_url($share_url) . '" target="_blank">' . esc_html__('Facebook', 'organey') . '</a>';
    $return_html .= '<a class="woosw-share-twitter" href="https://twitter.com/share?url=' . esc_url($share_url) . '" target="_blank">' . esc_html__('Twitter', 'organey') . '</a>';
    $return_html .= '<a class="woosw-share-pinterest" href="https://pinterest.com/pin/create/button/?url=' . esc_url($share_url) . '" target="_blank">' . esc_html__('Pinterest', 'organey') . '</a>';
    $return_html .= '<a class="woosw-share-mail" href="mailto:?body=' . esc_url($share_url) . '" target="_blank">' . esc_html__('Mail', 'organey') . '</a>';
    $return_html .= '</div><!-- /woosw-share -->';
}

if (get_option('woosw_page_copy', 'yes') === 'yes') {
    $return_html .= '<div class="woosw-copy">';
    $return_html .= '<span class="woosw-copy-label">' . esc_html__('Wishlist link:', 'organey') . '</span>';
    $return_html .= '<span class="woosw-copy-url"><input id="woosw_copy_url" type="url" value="' . esc_attr($share_url_raw) . '" readonly/></span>';
    $return_html .= '<span class="woosw-copy-btn"><input id="woosw_copy_btn" type="button" value="' . esc_attr__('Copy', 'organey') . '"/></span>';
    $return_html .= '</div><!-- /woosw-copy -->';
}

$return_html .= '</div><!-- /woosw-actions -->';

printf('<div class="woosw-list">%s</div>', $return_html);
