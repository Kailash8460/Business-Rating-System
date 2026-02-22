$(document).ready(function () {
    $('.rating-readonly').each(function () {
        var ratingValue = $(this).data('score');
        $(this).raty({
            readOnly: true,
            half: true,
            score: ratingValue,
        });
    });
});