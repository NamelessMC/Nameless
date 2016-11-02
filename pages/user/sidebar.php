<div class="well well-sm">
  <ul class="nav nav-pills nav-stacked">
	<li<?php if($user_page == 'index'){ ?> class="active"<?php } ?>><a href="/user"><?php echo $mod_language['overview']; ?></a></li>
	<li<?php if($user_page == 'messaging'){ ?> class="active"<?php } ?>><a href="/user/messaging"><?php echo $user_language['private_messages']; ?></a></li>
	<li<?php if($user_page == 'alerts'){ ?> class="active"<?php } ?>><a href="/user/alerts"><?php echo $user_language['alerts']; ?></a></li>
	<li<?php if($user_page == 'settings'){ ?> class="active"<?php } ?>><a href="/user/settings"><?php echo $user_language['profile_settings']; ?></a></li>
	<?php 
	if(isset($custom_user_sidebar)){
        foreach($custom_user_sidebar as $key => $item){
		  echo '<li' . ($user_page == $key ? ' class="active"' : '') . '><a href="' . $item['url'] . '">' . $item['title'] . '</a></li>';
		}
	}
	?>
  </ul>
</div>
