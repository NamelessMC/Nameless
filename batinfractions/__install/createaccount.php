<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>BungeeAdminTools WebInterface Installation</title>
<!-- Bootstrap includes -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script type="text/javascript">
$(function() {
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

function handleRequest(request){
    request.done(function (jsonResponse, textStatus, jqXHR){
    	var response = JSON.parse(jsonResponse);
    	if(response.message == "Account successfully created!"){
			document.location.href = "index.php";
        }
    	displayModalInfo(response.message);
    });

    request.fail(function (jqXHR, textStatus, errorThrown){
    	displayModalInfo("An error occured during the transaction: " + errorThrown);
    });

    request.always(function () {
    	// Hide all the displayed modal
        $('.modal:not(#modal-info)').modal('hide');
        // Hide the modal info 3 seconds after its showing
        setTimeout(function(){$('#modal-info').modal('hide');}, 3000);
    });
}
function displayModalInfo(content){
    $('#modal-info-content').html(content);
    $('#modal-info').modal('show');
}
</script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body style="background: #A7DBD8;">
	<nav class="navbar" role="navigation"
		style="background: #FA6900; color: #E0E4CC;">
		<div class="container-fluid">
			<div class="collapse navbar-collapse"
				id="bs-example-navbar-collapse-1">
				<center>
					<h1>BungeeAdminTools WebInterface - Installation</h1>
				</center>
			</div>
		</div>
	</nav>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<center>
				<div class="jumbotron">
					<h4 style="text-decoration: underline;">Create an admin account :</h4>
					<form class="form-horizontal ajax-form" role="form" method="post"
						autocomplete="off" action="createaccountlogic.php">
						<div class="form-group">
							<div class="col-sm-3"></div>
							<label for="username" class="col-sm-1 control-label">Username</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" id="username"
									name="user" placeholder="Enter username" autocomplete="off">
							</div>
							<div class="col-sm-3"></div>
						</div>
						<div class="form-group">
							<div class="col-sm-3"></div>
							<label for="password" class="col-sm-1 control-label">Password</label>
							<div class="col-sm-4">
								<input type="password" class="form-control" id="password"
									name="password" placeholder="Password" autocomplete="off">
							</div>
							<div class="col-sm-3"></div>
						</div>
						<div class="row">
							<button type="submit" class="btn btn-success">Create account</button>
						</div>
					</form>
				</div>
			</center>
		</div>
		<div class="col-md-2"></div>
	</div>
</body>
</html>
<?php require("../application/views/_template/modal-info.php");?>
