$(function() {
    $('div.spoiler-title').click(function() {
        $(this)
            .children()
            .first()
            .toggleClass('show-icon')
            .toggleClass('hide-icon');
        $(this)
            .parent().children().last().toggle();
    });
});
$(document).ready(function() {
    $('.hide-icon').each(function() {
        $(this).parent()
            .children()
            .first()
            .toggleClass('show-icon')
            .toggleClass('hide-icon');
        $(this).parent()
            .parent().children().last().toggle();
    });
});