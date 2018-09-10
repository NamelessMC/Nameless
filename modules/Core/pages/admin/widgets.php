<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Admin widgets page
 */

if($user->isLoggedIn()){
    if(!$user->canViewACP()){
        // No
        Redirect::to(URL::build('/'));
        die();
    } else {
        // Check the user has re-authenticated
        if(!$user->isAdmLoggedIn()){
            // They haven't, do so now
            Redirect::to(URL::build('/admin/auth'));
            die();
        } else if(!$user->hasPermission('admincp.widgets')){
            // Can't view this page
            require(ROOT_PATH . '/404.php');
            die();
        }
    }
} else {
    // Not logged in
    Redirect::to(URL::build('/login'));
    die();
}

$page = 'admin';
$admin_page = 'widgets';

Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if(isset($_GET['action'])){
    if($_GET['action'] == 'enable'){
        // Enable a widget
        if(!isset($_GET['w']) || !is_numeric($_GET['w'])) die('Invalid widget!');

        // Get widget name
        $name = $queries->getWhere('widgets', array('id', '=', $_GET['w']));

        if(count($name)){
            $name = Output::getClean($name[0]->name);
            $widget = $widgets->getWidget($name);

            if(!is_null($widget)){
                $queries->update('widgets', $_GET['w'], array(
                    'enabled' => 1
                ));

                $widgets->enable($widget);

                Session::flash('admin_widgets', '<div class="alert alert-success">' . $language->get('admin', 'widget_enabled') . '</div>');
            }
        }

        Redirect::to(URL::build('/admin/widgets'));
        die();

    } else if($_GET['action'] == 'disable'){
        // Disable a widget
        if(!isset($_GET['w']) || !is_numeric($_GET['w'])) die('Invalid widget!');

        // Get widget name
        $name = $queries->getWhere('widgets', array('id', '=', $_GET['w']));
        if(count($name)){
            $name = htmlspecialchars($name[0]->name);
            $widget = $widgets->getWidget($name);

            if(!is_null($widget)){
                $queries->update('widgets', $_GET['w'], array(
                    'enabled' => 0
                ));

                $widgets->disable($widget);

                Session::flash('admin_widgets', '<div class="alert alert-success">' . $language->get('admin', 'widget_disabled') . '</div>');
            }
        }

        Redirect::to(URL::build('/admin/widgets'));
        die();

    }
}
?>
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
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.css">
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
                    <h3><?php echo $language->get('admin', 'widgets'); ?></h3>

                    <hr />
                    <?php
                    if(!isset($_GET['action'])){
                        if(Session::exists('admin_widgets')){
                            echo Session::flash('admin_widgets');
                        }

                        // List widgets
                        foreach($widgets->getAll() as $widget){
                            $widget_query = $queries->getWhere('widgets', array('name', '=', $widget->getName()));
                            if(!count($widget_query)){
                                $queries->create('widgets', array(
                                    'name' => $widget->getName()
                                ));
                                $widget_query = $queries->getLastId();
                            } else
                                $widget_query = $widget_query[0]->id;
                            ?>
                            <div class="row">
                                <div class="col-md-9">
                                    <strong><?php echo Output::getClean($widget->getName()); ?></strong> <small>(<?php echo str_replace('{x}', Output::getClean($widget->getModule()), $language->get('admin', 'module_x')); ?>)</small>
                                    <br/>
                                    <small><?php echo Output::getClean($widget->getDescription()); ?></small>
                                </div>
                                <div class="col-md-3">
				  <span class="pull-right">
				    <?php
                    if($widgets->isEnabled($widget)){
                        ?>
                        <a href="<?php echo URL::build('/admin/widgets/', 'action=disable&w=' . $widget_query); ?>"
                           class="btn btn-danger"><?php echo $language->get('admin', 'disable'); ?></a>
                        <a href="<?php echo URL::build('/admin/widgets/', 'action=edit&w=' . $widget_query); ?>"
                           class="btn btn-info"><i class="fa fa-cogs" aria-hidden="true"></i></a>
                        <?php
                    } else {
                        ?>
                        <a href="<?php echo URL::build('/admin/widgets/', 'action=enable&w=' . $widget_query); ?>"
                           class="btn btn-success"><?php echo $language->get('admin', 'enable'); ?></a>
                        <?php
                    }
                    ?>
				  </span>
                                </div>
                            </div>
                            <hr />
                            <?php
                        }
                    } else {
                        // Ensure widget exists
                        if (!isset($_GET['w']) || !is_numeric($_GET['w'])) {
                            Redirect::to(URL::build('/admin/widgets'));
                            die();
                        }

                        $widget = $queries->getWhere('widgets', array('id', '=', $_GET['w']));
                        if (!count($widget)) {
                            Redirect::to(URL::build('/admin/widgets'));
                            die();
                        }
                        $widget = $widget[0];

                        if($_GET['action'] == 'settings'){
                            // Widget settings
                            if($widgets->getWidget($widget->name)->getSettings() == null || !file_exists($widgets->getWidget($widget->name)->getSettings())){
                                Redirect::to(URL::build('/admin/widgets'));
                                die();
                            } else
                                require_once($widgets->getWidget($widget->name)->getSettings());

                        } else if($_GET['action'] == 'edit'){
                            // Editing widget
                            $active_pages = json_decode($widget->pages, true);

                            if (Input::exists()) {
                                if (Token::check(Input::get('token'))) {
                                    try {
                                        // Updated pages list
                                        if (isset($_POST['pages']) && count($_POST['pages']))
                                            $active_pages = $_POST['pages'];
                                        else
                                            $active_pages = array();

                                        $active_pages_string = json_encode($active_pages);

                                        $order = (isset($_POST['order']) ? $_POST['order'] : 10);

                                        $queries->update('widgets', $widget->id, array('pages' => $active_pages_string, '`order`' => $order));

                                        Log::getInstance()->log(Log::Action('admin/widget/update'), $widget->name);

                                        Redirect::to(URL::build('/admin/widgets/', 'action=edit&w=' . $widget->id));
                                        die();
                                    } catch (Exception $e) {
                                        $error = $e->getMessage();
                                    }
                                } else
                                    $error = $language->get('general', 'invalid_token');
                            }

                            if (is_null($active_pages))
                                $active_pages = array();
                            ?>
                            <h4 style="display:inline;"><?php echo str_replace('{x}', Output::getClean($widget->name), $language->get('admin', 'editing_widget_x')); ?></h4>
                            <span class="pull-right"><?php if ($widgets->getWidget($widget->name)->getSettings() != null) echo '<a class="btn btn-info" href="' . URL::build('/admin/widgets/', 'action=settings&amp;w=' . $widget->id) . '">' . $language->get('admin', 'settings') . '</a> '; ?>
                                <a class="btn btn-warning"
                                   href="<?php echo URL::build('/admin/widgets'); ?>"><?php echo $language->get('general', 'back'); ?></a></span>
                            <br/><br/>
                            <?php if (isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
                            <form action="" method="post">
                                <?php
                                $order = $widgets->getWidget($widget->name)->getOrder();
                                if(!$order) $order = 10;

                                $possible_pages = $pages->returnWidgetPages();
                                if (count($possible_pages)) {
                                    foreach ($possible_pages as $module => $module_pages) {
                                        if (count($module_pages)) {
                                            ?>
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th><?php echo Output::getClean($module); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($module_pages as $page => $value) { ?>
                                                        <tr>
                                                            <td>
                                                                <label for="<?php echo Output::getClean($page); ?>"><?php echo Output::getClean(ucfirst($page)); ?></label>
                                                                <span class="pull-right">
                                                                    <input class="js-switch" type="checkbox" name="pages[]"
                                                                           id="<?php echo Output::getClean($page); ?>"
                                                                           value="<?php echo Output::getClean($page); ?>"<?php if (in_array($page, $active_pages)) echo ' checked'; ?>>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php
                                        }
                                    }
                                }
                                ?>
								<div class="form-group">
									<label for="inputOrder"><?php echo $language->get('admin', 'widget_order'); ?></label>
									<input id="inputOrder" name="order" type="number" class="form-control" value="<?php echo Output::getClean($order); ?>">
								</div>
                                <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                                <input type="submit" class="btn btn-primary"
                                       value="<?php echo $language->get('general', 'submit'); ?>">
                            </form>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require(ROOT_PATH . '/modules/Core/pages/admin/footer.php'); ?>

<?php require(ROOT_PATH . '/modules/Core/pages/admin/scripts.php'); ?>
<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.js"></script>
<script>
var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
elems.forEach(function(html) {
    var switchery = new Switchery(html);
});
</script>
</body>
</html>