(function ($) {
    'use strict';

    function quick_product_variable() {
        var btnSelector = '.products .product-type-variable .add_to_cart_button';
        var xhr = false;
        $(document).on('click', btnSelector, function (e) {
            var $this = $(this),
                $product = $this.parents('.product').first(),
                $content = $product.find('.quick-shop-form'),
                id = $this.data('product_id'),
                loadingClass = 'btn-loading';

            if ($product.find('.variations_form').length > 0) {
                e.preventDefault();
                if ($this.hasClass(loadingClass)) return;

                if ($product.hasClass('quick-shop-loaded')) {
                    $product.addClass('quick-shop-shown');
                    $('body').trigger('organey-quick-view-displayed');
                    return;
                }

                $this.addClass(loadingClass);
                $product.addClass('loading-quick-shop');

                $.ajax({
                    url: organeyAjax.ajaxurl,
                    data: {
                        action: 'organey_quick_shop',
                        id: id,
                    },
                    method: 'get',
                    success: function (data) {
                        // insert variations form
                        $content.append(data);

                        initVariationForm($product);
                        // organeyThemeModule.swatchesVariations();
                        // console.log(data);
                    },
                    complete: function () {
                        setTimeout(function () {
                            $this.removeClass(loadingClass);
                            $product.removeClass('loading-quick-shop');
                            $product.addClass('quick-shop-shown quick-shop-loaded');
                            $('body').trigger('organey-quick-view-displayed');
                        }, 100);
                    },
                    error: function () {
                    },
                });
            }

        }).on('click', '.quick-shop-close', function () {
            var $this = $(this),
                $product = $this.parents('.product');
            $product.removeClass('quick-shop-shown');
        })
        //     .on('submit', 'form.cart', function (e) {
        //
        //     var $productWrapper = $(this).parents('.product');
        //     if ($productWrapper.hasClass('product-type-external') || $productWrapper.hasClass('product-type-zakeke')) return;
        //
        //     e.preventDefault();
        //
        //     var form = $(this);
        //     form.block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});
        //
        //     var formData = new FormData(form[0]);
        //     formData.append('add-to-cart', form.find('[name=add-to-cart]').val());
        //     if (xhr) {
        //         xhr.abort();
        //     }
        //     // Ajax action.
        //     xhr = $.ajax({
        //         url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'organey_add_to_cart'),
        //         data: formData,
        //         type: 'POST',
        //         processData: false,
        //         contentType: false,
        //         complete: function (response) {
        //             response = response.responseJSON;
        //
        //             if (!response) {
        //                 return;
        //             }
        //
        //             if (response.error && response.product_url) {
        //                 window.location = response.product_url;
        //                 return;
        //             }
        //
        //             // Redirect to cart option
        //             if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
        //                 window.location = wc_add_to_cart_params.cart_url;
        //                 return;
        //             }
        //
        //             var $thisbutton = form.find('.single_add_to_cart_button'); //
        //
        //             // Trigger event so themes can refresh other areas.
        //             $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
        //
        //             // Remove existing notices
        //             $('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();
        //
        //             // Add new notices
        //             $('.single-product .main .woocommerce-notices-wrapper').append(response.fragments.notices_html)
        //
        //             form.unblock();
        //             xhr = false;
        //         }
        //     });
        //
        // });
        $(document.body).on('added_to_cart', function () {
            $('.product').removeClass('quick-shop-shown');
        });
    }

    function quantity() {
        var $parent = $(".products");
        $parent.on("click", ".quantity input", function () {
            return false;
        });

        $parent.on("change input", ".quantity .qty", function () {
            var add_to_cart_button = $(this).parents(".product").find(".add_to_cart_button");
            add_to_cart_button.attr("data-quantity", $(this).val());
        });

        $parent.on("keypress", ".quantity .qty", function (e) {
            if ((e.which || e.keyCode) === 13) {
                $(this).parents(".product").find(".add_to_cart_button").trigger("click");
            }
        });
    }

    function initVariationForm($product) {
        $product.find('.variations_form').wc_variation_form().find('.variations select:eq(0)').change();
        $product.find('.variations_form').trigger('wc_variation_form');
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

    function tooltip() {
        $('body').on('mouseenter', '.shop-action .woosq-btn:not(.tooltipstered),.shop-action .woosw-btn:not(.tooltipstered),.shop-action .woosc-btn:not(.tooltipstered)', function () {
            var $element = $(this);
            if (typeof $.fn.tooltipster !== 'undefined') {
                $element.tooltipster({
                    position: 'top',
                    functionBefore: function (instance, helper) {
                        instance.content(instance._$origin.text());
                    },
                    theme: 'organey-product-tooltipster',
                    delay: 0,
                    animation: 'fade'
                }).tooltipster('show');
            }
        });
    }

    var xhr;

    function sendRequest(url) {

        if (xhr) {
            xhr.abort();
        }

        xhr = $.ajax({
            type: "GET",
            url: url,
            beforeSend: function () {
                $('ul.organey-products').addClass('preloader');
            },
            success: function (data) {
                let $html = $(data);
                $('#main > ul.organey-products').replaceWith($html.find('#main > ul.organey-products'));
                $('#main > .organey-woocommerce-pagination').replaceWith($html.find('#main > .organey-woocommerce-pagination'));
                $('.woocommerce-result-count').replaceWith($html.find('.woocommerce-result-count'));
                window.history.pushState(null, null, url);
                $(document).trigger('skeletonScreen');
                xhr = false;
            }
        });
    }

    function product_archive_layout() {
        var $body = $('body');
        $body.on('click', '.gridlist-toggle .list,.gridlist-toggle .grid', function (e) {
            e.preventDefault();
            if ($(this).hasClass('active')) {
                return;
            }
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            var url = $(this).attr('href');
            sendRequest(url);
        });

        $body.on('change', '.organey-products-per-page #per_page', function (e) {
            e.preventDefault();
            var url = this.value;
            sendRequest(url);
        });

    }
    product_archive_layout();
    quick_product_variable();
    woo_widget_categories();
    tooltip();

    $(document).on('woo_widget_categories', woo_widget_categories).ready(function () {
        quantity();
    });

})(jQuery);
