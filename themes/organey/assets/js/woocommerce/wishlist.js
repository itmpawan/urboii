(function ($) {
    'use strict';

    function ajax_wishlist_count() {
        var counter = $('.header-wishlist .count, .footer-wishlist .count');
        var data = {
            action: 'wishlist_load_count',
        };

        $.post(woosw_vars.ajax_url, data, function(response) {
            response = JSON.parse(response);

            if (response['count'] != null) {
                var count = response['count'];
                counter.html(count);
            }
        });
    }

    function wishlist_side() {
        var $wishlist_side = $('.site-wishlist-side');
        var $body = $('body');
        if (!$wishlist_side.length) {
            return;
        }

        $body.on('click', '.header-wishlist, .footer-wishlist', function (e) {
            organey_woosw_load(true);
        });

        $(document.body).on('woosw_show', function () {
            organey_woosw_load();
            if (!$wishlist_side.hasClass('active')) {
                $wishlist_side.addClass('active');
            }
        });
    }

    function organey_woosw_load(check = false) {
        var $wrap_content = $('.organey-wishlist-content');
        if($wrap_content.hasClass('content-loaded') && check) {
            return;
        }
        var data = {
            action: 'wishlist_load',
        };

        $wrap_content.addClass('loadding');

        $.post(woosw_vars.ajax_url, data, function (response) {
            response = JSON.parse(response);
            if (response['status'] == 1) {
                $wrap_content.html(response['content']);
            } else {
                if (response['notice'] != null) {
                    $wrap_content.html('<div class="woosw-content-mid-notice">' +
                        response['notice'] +
                        '</div>');
                }
            }
            $wrap_content.removeClass('loadding');
            $(document.body).trigger('organey_woosw_loaded')
        });

        $wrap_content.addClass('content-loaded');
    }

    $(document).ready(function () {
        wishlist_side();
        ajax_wishlist_count();

        $('body').on('woosw_change_count', function (event, count) {
            var counter = $('.header-wishlist .count, .footer-wishlist .count');
            counter.html(count);
            $('.organey-wishlist-content').removeClass('content-loaded');
        });
    });


})(jQuery);


