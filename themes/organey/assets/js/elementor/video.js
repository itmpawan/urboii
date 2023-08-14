(function ($) {
    "use strict";

    $(window).on('elementor/frontend/init', () => {

        // Video
        elementorFrontend.hooks.addAction( 'frontend/element_ready/organey-video-popup.default', ( $scope ) => {
            $scope.find('.organey-video-popup a.elementor-video-popup').magnificPopup({
                type: 'iframe',
                removalDelay: 500,
                midClick: true,
                closeBtnInside: true,
                callbacks: {
                    beforeOpen: function() {
                        this.st.mainClass = this.st.el.attr('data-effect');
                    }
                },
            });
        } );
    });

})(jQuery);
