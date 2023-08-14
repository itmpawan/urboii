(function ($) {
    'use strict';

    function cart_side() {
        var $cart_side = $('.site-header-cart-side');
        var $body = $('body');
        if (!$cart_side.length) {
            return;
        }

        $body.on('added_to_cart', function () {

            if (!$cart_side.hasClass('active')) {
                $cart_side.addClass('active');
            }
        });
    }

    $(document).ready(function () {
        cart_side();
    });

})(jQuery);


