(function ($) {
    "use strict";

    $(window).on('elementor/frontend/init', () => {
        // Product
        elementorFrontend.hooks.addAction('frontend/element_ready/organey-products.default', ($scope) => {
            let $carousel = $('.woocommerce-carousel', $scope);
            if ($carousel.length > 0) {
                let data = $carousel.data('settings'),
                    rtl = $('body').hasClass('rtl') ? true : false;

                $('ul.products, ul.products-list', $carousel).on('init', function(){
                    $(document).trigger('skeletonScreen');
                }).not('.slick-initialized').slick(
                    {
                        rtl: rtl,
                        dots: data.navigation == 'both' || data.navigation == 'dots' ? true : false,
                        arrows: data.navigation == 'both' || data.navigation == 'arrows' ? true : false,
                        infinite: data.loop,
                        speed: 300,
                        slidesToShow: parseInt(data.items),
                        autoplay: data.autoplay,
                        autoplaySpeed: parseInt(data.autoplayTimeout),
                        slidesToScroll: 1,
                        lazyLoad: 'ondemand',
                        responsive: [
                            {
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: parseInt(data.items_tablet),
                                }
                            },
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: parseInt(data.items_mobile),
                                }
                            }
                        ]
                    }
                );
            }
        });
    });

})(jQuery);
