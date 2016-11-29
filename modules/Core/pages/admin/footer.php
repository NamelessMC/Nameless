<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Admin footer
 */
?>

<footer>
  <div class="container">
    <div class="card">
      <div class="card-block">
		<ul class="nav nav-pills dropup">
		  <li class="nav-item">
			<a class="nav-link" href="#" onclick="nightMode();"><?php if($user->data()->night_mode == 1) echo $language->get('admin', 'disable_night_mode'); else echo $language->get('admin', 'enable_night_mode'); ?></a>
		  </li>
		    <span class="pull-right">
		    <li class="dropdown">
			  <a class="dropdown-toggle" data-toggle="dropdown" href="#">&copy; NamelessMC <?php echo date('Y'); ?></a>
			  <ul class="dropdown-menu">
			    <li class="nav-item"><a class="nav-link" href="https://github.com/NamelessMC/Nameless" target="_blank"><i class="fa fa-github" aria-hidden="true"></i> Source</a></li>
			  </ul>
		    </li>
			</span>
		  </ul>
      </div>
    </div>
  </div>
</footer>