<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Admin sidebar
 */
?>
		  <div class="card card-inverse">
		    <div class="card-block">
			  <ul class="nav flex-column nav-pills">
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'overview') echo ' active'; ?>" href="<?php echo URL::build('/admin'); ?>"><?php echo $language->get('admin', 'overview'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'core') echo ' active'; ?>" href="<?php echo URL::build('/admin/core'); ?>"><?php echo $language->get('admin', 'core'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'minecraft') echo ' active'; ?>" href="<?php echo URL::build('/admin/minecraft'); ?>"><?php echo $language->get('admin', 'minecraft'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'modules') echo ' active'; ?>" href="<?php echo URL::build('/admin/modules'); ?>"><?php echo $language->get('admin', 'modules'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'pages') echo ' active'; ?>" href="<?php echo URL::build('/admin/pages'); ?>"><?php echo $language->get('admin', 'pages'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'security') echo ' active'; ?>" href="<?php echo URL::build('/admin/security'); ?>"><?php echo $language->get('admin', 'security'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'styles') echo ' active'; ?>" href="<?php echo URL::build('/admin/styles'); ?>"><?php echo $language->get('admin', 'styles'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'update') echo ' active'; ?>" href="<?php echo URL::build('/admin/update'); ?>"><?php echo $language->get('admin', 'update'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'users_and_groups') echo ' active'; ?>" href="<?php echo URL::build('/admin/users'); ?>"><?php echo $language->get('admin', 'users_and_groups'); ?></a>
				</li>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'widgets') echo ' active'; ?>" href="<?php echo URL::build('/admin/widgets'); ?>"><?php echo $language->get('admin', 'widgets'); ?></a>
				</li>
				
				<?php if(isset($admin_sidebar) && count($admin_sidebar)){ ?>
				<hr />
				
				<!-- Custom -->
				<?php 
					foreach($admin_sidebar as $key => $item){
					?>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == $key) echo ' active'; ?>" href="<?php echo $item['url']; ?>"><?php echo $item['title']; ?></a>
				</li>
					<?php
					}
				}
				?>
				
			  </ul>
		    </div>
		  </div>