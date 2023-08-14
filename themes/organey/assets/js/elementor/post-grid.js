(function ($) {
    "use strict";

    $(window).on('elementor/frontend/init', () => {
        // Post Grid

        elementorFrontend.hooks.addAction('frontend/element_ready/organey-post-grid.default', ($scope) => {
            let $carousel = $('.organey-carousel', $scope);
            var rtl = $('body').hasClass('rtl');
            if ($carousel.length > 0) {
                let data = $carousel.data('settings');
                $carousel.on('init', function(){
                    $(document).trigger('skeletonScreen');
                }).not('.slick-initialized').slick(
                    {
                        dots: data.navigation == 'both' || data.navigation == 'dots' ? true : false,
                        arrows: data.navigation == 'both' || data.navigation == 'arrows' ? true : false,
                        infinite: data.loop,
                        speed: 300,
                        slidesToShow: parseInt(data.items),
                        autoplay: false,
                        autoplaySpeed: 5000,
                        slidesToScroll: 1,
                        rtl: rtl,
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
