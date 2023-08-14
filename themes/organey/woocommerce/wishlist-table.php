<?php
$items_html = '';
$products   = get_option('woosw_list_' . $key);

if (is_array($products) && (count($products) > 0)) {
    $items_html .= '<table class="woosw-content-items">';
    $items_html .= '<thead><tr>';
    $items_html .= '<th></th><th></th>';
    $items_html .= '<th>' . esc_html__('Product', 'organey') . '</th>';
    $items_html .= '<th>' . esc_html__('Price', 'organey') . '</th>';
    $items_html .= '<th>' . esc_html__('Stock status', 'organey') . '</th>';
    $items_html .= '<th></th>';
    $items_html .= '</tr></thead>';

    foreach ($products as $product_id => $product_data) {
        $product = wc_get_product($product_id);

        if (!$product) {
            continue;
        }

        if (is_array($product_data) && isset($product_data['time'])) {
            $product_time = date_i18n(get_option('date_format'), $product_data['time']);
        } else {
            // for old version
            $product_time = date_i18n(get_option('date_format'), $product_data);
        }

        $items_html .= '<tr class="woosw-content-item woosw-content-item-' . $product_id . '" data-id="' . $product_id . '" data-key="' . $key . '">';

        if (WPCleverWoosw::can_edit($key)) {
            $items_html .= '<td class="woosw-content-item--remove"><span></span></td>';
        }

        $items_html .= '<td class="woosw-content-item--image">' . $product->get_image() . '</td>';
        $items_html .= '<td class="woosw-content-item--title"><div><a href="' . $product->get_permalink() . '">' . $product->get_name() . '</a></div>';
        $items_html .= '<div class="woosw-content-item--time">' . $product_time . '</div></td>';
        $items_html .= '<td data-title="' . esc_attr__('Price', 'organey') . '"><div class="woosw-content-item--price">' . $product->get_price_html() . '</div></td>';
        $items_html .= '<td data-title="' . esc_attr__('Stock status', 'organey') . '">' . ($product->is_in_stock() ? '<div class="woosw-content-item--stock">' . esc_html__('In stock', 'organey') : '<div class="woosw-content-item--stock outstock">' . esc_html__('Out of stock', 'organey')) . '</div></td>';
        $items_html .= '<td><div class="woosw-content-item--add">' . do_shortcode('[add_to_cart id="' . $product_id . '" show_price="false"]') . '</div></td>';
        $items_html .= '</tr>';
    }

    $items_html .= '</table>';
} else {
    $items_html = '<div class="woosw-content-mid-notice">' . esc_html__('There are no products on the wishlist!', 'organey') . '</div>';
}

printf('%s', $items_html);
