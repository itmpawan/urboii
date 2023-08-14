(function($){
    'use strict';
    $(document).on('click', '.lb_settings_page h2 .nav-tab', function(e){
        let $this = $(this),
            id = $this.attr('href'),
            $tab = $(id)
        $this.closest('.nav-tab-wrapper').find('>a').removeClass('nav-tab-active')
        $this.addClass('nav-tab-active');

        $('.lb_settings_page_content > table').removeClass('active');
        $tab.addClass('active')
    })
        .on('click', '.organey-group-devices .list-devices i', function(e){
            e.preventDefault();
            let $this = $(this),
                $list = $this.closest('.list-devices'),
                $wrapper = $this.closest('.organey-group-devices')

            $list.find('i').removeClass('active')
            $this.addClass('active');

            $wrapper.find('.organey-device').removeClass('active');
            $wrapper.find('.organey-device.'+ $this.data('device')).addClass('active');

        })
    $('.organey-color-picker').wpColorPicker();
})(jQuery);
