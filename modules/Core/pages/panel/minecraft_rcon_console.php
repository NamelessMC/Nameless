<?php

$page_title = $language->get('admin', 'rcon');
if ($user->isLoggedIn()) {
  if (!$user->canViewStaffCP()) {
    Redirect::to(URL::build('/'));
    die();
  }
  if (!$user->isAdmLoggedIn()) {
    Redirect::to(URL::build('/panel/auth'));
    die();
  }
} else {
  // Not logged in
  Redirect::to(URL::build('/login'));
  die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'integrations');
define('PANEL_PAGE', 'rcon');
define('MINECRAFT_PAGE', 'rcon');

require_once(ROOT_PATH . '/core/templates/backend_init.php');

if (isset($_GET['id'])) {
  if (is_numeric($_GET['id'])) {
    $server_id = $_GET['id'];
  } else {
    require_once(ROOT_PATH . '/403.php');
    die();
  }
}

$smarty->assign(array(
  'IS_ADMIN' => false
));
if ($user->hasPermission('admincp.minecraft.rcon.all')) {
  $smarty->assign(array(
    'IS_ADMIN' => true
  ));
} else {
  if (!$user->hasPermission('admincp.minecraft.rcon.' . $server_id)) {
    require_once(ROOT_PATH . '/403.php');
    die();
  }
}

$server = end($queries->getWhere('mc_servers', array('id', '=', $server_id)));

if (empty($server) or $server->rcon_status != 1) {
  require_once(ROOT_PATH . '/403.php');
  die();
}

if(isset($_POST['cmd_mc'])) {

  if (Token::check($_POST['token'])) {

    $response = array();
    $rcon = new Rcon($server->ip, $server->rcon_port, $server->rcon_pass, 3);

    $command = $_POST['cmd_mc'];

    if (empty($command)) {
      $response['status'] = 'error';
      $response['error_label'] = 'Error';
      $response['error_status'] = 'Error. Empty command';
    } else {
      if ($rcon->connect()) {
        $rcon->send_command($command);
        $response['status'] = 'success';
        $response['command'] = $_POST['cmd_mc'];
        $response['response'] = $rcon->get_response();
        $response['success_status'] = 'Success';
      } else {
        $response['status'] = 'error';
        $response['error_label'] = 'Error';
        $response['error_status'] = 'Error connect to server';
      }
    }
  }
  echo json_encode($response);
  exit;
}

$smarty->assign(array(
  'SERVER' => $server,
));

$template_file = 'integrations/minecraft/minecraft_servers_rcon_console.tpl';

$rcon_js = DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, ['core', 'assets', 'js', 'rcon_console.js']);
$rcon_css = DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, ['core', 'assets', 'css', 'rcon_console.css']);

$template->addJSFiles(array(
  (defined('CONFIG_PATH') ? CONFIG_PATH : '') . $rcon_js => array()
));
$template->addCSSFiles(array(
  (defined('CONFIG_PATH') ? CONFIG_PATH : '') . $rcon_css => array()
));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $staffcp_nav), $widgets, $template);
$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));
$template->onPageLoad();

if (Session::exists('staff_rcon'))
  $success = Session::flash('staff_rcon');

if (isset($success))
  $smarty->assign(array(
    'SUCCESS' => $success,
    'SUCCESS_TITLE' => $language->get('general', 'success')
  ));

if (isset($errors) && count($errors))
  $smarty->assign(array(
    'ERRORS' => $errors,
    'ERRORS_TITLE' => $language->get('general', 'error')
  ));

  $smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'BACK' => $language->get('general', 'back'),
    'BACK_LINK' => URL::build('/panel/minecraft/rcon'),
    'PERMISSION_LABEL' => $language->get('admin', 'permissions'),
    'PERMISSION_LINK' => URL::build('/panel/minecraft/rcon/permissions', 'id=' . $server->id),
));

require(ROOT_PATH . '/core/templates/panel_navbar.php');

$template->displayTemplate($template_file, $smarty);