toastr.options = {
	progressBar: true,
	closeButton: true,
	timeOut: 6000,
	extendedTimeOut: 6000,
	positionClass: 'toast-bottom-left'
};

function URLBuild(path, full = false) {
    return (full ? fullSiteURL : siteURL) + path;
}

function redirect(url) {
    window.location.href = url;
}

function copy(element) {
    var $temp = $('<input>');
    $('body').append($temp);
    $temp.val($(element).text()).select();
    document.execCommand('copy');
    $temp.remove();
	toastr.success(copied);
}
$(function() {
    
    $('.ui.sidebar').sidebar('attach events', '.toc.item');
    
    $('.ui.dropdown:not(.search)').dropdown();
    
    $('[data-toggle="tooltip"]').popup({
        hoverable: true
    });

	$('[data-toggle="popup"]').popup({ 
        hoverable: true,
        variation: 'special flowing',
    });
    
    $('[data-toggle="modal"]').click(function() {
        let att = $(this).attr("data-target");
        $(att).modal('show');
    });
	
	$('.menu .item').tab();

    $('.message .close').on('click', function() {
        $(this).closest('.message').transition('fade');
    });
	
});

$(function() {
    if (loadingTime !== '') {
        $('#page_load').html(loadingTime);
    }
});

$(function() {
    var cachedUsers = {};
    var timeoutId;

    $('*[data-poload]').mouseenter(function (){
        var elem = this;
        $.get($(elem).data('poload'),
        function(d) {
            (debugging ? console.log(d) : '');
            var data = JSON.parse(d);
            cachedUsers[$(elem).data('poload')] = data;
            var tmp = document.createElement('div');
            tmp.innerHTML = data.html;
            var img = tmp.getElementsByTagName('img')[0];
            var image = new Image();
            image.src = img.src;
        });
    });
                    
    $('*[data-poload]').popup({
        hoverable: true,
        variation: 'special flowing',
        html: '<i class="circle notch loading icon"></i>',
        delay: {show: 500, hide: 200},
        onShow: function(e) {this.html(cachedUsers[$(e).data('poload')].html)}
    });
});
