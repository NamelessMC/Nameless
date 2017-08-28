<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Admin footer
 */
?>
<br />
<footer>
  <div class="container">
    <div class="card">
      <div class="card-block">
		<ul class="nav nav-pills dropup">
          <span class="float-right">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">&copy; NamelessMC <?php echo date('Y'); ?></a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="https://namelessmc.com/" target="_blank"><i class="fa fa-github" aria-hidden="true"></i> Source</a>
              </div>
            </li>
          </span>
          <li class="nav-item">
            <a class="nav-link" href="#" onclick="nightMode();"><?php if($user->data()->night_mode == 1) echo $language->get('admin', 'disable_night_mode'); else echo $language->get('admin', 'enable_night_mode'); ?></a>
          </li>
		</ul>
      </div>
    </div>
  </div>
</footer>
<br />