// DOM ready
$(function() {
	$('.selectSortBy').change(function() {
		var value = this.value;
		var currUrl = new String(getCleanUrl());
		var url;
		if(currUrl.indexOf("sortBy=") > -1){
			url = currUrl.replace(sortByRegex, "&sortBy=" + value);
		}else{
			url = currUrl + "&sortBy=" + value;
		}
		document.location.href = url;
	});
	
	$( "#pname-input" ).autocomplete({
		 source: "index.php?p=profile&action=searchplayer",
		 minLength: 2,
		 select: function( event, ui ) {
			 document.location.href = "index.php?p=profile&player=" + ui.item.value;
		 }
	});
	
	$(".ajax-form").submit(function(event){
		// Cancel form sending
		event.preventDefault();
		
		var $form = $(this);
		var url = $form.attr("action");
		var method = $form.attr("method");
	    var serializedData = $form.serialize();

	    // Start the request
	    var request;
	    if(method == "post"){
		    request = $.ajax({
		        url: url,
		        type: "post",
		        data: serializedData
		    });
	    }else{
		    request = $.ajax({
		        url: url,
		        type: "get"
		    });
	    }

	    handleRequest(request);
	});
});
//Handle ajax request result
function handleRequest(request){
    request.done(function (jsonResponse, textStatus, jqXHR){
    	var response = JSON.parse(jsonResponse);
    	if(response.urlArgs != null){
    		document.location.href = document.location.href.replace(/\\?.*$/, "?") + response.urlArgs;
    	}
    	else if(response.mustReload){
    		setTimeout(function(){location.reload();}, 3000);
    		$('#modal-info').on('hide.bs.modal', function () {
    			location.reload();
    		});
    	}
    	displayModalInfo(response.message);
    });

    request.fail(function (jqXHR, textStatus, errorThrown){
    	displayModalInfo("An error happend during the transaction : " + errorThrown);
    });

    request.always(function () {
    	// Hide all the displayed modal
        $('.modal:not(#modal-info)').modal('hide');
        // Hide the modal info 3 seconds after its showing
        setTimeout(function(){$('#modal-info').modal('hide');}, 3000);
    });
}
// Function to choose the page and handle the sortBy select in punishment list
var pageRegex = /&pageNo=\d+/;
var sortByRegex = /&sortBy=\w*/;
function openPageSelector(){
	var page = prompt("Type the page");
	if(page < 1){
		page = 1;
	}
	choosePage(page);
}
function choosePage(pageNo){
	var currUrl = new String(getCleanUrl());
	var url;
	if(currUrl.indexOf("pageNo=") > -1){
		url = currUrl.replace(pageRegex, "&pageNo=" + pageNo);
	}else{
		url = currUrl + "&pageNo=" + pageNo;
	}
	document.location.href = url; 
}
// Utils
function getCleanUrl(){
	return document.location.href.replace(/#$/, "");
}
function deployPanel(id){
	$('#' + id).toggleClass("show hidden");
}
// Modal info management
function displayModalInfo(content){
    $('#modal-info-content').html(content);
    $('#modal-info').modal('show');
}
function logout(){
    var request = $.ajax({
        url: "index.php?p=admin&action=logout",
        type: "get"
    });

    handleRequest(request);
}