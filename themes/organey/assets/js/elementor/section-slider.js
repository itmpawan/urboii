(function ($) {
    "use strict";

    $(window).on('elementor/frontend/init', () => {

        // Section slider

        elementorFrontend.hooks.addAction('frontend/element_ready/organey-section-slider.default', function ($scope) {
            let $carousel = $('.swiper-container', $scope);


            if ($carousel.length > 0) {
                let data = $carousel.data('settings');
                let break_point = data.disable_mobile ? 1024 : 0;
                var mySwiper = undefined;

                function section_swiper() {

                    if (mySwiper == undefined && $(window).width() >= break_point) {

                        let swiperOptions = {
                            direction: data.direction,
                            spaceBetween: 0,
                            slidesPerView: 1,
                            mousewheel: {
                                releaseOnEdges: true,
                            }
                        }

                        if (data.navigation == "dots" || data.navigation == "both") {
                            swiperOptions.pagination = {
                                el: '.swiper-pagination',
                            }
                        }

                        if (data.navigation == "arrow" || data.navigation == "both") {
                            swiperOptions.navigation = {
                                prevEl: '.elementor-swiper-button-prev',
                                nextEl: '.elementor-swiper-button-next'
                            };
                        }

                        if (data.autoplay == true) {
                            swiperOptions.autoplay = {
                                delay: data.autoplaySpeed,
                            };
                        }


                        mySwiper = new Swiper($carousel.selector, swiperOptions);
                        if (data.autoplayHoverPause == true) {
                            mySwiper.on({
                                mouseenter: function mouseenter() {
                                    _this2.swipers.main.autoplay.stop();
                                },
                                mouseleave: function mouseleave() {
                                    _this2.swipers.main.autoplay.start();
                                }
                            });
                        }
                    } else if (mySwiper != undefined && $(window).width() < break_point) {

                        mySwiper.destroy();
                        mySwiper = undefined;

                    }
                }

                section_swiper();

                window.addEventListener('resize', function () {
                    section_swiper();
                });

            }

        });

    });

})(jQuery);
