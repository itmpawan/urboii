(function ($) {
    'use strict';
    var Countdown = function ($countdown, endTime, $) {
        var timeInterval,
            elements = {
                $daysSpan: $countdown.find('.countdown-days'),
                $hoursSpan: $countdown.find('.countdown-hours'),
                $minutesSpan: $countdown.find('.countdown-minutes'),
                $secondsSpan: $countdown.find('.countdown-seconds')
            };

        var updateClock = function () {
            var timeRemaining = Countdown.getTimeRemaining(endTime);

            $.each(timeRemaining.parts, function (timePart) {
                var $element = elements['$' + timePart + 'Span'],
                    partValue = this.toString();

                if (1 === partValue.length) {
                    partValue = 0 + partValue;
                }

                if ($element.length) {
                    $element.text(partValue);
                }
            });

            if (timeRemaining.total <= 0) {
                clearInterval(timeInterval);
            }
        };

        var initializeClock = function () {
            updateClock();

            timeInterval = setInterval(updateClock, 1000);
        };

        initializeClock();
    };
    Countdown.getTimeRemaining = function (endTime) {
        var timeRemaining = endTime - new Date(),
            seconds = Math.floor((timeRemaining / 1000) % 60),
            minutes = Math.floor((timeRemaining / 1000 / 60) % 60),
            hours = Math.floor((timeRemaining / (1000 * 60 * 60)) % 24),
            days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));

        if (days < 0 || hours < 0 || minutes < 0) {
            seconds = minutes = hours = days = 0;
        }

        return {
            total: timeRemaining,
            parts: {
                days: days,
                hours: hours,
                minutes: minutes,
                seconds: seconds
            }
        };
    };
    var $element = $('[data-countdown="true"]');
    $element.each(function (index, el) {
        var date = new Date($(this).data('date') * 1000);
        new Countdown($(this), date, $);
    });

    $(document).on('organey_skeleton_loaded', function(){
        var $element = $('[data-countdown="true"]');
        $element.each(function (index, el) {
            var date = new Date($(this).data('date') * 1000);
            new Countdown($(this), date, $);
        });
    });

})(jQuery);