(function ($) {
    'use strict';
    const selectorsClick = [
        '#secondary .widget .product-categories a',
        '#secondary .widget .product-brands a',
        '#secondary .widget .woocommerce-widget-layered-nav-list a',
        '#secondary .widget.widget_layered_nav_filters a',
        '#secondary .widget.widget_rating_filter a',
        '#secondary .widget_product_tag_cloud a',

        '#organey-canvas-filter .widget .product-categories a',
        '#organey-canvas-filter .widget .product-brands a',
        '#organey-canvas-filter .widget .woocommerce-widget-layered-nav-list a',
        '#organey-canvas-filter .widget.widget_layered_nav_filters a',
        '#organey-canvas-filter .widget.widget_rating_filter a',
        '#organey-canvas-filter .widget_product_tag_cloud a',

        '#main ul.products + .woocommerce-pagination a',
        '#secondary .widget .product-brands a',
    ];

    const selectorsOptions = [
        '#secondary .widget select.woocommerce-widget-layered-nav-dropdown',
        '#organey-canvas-filter .widget select.woocommerce-widget-layered-nav-dropdown',
    ];

    $('body').on('click', selectorsClick.join(','), (event) => {
        event.preventDefault();
        let $this = $(event.currentTarget);
        let url = $this.attr('href');
        let objUrl = new URL(url);
        let search = location.search.substring(1);

        if(search) {
            let searchString = decodeURI(search).split('&')
            search = {};
            for(let s of searchString){
                let arrS = s.split('=');
                if(arrS.length <= 1){
                    search[arrS[0]] = '';
                }else{
                    search[arrS[0]] = arrS[1];
                }
            }
            if (search['woocommerce_archive_layout']){
                objUrl.searchParams.append('woocommerce_archive_layout',search['woocommerce_archive_layout']);
            }
            if (search['woocommerce_catalog_columns']){
                objUrl.searchParams.append('woocommerce_catalog_columns',search['woocommerce_catalog_columns']);
            }
        }
        sendRequest(objUrl.toString());
    }).on('change', selectorsOptions.join(','), (event) => {
        event.preventDefault();
        let $this = $(event.currentTarget),
            value = $this.val();
        $this.closest( 'form' )
            .find('input[type="hidden"]').val($this.val())
            .closest( 'form' )
            .trigger( 'submit' );

    })

    $(window).on("popstate", () => {
        if (history.state && history.state.woofilter) {
            renderHtml(history.state);
        }
    });

    function scrollUp() {

        let position = $('#primary').offset().top;

        if ($('#wpadminbar').length > 0) {
            position -= $('#wpadminbar').outerHeight();
        }
        if ($(window).scrollTop() > position) {
            $('html, body').animate({scrollTop: position}, 'slow');
        }
    }

    function sendRequest(url, replace = true) {
        $('ul.organey-products').addClass('preloader');
        $.post(url, (data) => {
            if (data) {
                let dom = new DOMParser()
                    .parseFromString(data, "text/html")
                let $html = $(dom);
                let state = {
                    woofilter: true,
                    title: $html.filter('title').text(),
                    sidebar: $html.find('#secondary').html(),
                    content: $html.find('#primary').html(),
                    filter: $html.find('.organey-canvas-filter-wrap').html(),
                    pagetitle: $html.find('#page-title-bar').html(),
                    breadcrumb: $html.find('.elementor-widget-woocommerce-breadcrumb').html(),
                    pagedesc: $html.find('header.woocommerce-products-header').html()
                };
                renderHtml(state);
                window.history.pushState(state, state.title, url);
            }
        })
    }

    function renderHtml(state) {
        scrollUp();
        $('head title').text(state.title);
        $('#primary').html(state.content);
        $('#secondary').html(state.sidebar);
        $('#organey-canvas-filter .organey-canvas-filter-wrap').html(state.filter);
        $('#page-title-bar').html(state.pagetitle);
        $('header.woocommerce-products-header').html(state.pagedesc);
        $('.elementor-widget-woocommerce-breadcrumb').html(state.breadcrumb);
        initPriceSlider();
        initOrdering();
        woo_widget_categories();
        clone_sidebar();
        $(document).trigger('skeletonScreen');
    }

    function initOrdering() {
        setTimeout(() => {
            $('.woocommerce-ordering').off('change');
            $('.woocommerce-ordering').on('change', (event) => {
                let $select = $(event.currentTarget).find('.orderby');
                let url = window.location.href.replace(/&orderby(=[^&]*)?|^orderby(=[^&]*)?&?/g, '')
                    .replace(/\?orderby(=[^&]*)?|^orderby(=[^&]*)?&?/g, '?')
                    .replace(/\?$/g, '')


                if (url.indexOf('?') !== -1) {
                    url = url + `&orderby=${$select.val()}`;
                } else {
                    url = url + `?orderby=${$select.val()}`;
                }
                sendRequest(url);
            })
        }, 100);
    }

    function initPriceSlider() {
        setTimeout(() => {
            if ($('.price_slider:not(.ui-slider)').length <= 0) {
                return true;
            }
            $('input#min_price, input#max_price').hide();
            $('.price_slider, .price_label').show();

            let min_price = $('.price_slider_amount #min_price').data('min'),
                max_price = $('.price_slider_amount #max_price').data('max'),
                current_min_price = $('.price_slider_amount #min_price').val(),
                current_max_price = $('.price_slider_amount #max_price').val();

            $('.price_slider:not(.ui-slider)').slider({
                range: true,
                animate: true,
                min: min_price,
                max: max_price,
                values: [current_min_price, current_max_price],
                create: function () {

                    $('.price_slider_amount #min_price').val(current_min_price);
                    $('.price_slider_amount #max_price').val(current_max_price);

                    $(document.body).trigger('price_slider_create', [current_min_price, current_max_price]);
                },
                slide: function (event, ui) {

                    $('input#min_price').val(ui.values[0]);
                    $('input#max_price').val(ui.values[1]);

                    $(document.body).trigger('price_slider_slide', [ui.values[0], ui.values[1]]);
                },
                change: function (event, ui) {

                    $(document.body).trigger('price_slider_change', [ui.values[0], ui.values[1]]);
                }
            });
        }, 200);
    }

    function priceSlideChange() {
        $(document.body).bind('price_slider_change', (event, min, max) => {
            let url = window.location.href.replace(/(min_price=\d+\&*|max_price=\d+\&*)/g, '')
                .replace(/\?$/g, '')
            if (url.indexOf('?') !== -1) {
                url = url + `&min_price=${min}&max_price=${max}`;
            } else {
                url = url + `?min_price=${min}&max_price=${max}`;
            }
            sendRequest(url);
        })
    }

    function clone_sidebar() {
        var $canvas = $('.organey-canvas-filter-wrap');
        if(!$('body').hasClass('shop_filter_canvas')){
            if($(window).width() < 1024){
                $('#secondary').children().appendTo(".organey-canvas-filter-wrap");
                $('.organey-dropdown-filter-wrap').children().appendTo(".organey-canvas-filter-wrap");
            }else {
                $canvas.children().appendTo("#secondary");

                $canvas.children().appendTo(".organey-dropdown-filter-wrap");
            }
        }

    }

    function woo_widget_categories() {
        var widget = $('.widget_product_categories'),
            main_ul = widget.find('ul');
        if (main_ul.length) {
            var dropdown_widget_nav = function () {

                main_ul.find('li').each(function () {

                    var main = $(this),
                        link = main.find('> a'),
                        ul = main.find('> ul.children');
                    if (ul.length) {

                        if (main.hasClass('current-cat-parent')) {
                            ul.show();
                            link.before('<i class="organey-icon-minus-square"></i>');
                            main.addClass('closed');
                        } else if (main.hasClass('opened')) {
                            link.before('<i class="organey-icon-plus-square"></i>');
                            ul.hide();
                        } else {
                            main.addClass('opened');
                            link.before('<i class="organey-icon-plus-square"></i>');
                        }

                        // on click
                        main.find('i').on('click', function (e) {

                            ul.slideToggle('slow');

                            if (main.hasClass('closed')) {
                                main.removeClass('closed').addClass('opened');
                                main.find('>i').removeClass('organey-icon-minus-square').addClass('organey-icon-plus-square');
                            } else {
                                main.removeClass('opened').addClass('closed');
                                main.find('>i').removeClass('organey-icon-plus-square').addClass('organey-icon-minus-square');
                            }

                            e.stopImmediatePropagation();
                        });

                        main.on('click', function (e) {

                            if ($(e.target).filter('a').length)
                                return;

                            ul.slideToggle('slow');

                            if (main.hasClass('closed')) {
                                main.removeClass('closed').addClass('opened');
                                main.find('i').removeClass('organey-icon-minus-square').addClass('organey-icon-plus-square');
                            } else {
                                main.removeClass('opened').addClass('closed');
                                main.find('i').removeClass('organey-icon-plus-square').addClass('organey-icon-minus-square');
                            }

                            e.stopImmediatePropagation();
                        });
                    }
                });
            };
            dropdown_widget_nav();
        }
    }


    $(document).ready(function () {
        priceSlideChange();
        initOrdering();
    }).on('init_woocommerce_price_range', function(){
        initPriceSlider();
    });

})(jQuery);
