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
<nav class="navbar navbar-toggleable-md navbar-inverse bg-primary">
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	<span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="<?php echo URL::build('/admin'); ?>"><?php echo $language->get('admin', 'admin_cp'); ?></a>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
	<ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo URL::build('/'); ?>" target="_blank"><?php echo $language->get('admin', 'view_site'); ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><?php echo str_replace('{x}', htmlspecialchars($user->data()->nickname), $language->get('admin', 'signed_in_as_x')); ?></a>
      </li>
  </div>
</nav>

<div style="padding-top: 2rem;"></div>