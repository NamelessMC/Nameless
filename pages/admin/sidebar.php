<div class="well well-sm">
  <div class="nav nav-pills nav-stacked">
    <li<?php if($adm_page == "index"){ ?> class="active"<?php } ?>><a href="/admin"><?php echo $admin_language['index']; ?></a></li>
	<li<?php if($adm_page == "addons"){ ?> class="active"<?php } ?>><a href="/admin/addons"><?php echo $admin_language['addons']; ?></a></li>
	<li<?php if($adm_page == "announcements"){ ?> class="active"<?php } ?>><a href="/admin/announcements"><?php echo $admin_language['announcements']; ?></a></li>
	<li<?php if($adm_page == "core"){ ?> class="active"<?php } ?>><a href="/admin/core"><?php echo $admin_language['core']; ?></a></li>
	<li<?php if($adm_page == "custom_pages"){ ?> class="active"<?php } ?>><a href="/admin/pages"><?php echo $admin_language['custom_pages']; ?></a></li>
    <li<?php if($adm_page == "forums"){ ?> class="active"<?php } ?>><a href="/admin/forums"><?php echo $admin_language['forums']; ?></a></li>
    <li<?php if($adm_page == "minecraft"){ ?> class="active"<?php } ?>><a href="/admin/minecraft"><?php echo $admin_language['minecraft']; ?></a></li>
	<li<?php if($adm_page == "misc"){ ?> class="active"<?php } ?>><a href="/admin/misc"><?php echo $admin_language['misc']; ?></a></li>
	<li<?php if($adm_page == "styles"){ ?> class="active"<?php } ?>><a href="/admin/styles"><?php echo $admin_language['style']; ?></a></li>
	<li<?php if($adm_page == "update"){ ?> class="active"<?php } ?>><a href="/admin/update"><?php echo $admin_language['update']; ?></a></li>
	<li<?php if($adm_page == "users"){ ?> class="active"<?php } ?>><a href="/admin/users"><?php echo $admin_language['users_and_groups']; ?></a></li>
	<li<?php if($adm_page == "help"){ ?> class="active"<?php } ?>><a href="/admin/help"><?php echo $admin_language['help']; ?></a></li>
	<?php foreach($admin_sidebar as $item){ ?>
	<li<?php if($adm_page == $item['title']){ ?> class="active"<?php } ?>><a href="<?php echo htmlspecialchars($item['path']); ?>"><?php echo htmlspecialchars($item['title']); ?></a></li>
	<?php } ?>
  </div>
</div>
