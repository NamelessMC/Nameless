<div class="well well-sm">
	<ul class="nav nav-pills nav-stacked">
	  <li<?php if($mod_page == 'index'){ ?> class="active"<?php } ?>><a href="/mod"><?php echo $mod_language['overview']; ?></a></li>
	  <li<?php if($mod_page == 'reports'){ ?> class="active"<?php } ?>><a href="/mod/reports"><?php echo $mod_language['reports']; ?><?php if($reports == true){ ?> <span class="glyphicon glyphicon-exclamation-sign"></span><?php } ?></a></li>
	  <li<?php if($mod_page == 'punishments'){ ?> class="active"<?php } ?>><a href="/mod/punishments"><?php echo $mod_language['punishments']; ?></a></li>
	  <?php if($user->canViewApps($user->data()->id)){ ?><li<?php if($mod_page == 'applications'){ ?> class="active"<?php } ?>><a href="/mod/applications"><?php echo $navbar_language['staff_apps']; ?></a></li><?php } ?>
	  <?php 
	  if(isset($custom_mod_sidebar)){
          foreach($custom_mod_sidebar as $key => $item){
		    echo '<li' . ($mod_page == $key ? ' class="active"' : '') . '><a href="' . $item['url'] . '">' . $item['title'] . '</a></li>';
		  }
	  }
	  ?>
	</ul>
</div>