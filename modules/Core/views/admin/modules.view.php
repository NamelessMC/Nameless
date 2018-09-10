<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>" <?php if(defined('HTML_RTL') && HTML_RTL === true) echo ' dir="rtl"'; ?>>
  <head>
    <!-- Standard Meta -->
    <meta charset="<?php echo (defined('LANG_CHARSET') ? LANG_CHARSET : 'utf-8'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<?php
	$title = $language->get('admin', 'admin_cp');
	require(ROOT_PATH . '/core/templates/admin_header.php');
	?>

  </head>
  <body>
    <?php require(ROOT_PATH . '/modules/Core/pages/admin/navbar.php'); ?>
	<div class="container">
	  <div class="row">
	    <div class="col-md-3">
		  <?php require(ROOT_PATH . '/modules/Core/pages/admin/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <div class="card">
		    <div class="card-block">
			  <h3 style="display:inline;"><?php echo $language->get('admin', 'modules'); ?></h3>
			  <span class="pull-right">
			    <a class="btn btn-primary" href="<?php echo URL::build('/admin/modules/', 'action=install'); ?>"><?php echo $language->get('admin', 'install'); ?></a>
			  </span>
			  <br />
			  <hr />
			  <?php
			  if(Session::exists('admin_modules')){
				  echo Session::flash('admin_modules');
			  }

			  // Get all modules
			  $modules = $queries->getWhere('modules', array('id', '<>', 0));
			  $enabled_modules = Module::getModules();

			  foreach($modules as $item){
				  $exists = false;
				  foreach($enabled_modules as $enabled_item){
					  if($enabled_item->getName() == $item->name){
						  $exists = true;
						  $module = $enabled_item;
						  break;
					  }
				  }

				  if(!$exists){
					  require_once(ROOT_PATH . '/modules/' . $item->name . '/init.php');
				  }
			  ?>
			  <div class="row">
			    <div class="col-md-9">
				    <strong><?php echo htmlspecialchars($module->getName()); ?></strong> <small><?php echo $module->getVersion(); ?></small>
					<br /><small><?php echo $language->get('admin', 'author'); ?> <?php echo $module->getAuthor(); ?></small>
				</div>
				<div class="col-md-3">
				  <span class="pull-right">
				    <?php
				    if($module->getName() == 'Core'){
					?>
				    <a href="#" class="btn btn-warning disabled"><i class="fa fa-lock" aria-hidden="true"></i></a>
					<!--<a href="<?php //echo URL::build('/admin/modules/', 'action=edit&m=' . $module->id); ?>" class="btn btn-primary"><i class="fa fa-cogs" aria-hidden="true"></i></a>-->
					<?php
					} else {
						if($item->enabled == 1){
					?>
					<a href="<?php echo URL::build('/admin/modules/', 'action=disable&m=' . $item->id); ?>" class="btn btn-danger"><?php echo $language->get('admin', 'disable'); ?></a>
					<!--<a href="<?php //echo URL::build('/admin/modules/', 'action=edit&m=' . $module->id); ?>" class="btn btn-primary"><i class="fa fa-cogs" aria-hidden="true"></i></a>-->
					<?php
						} else {
					?>
					<a href="<?php echo URL::build('/admin/modules/', 'action=enable&m=' . $item->id); ?>" class="btn btn-success"><?php echo $language->get('admin', 'enable'); ?></a>
					<?php
						}
					}
					?>
				  </span>
				</div>
			  </div>
			  <hr />
			  <?php
			  }
			  ?>
		    </div>
		  </div>
		</div>
	  </div>
    </div>

		<?php require(ROOT_PATH . '/modules/Core/pages/admin/footer.php'); ?>

    <?php require(ROOT_PATH . '/modules/Core/pages/admin/scripts.php'); ?>

  </body>
</html>
