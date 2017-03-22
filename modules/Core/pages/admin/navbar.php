<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Admin navbar
 */
?>
<nav class="navbar navbar-toggleable-md fixed-top navbar-inverse bg-primary">
  <div class="container">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <a class="navbar-brand" href="<?php echo URL::build('/admin'); ?>"><?php echo $language->get('admin', 'admin_cp'); ?></a>
      <ul class="navbar-nav mr-auto">
      </ul>
	  <ul class="navbar-nav float-right">
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URL::build('/'); ?>" target="_blank"><?php echo $language->get('admin', 'view_site'); ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php echo str_replace('{x}', htmlspecialchars($user->data()->nickname), $language->get('admin', 'signed_in_as_x')); ?></a>
        </li>
	  </ul>
    </div>
  </div>
</nav>

<div style="padding-top: 5rem;"></div>
