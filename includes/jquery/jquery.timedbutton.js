$.fn.timedDisable = function(time) {
    if (time == null) {
        time = 5000;
    }
    var seconds = Math.ceil(time / 1000);
    return $(this).each(function() {
        $(this).attr('disabled', 'disabled');
        var disabledElem = $(this);
        var originalText = $(this).attr("value");
        disabledElem.attr("value", originalText + " (" + seconds + ")");
        var interval = setInterval(function() {
            disabledElem.attr("value", originalText + " (" + --seconds + ")");
            if (seconds === 0) {
                disabledElem.removeAttr('disabled')
                    .attr("value",originalText);
                clearInterval(interval);
            }
        }, 1000);
    });
};