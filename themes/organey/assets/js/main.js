(function ($) {
    'use strict';

    function handleWindow() {
        var body = document.querySelector('body');

        if (window.innerWidth > body.clientWidth + 5) {
            body.classList.add('has-scrollbar');
            body.setAttribute('style', '--scroll-bar: ' + (window.innerWidth - body.clientWidth) + 'px');
        } else {
            body.classList.remove('has-scrollbar');
        }
    }

    function navigation() {
        var $button = $('.site-navigation .menu-toggle'),
            $menu = $('.site-navigation');
        $button.on('click', function () {
            $menu.toggleClass('toggled');
        });

        if ($menu.length > 0) {
            $menu.find('.menu-item-has-children > a, .page_item_has_children > a').each((index, element) => {
                var $dropdown = $('<button class="dropdown-toggle"></button>');
                $dropdown.insertAfter(element);

            });
            $(document).on('click', '.site-navigation .dropdown-toggle', function (e) {
                e.preventDefault();
                $(e.target).toggleClass('toggled-on');
                $(e.target).siblings('ul').stop().toggleClass('show');
            });
        }

    }

    function minHeight() {
        var bodyHeight = $(window).outerHeight(),
            headerHeight = $('header.site-header').outerHeight(true),
            footerHeight = $('footer.site-footer').outerHeight(true),
            siteMain = $('.site-main').outerHeight(true);

        if (bodyHeight > (headerHeight + footerHeight + siteMain)) {

            $('.site-main').css({
                'min-height': bodyHeight - headerHeight - footerHeight
            });
        }
    }

    function search_popup() {

        var $button_search = $('.button-search-popup');
        var $drop_down = $('.site-search-popup-wrap');
        $button_search.on('click', function (e) {
            e.preventDefault();
            $('html').toggleClass('search-popup-active');
        });

        $('.site-search-popup-close').on('click', function (e) {
            e.preventDefault();
            $('html').removeClass('search-popup-active');
        });

        $(document).mouseup(function (e) {
            if (!$drop_down.is(e.target) && $drop_down.has(e.target).length == 0) {
                $('html').removeClass('search-popup-active');
            }
        });
    }

    function side_collapse() {
        $('body').on('click', '[data-toggle="button-side"]', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var $target = $(this).data('target');
            $($target).toggleClass('active');

        }).on('click', '.side-overlay', function (e) {
            e.preventDefault();
            var $target = $(this).siblings('.side-wrap');
            $target.removeClass('active');
        }).on('click', '.close-side', function (e) {
            e.preventDefault();
            var $target = $(this).closest('.side-wrap');
            $target.removeClass('active');
        });

    }

    function account_side() {
        var $account_side = $('body .site-header-account .my-account-icon');
        var $account_active = $('body .site-header-account .account-dropdown');
        $(document).mouseup(function (e) {
            if ($account_side.has(e.target).length == 0 && !$account_active.is(e.target) && $account_active.has(e.target).length == 0) {
                $account_active.removeClass('active');
            }
        });

        $account_side.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var $target = $(this).siblings('.account-dropdown');
            $target.toggleClass('active');
        });
    }

    function setPositionLvN($item) {
        var sub = $item.children('.sub-menu'),
            offset = $item.offset(),
            width = $item.outerWidth(),
            screen_width = $(window).width(),
            sub_width = sub.outerWidth();
        var align_delta = offset.left + width + sub_width - screen_width;
        if (align_delta > 0) {
            if ($item.parents('.menu-item-has-children').length) {
                sub.css({ left: 'auto', right: '100%' });
            }else {
                sub.css({ left: 'auto', right: '0' });
            }
        } else {
            sub.css({ left: '', right: '' });
        }
    }

    function initSubmenuHover() {
        $('.site-header .primary-navigation .menu-item-has-children').hover(function (event) {
            var $item = $(event.currentTarget);
            setPositionLvN($item);
        });
    }

    $(document).on('skeletonScreen', function(){
        var $skel_bodies = $('.skeleton-body'),
            skel_cnt = $skel_bodies.length;
        $skel_bodies.each(
            function (e) {
                var $this = $(this);
                var dclPromise = (function () {
                    var deferred = $.Deferred();
                    if (typeof imagesLoaded === 'function') {
                        var $content = $this.find('script[type="text/template"]');
                        var cnt = $content.length;
                        $content.each(
                            function () {
                                $(JSON.parse($(this).html())).imagesLoaded(
                                    function () {
                                        if (0 == --cnt) {
                                            deferred.resolve();
                                        }
                                    }
                                );
                            }
                        )
                    }
                    return deferred.promise();
                })();
                $.when(dclPromise).done(
                    function (e) {
                        var $content = $this.find('script[type="text/template"]');
                        $content.map(
                            function () {
                                var $_element = $(this).next();
                                if($_element.hasClass('skeleton-widget')){
                                    setTimeout(function () {
                                        $(document).trigger('woo_widget_categories');
                                    }, 400);
                                }
                                var $parent = $(this).parent().parent();
                                $parent.children().remove();
                                $parent.append($(JSON.parse($(this).html())));

                                if($parent.hasClass('woocommerce-product-gallery')){
                                    $(document).trigger('skeletonScreen-single-product-loaded');
                                }

                                if($parent.find('.skeleton-body').length > 0){
                                    $(document).trigger('skeletonScreen');
                                }
                            }
                        );
                        $this.removeClass('skeleton-body');
                        --skel_cnt || $(document).trigger('organey_skeleton_loaded', $this);
                        $(document).trigger('init_woocommerce_price_range');
                        if ($.fn.mediaelementplayer) {
                            $(window.wp.mediaelement.initialize);
                        }
                    }
                );
            }
        );
    }).ready(function(){
        $(document).trigger('skeletonScreen');
    })


    initSubmenuHover();

    // minHeight();
    handleWindow();
    navigation();
    account_side();
    side_collapse();
    $(document).ready(function () {
        search_popup();
    });
})(jQuery);
