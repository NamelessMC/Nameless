	<li class="dropdown alert-dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span style="margin: -10px 0px; font-size: 16px;"><i class="fa fa-envelope"></i> <div style="display: inline;" id="pms"></div></span></a>
	<ul class="dropdown-menu">
		<li><a href="#">Click View Messages</a></li>
		<li role="separator" class="divider"></li>
		<li><a href="../user/messaging">View Messages</a></li>
	</ul>
	</li>	
	
	<li class="dropdown alert-dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span style="margin: -10px 0px; font-size: 16px;"><i class="fa fa-flag"></i> <div style="display: inline;" id="alerts"></div></span></a>
		<ul class="dropdown-menu">
			<li><a href="#">Click View Alerts</a></li>
			<li role="separator" class="divider"></li>
			<li><a href="../user/alerts">View Alerts</a></li>
		</ul>
	</li>
	
	<li class="dropdown">
	<a href="#" data-toggle="dropdown" role="button" aria-expanded="false">&nbsp;&nbsp;<?php echo $this->getUsername() ?> <span class="caret"></span></a>
	<ul class="dropdown-menu" role="menu">
		<li><a href="../profile/<?php echo $this->getUsername() ?>">Profile</a></li>
		<li class="divider"></li>
		<li><a href="../user">UserCP</a></li>
		<li><a href="../mod">ModCP</a></li>
		<li><a href="../admin">NMC AdminCP</a></li>
		<li class="<?php if(get_class($this) == "admin"){echo "active";}?>"><a href="index.php?p=admin">BAT AdminCP</a></li>
		<li class="divider"></li>
		<li><a href="../infractions">Infractions</a></li>
		<li class="divider"></li>
		<li><a href="#" onclick="logout();">Sign Out</a></li>
	</ul>
	</li>

<!-- Some librairies or CSS files are only use in admin panel, so it's better to load them there -->
<!-- Datepicker includes -->
<script type="text/javascript" src="public/js/moment.js"></script>
<script type="text/javascript" src="public/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.44/css/bootstrap-datetimepicker.min.css">