<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Admin navbar
 */
?>
<nav class="navbar navbar-fixed-top navbar-dark bg-primary">
  <div class="container">
    <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar2">
      &#9776;
    </button>
    <div class="collapse navbar-toggleable-xs" id="exCollapsingNavbar2">
      <a class="navbar-brand" href="<?php echo URL::build('/admin'); ?>"><?php echo $language->get('admin', 'admin_cp'); ?></a>
      <ul class="nav navbar-nav">
      </ul>
	  <ul class="nav navbar-nav pull-xs-right">
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