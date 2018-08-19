<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
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
                <?php
                if($user->hasPermission('admincp.core')){
                ?>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'core') echo ' active'; ?>" href="<?php echo URL::build('/admin/core'); ?>"><?php echo $language->get('admin', 'core'); ?></a>
				</li>
                <?php
                }
                if($user->hasPermission('admincp.minecraft')){
                ?>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'minecraft') echo ' active'; ?>" href="<?php echo URL::build('/admin/minecraft'); ?>"><?php echo $language->get('admin', 'minecraft'); ?></a>
				</li>
                <?php
                }
                if($user->hasPermission('admincp.modules')){
                ?>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'modules') echo ' active'; ?>" href="<?php echo URL::build('/admin/modules'); ?>"><?php echo $language->get('admin', 'modules'); ?></a>
				</li>
                <?php
                }
                if($user->hasPermission('admincp.pages')){
                ?>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'pages') echo ' active'; ?>" href="<?php echo URL::build('/admin/pages'); ?>"><?php echo $language->get('admin', 'pages'); ?></a>
				</li>
                <?php
                }
                if($user->hasPermission('admincp.security')){
                ?>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'security') echo ' active'; ?>" href="<?php echo URL::build('/admin/security'); ?>"><?php echo $language->get('admin', 'security'); ?></a>
				</li>
                <?php
                }
                if($user->hasPermission('admincp.sitemap')){
	                ?>
					<li class="nav-item">
						<a class="nav-link<?php if(isset($admin_page) && $admin_page == 'sitemap') echo ' active'; ?>" href="<?php echo URL::build('/admin/sitemap'); ?>"><?php echo $language->get('admin', 'sitemap'); ?></a>
					</li>
	                <?php
                }
                if($user->hasPermission('admincp.styles')){
                ?>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'styles') echo ' active'; ?>" href="<?php echo URL::build('/admin/styles'); ?>"><?php echo $language->get('admin', 'styles'); ?></a>
				</li>
                <?php
                }
                if($user->hasPermission('admincp.update')){
                ?>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'update') echo ' active'; ?>" href="<?php echo URL::build('/admin/update'); ?>"><?php echo $language->get('admin', 'update'); ?></a>
				</li>
                <?php
                }
                if($user->hasPermission('admincp.users') || $user->hasPermission('admincp.groups')){
                ?>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'users_and_groups') echo ' active'; ?>" href="<?php echo URL::build('/admin/users'); ?>"><?php echo $language->get('admin', 'users_and_groups'); ?></a>
				</li>
                <?php
                }
                if($user->hasPermission('admincp.widgets')){
                ?>
				<li class="nav-item">
				  <a class="nav-link<?php if(isset($admin_page) && $admin_page == 'widgets') echo ' active'; ?>" href="<?php echo URL::build('/admin/widgets'); ?>"><?php echo $language->get('admin', 'widgets'); ?></a>
				</li>
                <?php
                }
                ?>
				
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