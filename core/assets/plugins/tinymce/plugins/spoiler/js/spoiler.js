$(function(){
    $('.spoiler-text').hide();
    $('.spoiler-toggle').click(function(){
        $(this).next().toggle();
    });
});
