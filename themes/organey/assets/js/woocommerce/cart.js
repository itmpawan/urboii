(function ($) {
    'use strict';

    function CrossSellCarousel() {
        var $body = $('body');
        var rtl = $body.hasClass('rtl') ? true : false;
        var $cross_sell = $body.find('.cross-sells ul.products');
        if ($cross_sell.length) {
            $cross_sell.slick({
                rtl: rtl,
                infinite: false,
                dots: true,
                arrows: false,
                slidesToShow: 4,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 450,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
    }

    $(document).ready(function () {
        CrossSellCarousel();
    });

})(jQuery);
