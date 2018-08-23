<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  License: MIT
 *
 *  Admin custom pages page
 */

// Can the user view the AdminCP?
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
        } else {
            if(!$user->hasPermission('admincp.pages')){
            	if($user->hasPermission('admincp.pages.metadata')){
            		Redirect::to(URL::build('/admin/metadata'));
            		die();
				} else {
            		require(ROOT_PATH . '/404.php');
            		die();
				}
            }
        }
    }
} else {
    // Not logged in
    Redirect::to(URL::build('/login'));
    die();
}

// Set page name for sidebar
$page = 'admin';
$admin_page = 'pages';
?>
<!DOCTYPE html>
<html lang="<?php echo(defined('HTML_LANG') ? HTML_LANG : 'en'); ?>" <?php if(defined('HTML_RTL') && HTML_RTL === true) echo ' dir="rtl"'; ?>>
<head>
  <!-- Standard Meta -->
  <meta charset="<?php echo (defined('LANG_CHARSET') ? LANG_CHARSET : 'utf-8'); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  <meta name="robots" content="noindex">

    <?php
    $title = $language->get('admin', 'admin_cp');
    require(ROOT_PATH . '/core/templates/admin_header.php');
    ?>

  <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.css">
  <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">

</head>

<body>
<?php
require(ROOT_PATH . '/modules/Core/pages/admin/navbar.php');
?>
<div class="container">
  <div class="row">
    <div class="col-md-3">
        <?php require(ROOT_PATH . '/modules/Core/pages/admin/sidebar.php'); ?>
    </div>
    <div class="col-md-9">
      <div class="card">
        <div class="card-block">
          <?php if($user->hasPermission('admincp.pages.metadata')){ ?>
          <ul class="nav nav-pills">
            <li class="nav-item">
              <a class="nav-link active" href="<?php echo URL::build('/admin/pages'); ?>"><?php echo $language->get('admin', 'custom_pages'); ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URL::build('/admin/metadata'); ?>"><?php echo $language->get('admin', 'page_metadata'); ?></a>
            </li>
          </ul>
          <hr />
          <?php } ?>
          <h3 style="display:inline;"><?php echo $language->get('admin', 'custom_pages'); ?></h3>
          <?php if(!isset($_GET['action'])){ ?>
            <span class="pull-right">
              <a href="<?php echo URL::build('/admin/pages/', 'action=new'); ?>" class="btn btn-primary"><?php echo $language->get('admin', 'new_page'); ?></a>
            </span>
            <hr />
            <?php
            $custom_pages = $queries->getWhere('custom_pages', array('id', '<>', 0));
            if(count($custom_pages)){
              $i = 0;
              $total = count($custom_pages);
              foreach($custom_pages as $custom_page){
                echo '<a href="' . URL::build('/admin/pages/', 'action=edit&amp;id=' . $custom_page->id) . '">' . Output::getClean($custom_page->title) . '</a>';
                echo '<span class="pull-right"><a href="' . URL::build('/admin/pages/', 'action=edit&amp;id=' . $custom_page->id) . '" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a href="' . URL::build('/admin/pages/', 'action=delete&amp;id=' . $custom_page->id) . '" class="btn btn-danger btn-sm" onclick="return confirm(\'' . $language->get('admin', 'confirm_delete_page') . '\');"><i class="fa fa-trash" aria-hidden="true"></i></a></span>';

                if($i < ($total - 1))
                  echo '<hr />';

                $i++;
              }
            } else {
              echo '<div class="alert alert-info">' . $language->get('admin', 'no_custom_pages') . '</div>';
            }
            ?>
          <?php
          } else {
            if($_GET['action'] == 'new'){
              if(Input::exists()){
                if(Token::check(Input::get('token'))){
                  $validate = new Validate();
                  $validation = $validate->check($_POST, array(
                      'page_title' => array(
                          'required' => true,
                          'min' => 2,
                          'max' => 30
                      ),
                      /*
                      'page_icon' => array(
                          'max' => 64
                      ),
                      */
                      'page_url' => array(
                          'required' => true,
                          'min' => 2,
                          'max' => 20
                      ),
                      'content' => array(
                          'max' => 100000
                      ),
                      'link_location' => array(
                          'required' => true
                      ),
                      'redirect_link' => array(
                          'max' => 512
                      )
                  ));

                  if($validation->passed()){
                    try {
                      // Get link location
                      if(isset($_POST['link_location'])){
                        switch($_POST['link_location']){
                            case 1:
                            case 2:
                            case 3:
                            case 4:
                              $location = $_POST['link_location'];
                              break;
                            default:
                              $location = 1;
                        }
                      } else
                        $location = 1;

                      if(isset($_POST['redirect_page']) && $_POST['redirect_page'] == 'on') $redirect = 1;
                      else $redirect = 0;

                      if(isset($_POST['redirect_link'])) $link = $_POST['redirect_link'];
                      else $link = '';

                      if(isset($_POST['unsafe_html']) && $_POST['unsafe_html'] == 'on') $unsafe = 1;
                      else $unsafe = 0;

                      if(isset($_POST['sitemap']) && $_POST['sitemap'] == 'on') $sitemap = 1;
                      else $sitemap = 0;

                      $queries->create('custom_pages', array(
                          'url' => Output::getClean(rtrim(Input::get('page_url'), '/')),
                          'title' => Output::getClean(Input::get('page_title')),
                          'content' => Output::getClean(Input::get('content')),
                          'link_location' => $location,
                          'redirect' => $redirect,
                          'link' => Output::getClean($link),
                          'target' => ($redirect == 1) ? 1 : 0,
                          //'icon' => Input::get('page_icon'),
                          'all_html' => ($unsafe == 1) ? 1 : 0,
                          'sitemap' => ($sitemap == 1) ? 1 : 0
                      ));

                      $last_id = $queries->getLastId();

                      Log::getInstance()->log(Log::Action('admin/pages/new'), Output::getClean(Input::get('page_title')));
                      
                      // Permissions
                      $perms = array();  
                      if(isset($_POST['perm-view-0']) && $_POST['perm-view-0'] == 1)
                        $perms[0] = 1;
                      else
                        $perms[0] = 0;
                      
                      $groups = $queries->getWhere('groups', array('id', '<>', 0));
                      foreach($groups as $group){
                        if(isset($_POST['perm-view-' . $group->id]) && $_POST['perm-view-' . $group->id] == 1)
                          $perms[$group->id] = 1;
                        else
                          $perms[$group->id] = 0;
                      }

                      foreach($perms as $key => $perm){
                        $queries->create('custom_pages_permissions', array(
                            'page_id' => $last_id,
                            'group_id' => $key,
                            'view' => $perm
                        ));
                      }

                      Redirect::to(URL::build('/admin/pages'));
                      die();

                    } catch(Exception $e){
                      $error = $e->getMessage();
                    }
                  } else {
                    $error = $language->get('admin', 'unable_to_create_page') . '<ul>';
                    foreach($validation->errors() as $item){
                      $error .= '<li>';
                      if(strpos($item, 'is required') !== false){
                        if(strpos($item, 'page_title') !== false)
                          $error .= $language->get('admin', 'page_title_required');
                        else if(strpos($item, 'page_url') !== false)
                          $error .= $language->get('admin', 'page_url_required');
                        else if(strpos($item, 'link_location') !== false)
                          $error .= $language->get('admin', 'link_location_required');
                      } else if(strpos($item, 'minimum') !== false){
                        if(strpos($item, 'page_title') !== false)
                          $error .= $language->get('admin', 'page_title_minimum_2');
                        else if(strpos($item, 'page_url') !== false)
                          $error .= $language->get('admin', 'page_url_minimum_2');
                      } else if(strpos($item, 'maximum') !== false){
                        if(strpos($item, 'page_title') !== false)
                          $error .= $language->get('admin', 'page_title_maximum_30');
                        else if(strpos($item, 'page_icon') !== false)
                          $error .= $language->get('admin', 'page_icon_maximum_64');
                        else if(strpos($item, 'page_url') !== false)
                          $error .= $language->get('admin', 'page_url_maximum_20');
                        else if(strpos($item, 'content') !== false)
                          $error .= $language->get('admin', 'page_content_maximum_100000');
                        else if(strpos($item, 'redirect_link') !== false)
                          $error .= $language->get('admin', 'page_redirect_link_maximum_512');
                      }
                      $error .= '</li>';
                    }
                    $error .= '</ul>';
                  }
                } else
                  $error = $language->get('general', 'invalid_token');
              }
            ?>
            <span class="pull-right">
              <a href="<?php echo URL::build('/admin/pages/'); ?>" class="btn btn-warning" onclick="return confirm('<?php echo $language->get('general', 'confirm_cancel'); ?>');"><?php echo $language->get('general', 'cancel'); ?></a>
            </span>
            <hr />
            <h4><?php echo $language->get('admin', 'creating_new_page'); ?></h4>
            <?php if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
            <form action="" method="post">
              <div class="form-group">
                <label for="inputTitle"><?php echo $language->get('admin', 'page_title'); ?></label>
                <input type="text" class="form-control" name="page_title" id="inputTitle" placeholder="<?php echo $language->get('admin', 'page_title'); ?>" value="<?php echo Input::get('page_title'); ?>">
              </div>
              <!--
              <div class="form-group">
                <label for="inputIcon"><?php echo $language->get('admin', 'page_icon'); ?></label>
                <input type="text" class="form-control" name="page_icon" id="inputIcon" placeholder="<?php echo $language->get('admin', 'page_icon'); ?>" value="<?php echo Output::getClean(Input::get('page_icon')); ?>">
              </div>
              -->
              <div class="form-group">
                <label for="inputURL"><?php echo $language->get('admin', 'page_path'); ?></label>
                <input type="text" class="form-control" name="page_url" id="inputURL" placeholder="<?php echo $language->get('admin', 'page_path'); ?>" value="<?php echo Input::get('page_url'); ?>">
              </div>
              <div class="form-group">
                <label for="link_location"><?php echo $language->get('admin', 'page_link_location'); ?></label>
                <select class="form-control" id="link_location" name="link_location">
                  <option value="1"><?php echo $language->get('admin', 'page_link_navbar'); ?></option>
                  <option value="2"><?php echo $language->get('admin', 'page_link_more'); ?></option>
                  <option value="3"><?php echo $language->get('admin', 'page_link_footer'); ?></option>
                  <option value="4"><?php echo $language->get('admin', 'page_link_none'); ?></option>
                </select>
              </div>
              <div class="form-group">
                <label for="inputContent"><?php echo $language->get('admin', 'page_content'); ?></label>
                <textarea name="content" id="inputContent"></textarea>
              </div>
              <div class="form-group">
                <label for="inputRedirect"><?php echo $language->get('admin', 'page_redirect'); ?></label>
                <input id="inputRedirect" name="redirect_page" type="checkbox" class="js-switch" />
              </div>
              <div class="form-group">
                <label for="inputRedirectLink"><?php echo $language->get('admin', 'page_redirect_to'); ?></label>
                <input type="text" class="form-control" id="inputRedirectLink" name="redirect_link" value="<?php echo Input::get('redirect_link'); ?>">
              </div>
              <div class="form-group">
                <label for="inputUnsafeHTML"><?php echo $language->get('admin', 'unsafe_html'); ?></label> <span data-toggle="popover" data-content="<?php echo $language->get('admin', 'unsafe_html_warning'); ?>" class="badge badge-info"><i class="fa fa-question"></i></span>
                <input id="inputUnsafeHTML" name="unsafe_html" type="checkbox" class="js-switch" />
              </div>
              <div class="form-group">
                <label for="inputSitemap"><?php echo $language->get('admin', 'include_in_sitemap'); ?></label>
                <input id="inputSitemap" name="sitemap" type="checkbox" class="js-switch" />
              </div>
              <hr />
              <h5><?php echo $language->get('admin', 'page_permissions'); ?></h5>
              <hr />
              <script>
                  var groups = [];
                  groups.push("0");
              </script>
              <table class="table table-responsive table-striped">
                <thead>
                <tr>
                  <th><?php echo $language->get('admin', 'group'); ?></th>
                  <th><?php echo $language->get('admin', 'view_page'); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td onclick="toggleAll(this);"><?php echo $language->get('user', 'guests'); ?></td>
                  <td><input type="hidden" name="perm-view-0" value="0" /><input onclick="colourUpdate(this);" name="perm-view-0" id="Input-view-0" value="1" type="checkbox"></td>
                </tr>
                <?php
                $groups = $queries->getWhere('groups', array('id', '<>', 0));
                foreach($groups as $group){
                    ?>
                  <tr>
                    <td onclick="toggleAll(this);"><?php echo htmlspecialchars($group->name); ?></td>
                    <td><input type="hidden" name="perm-view-<?php echo $group->id; ?>" value="0" /> <input onclick="colourUpdate(this);" name="perm-view-<?php echo $group->id; ?>" id="Input-view-<?php echo $group->id; ?>" value="1" type="checkbox"></td>
                  </tr>
                  <script>groups.push("<?php echo $group->id; ?>");</script>
                    <?php
                }
                ?>
                </tbody>
              </table>
              <div class="form-group">
                <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                <input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
              </div>
            </form>
            <?php
            } else if($_GET['action'] == 'edit'){
              if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
                Redirect::to(URL::build('/admin/pages/'));
                die();
              }
              $page = $queries->getWhere('custom_pages', array('id', '=', $_GET['id']));
              if(!count($page)){
                Redirect::to(URL::build('/admin/pages/'));
                die();
              }
              $page = $page[0];

              // Handle input
              if(Input::exists()){
                if(Token::check(Input::get('token'))){
                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
                        'page_title' => array(
                            'required' => true,
                            'min' => 2,
                            'max' => 30
                        ),
                        'page_icon' => array(
                            'max' => 64
                        ),
                        'page_url' => array(
                            'required' => true,
                            'min' => 2,
                            'max' => 20
                        ),
                        'content' => array(
                            'max' => 100000
                        ),
                        'link_location' => array(
                            'required' => true
                        ),
                        'redirect_link' => array(
                            'max' => 512
                        )
                    ));

                    if($validation->passed()){
                        try {
                            // Get link location
                            if(isset($_POST['link_location'])){
                                switch($_POST['link_location']){
                                    case 1:
                                    case 2:
                                    case 3:
                                    case 4:
                                        $location = $_POST['link_location'];
                                        break;
                                    default:
                                        $location = 1;
                                }
                            } else
                                $location = 1;

                            if(isset($_POST['redirect_page']) && $_POST['redirect_page'] == 'on') $redirect = 1;
                            else $redirect = 0;

                            if(isset($_POST['redirect_link'])) $link = $_POST['redirect_link'];
                            else $link = '';

                            if(isset($_POST['unsafe_html']) && $_POST['unsafe_html'] == 'on') $unsafe = 1;
                            else $unsafe = 0;

                            if(isset($_POST['sitemap']) && $_POST['sitemap'] == 'on') $sitemap = 1;
                            else $sitemap = 0;

                            $queries->update('custom_pages', $page->id, array(
                                'url' => Output::getClean(rtrim(Input::get('page_url'), '/')),
                                'title' => Output::getClean(Input::get('page_title')),
                                'content' => Output::getClean(Input::get('content')),
                                'link_location' => $location,
                                'redirect' => $redirect,
                                'link' => Output::getClean($link),
                                'target' => ($redirect == 1) ? 1 : 0,
                                'icon' => Input::get('page_icon'),
                                'all_html' => ($unsafe == 1) ? 1 : 0,
                                'sitemap' => ($sitemap == 1) ? 1 : 0
                            ));
                            
                            Log::getInstance()->log(Log::Action('admin/pages/edit'), Output::getClean(Input::get('page_title')));

                            // Permissions
                            // Guest first
                            $view = Input::get('perm-view-0');

                            if(!($view)) $view = 0;

                            $page_perm_exists = 0;

                            $page_perm_query = $queries->getWhere('custom_pages_permissions', array('page_id', '=', $page->id));
                            if(count($page_perm_query)){
                                foreach($page_perm_query as $query){
                                    if($query->group_id == 0){
                                        $page_perm_exists = 1;
                                        $update_id = $query->id;
                                        break;
                                    }
                                }
                            }

                            try {
                                if($page_perm_exists != 0){ // Permission already exists, update
                                    // Update the category
                                    $queries->update('custom_pages_permissions', $update_id, array(
                                        'view' => $view
                                    ));
                                } else { // Permission doesn't exist, create
                                    $queries->create('custom_pages_permissions', array(
                                        'group_id' => 0,
                                        'page_id' => $page->id,
                                        'view' => $view
                                    ));
                                }

                            } catch(Exception $e) {
                                die($e->getMessage());
                            }

                            // Group category permissions
                            $groups = $queries->getWhere('groups', array('id', '<>', 0));
                            foreach($groups as $group){
                                $view = Input::get('perm-view-' . $group->id);

                                if(!($view)) $view = 0;

                                $page_perm_exists = 0;

                                if(count($page_perm_query)){
                                    foreach($page_perm_query as $query){
                                        if($query->group_id == $group->id){
                                            $page_perm_exists = 1;
                                            $update_id = $query->id;
                                            break;
                                        }
                                    }
                                }

                                try {
                                    if($page_perm_exists != 0){ // Permission already exists, update
                                        // Update the category
                                        $queries->update('custom_pages_permissions', $update_id, array(
                                            'view' => $view
                                        ));
                                    } else { // Permission doesn't exist, create
                                        $queries->create('custom_pages_permissions', array(
                                            'group_id' => $group->id,
                                            'page_id' => $page->id,
                                            'view' => $view
                                        ));
                                    }

                                } catch(Exception $e) {
                                    die($e->getMessage());
                                }
                            }

                            Redirect::to(URL::build('/admin/pages'));
                            die();

                        } catch(Exception $e){
                            $error = $e->getMessage();
                        }
                    } else {
                        $error = $language->get('admin', 'unable_to_create_page') . '<ul>';
                        foreach($validation->errors() as $item){
                            $error .= '<li>';
                            if(strpos($item, 'is required') !== false){
                                if(strpos($item, 'page_title') !== false)
                                    $error .= $language->get('admin', 'page_title_required');
                                else if(strpos($item, 'page_url') !== false)
                                    $error .= $language->get('admin', 'page_url_required');
                                else if(strpos($item, 'link_location') !== false)
                                    $error .= $language->get('admin', 'link_location_required');
                            } else if(strpos($item, 'minimum') !== false){
                                if(strpos($item, 'page_title') !== false)
                                    $error .= $language->get('admin', 'page_title_minimum_2');
                                else if(strpos($item, 'page_url') !== false)
                                    $error .= $language->get('admin', 'page_url_minimum_2');
                            } else if(strpos($item, 'maximum') !== false){
                                if(strpos($item, 'page_title') !== false)
                                    $error .= $language->get('admin', 'page_title_maximum_30');
                                else if(strpos($item, 'page_icon') !== false)
                                    $error .= $language->get('admin', 'page_icon_maximum_64');
                                else if(strpos($item, 'page_url') !== false)
                                    $error .= $language->get('admin', 'page_url_maximum_20');
                                else if(strpos($item, 'content') !== false)
                                    $error .= $language->get('admin', 'page_content_maximum_100000');
                                else if(strpos($item, 'redirect_link') !== false)
                                    $error .= $language->get('admin', 'page_redirect_link_maximum_512');
                            }
                            $error .= '</li>';
                        }
                        $error .= '</ul>';
                    }
                } else
                  $error = $language->get('general', 'invalid_token');
              }
            ?>
            <span class="pull-right">
              <a href="<?php echo URL::build('/admin/pages/'); ?>" class="btn btn-warning" onclick="return confirm('<?php echo $language->get('general', 'confirm_cancel'); ?>');"><?php echo $language->get('general', 'cancel'); ?></a>
            </span>
            <hr />
            <h4><?php echo str_replace('{x}', Output::getClean($page->title), $language->get('admin', 'editing_page_x')); ?></h4>
            <?php if(isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
            <form action="" method="post">
                <div class="form-group">
                  <label for="inputTitle"><?php echo $language->get('admin', 'page_title'); ?></label>
                  <input type="text" class="form-control" name="page_title" id="inputTitle" placeholder="<?php echo $language->get('admin', 'page_title'); ?>" value="<?php echo Output::getClean($page->title); ?>">
                </div>
                <div class="form-group">
                  <label for="inputIcon"><?php echo $language->get('admin', 'page_icon'); ?></label>
                  <input type="text" class="form-control" name="page_icon" id="inputIcon" placeholder="<?php echo $language->get('admin', 'page_icon'); ?>" value="<?php echo Output::getClean(htmlspecialchars_decode($page->icon)); ?>">
                </div>
                <div class="form-group">
                  <label for="inputURL"><?php echo $language->get('admin', 'page_path'); ?></label>
                  <input type="text" class="form-control" name="page_url" id="inputURL" placeholder="<?php echo $language->get('admin', 'page_path'); ?>" value="<?php echo Output::getClean($page->url); ?>">
                </div>
                <div class="form-group">
                  <label for="link_location"><?php echo $language->get('admin', 'page_link_location'); ?></label>
                  <select class="form-control" id="link_location" name="link_location">
                    <option value="1"<?php if($page->link_location == 1) echo ' selected'; ?>><?php echo $language->get('admin', 'page_link_navbar'); ?></option>
                    <option value="2"<?php if($page->link_location == 2) echo ' selected'; ?>><?php echo $language->get('admin', 'page_link_more'); ?></option>
                    <option value="3"<?php if($page->link_location == 3) echo ' selected'; ?>><?php echo $language->get('admin', 'page_link_footer'); ?></option>
                    <option value="4"<?php if($page->link_location == 4) echo ' selected'; ?>><?php echo $language->get('admin', 'page_link_none'); ?></option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="inputContent"><?php echo $language->get('admin', 'page_content'); ?></label>
                  <textarea name="content" id="inputContent"><?php echo (($page->all_html == 0) ? Output::getPurified(htmlspecialchars_decode($page->content)) : htmlspecialchars_decode($page->content)); ?></textarea>
                </div>
                <div class="form-group">
                  <label for="inputRedirect"><?php echo $language->get('admin', 'page_redirect'); ?></label>
                  <input id="inputRedirect" name="redirect_page" type="checkbox" class="js-switch" <?php if($page->redirect == 1) echo 'checked '; ?>/>
                </div>
                <div class="form-group">
                  <label for="inputRedirectLink"><?php echo $language->get('admin', 'page_redirect_to'); ?></label>
                  <input type="text" class="form-control" id="inputRedirectLink" name="redirect_link" value="<?php echo Output::getClean($page->link); ?>">
                </div>
                <div class="form-group">
                  <label for="inputUnsafeHTML"><?php echo $language->get('admin', 'unsafe_html'); ?></label> <span data-toggle="popover" data-content="<?php echo $language->get('admin', 'unsafe_html_warning'); ?>" class="badge badge-info"><i class="fa fa-question"></i></span>
                  <input id="inputUnsafeHTML" name="unsafe_html" type="checkbox" class="js-switch" <?php if($page->all_html == 1) echo 'checked '; ?>/>
                </div>
                <div class="form-group">
                  <label for="inputSitemap"><?php echo $language->get('admin', 'include_in_sitemap'); ?></label>
                  <input id="inputSitemap" name="sitemap" type="checkbox" class="js-switch" <?php if($page->sitemap == 1) echo 'checked '; ?>/>
                </div>
                <hr />
                <h5><?php echo $language->get('admin', 'page_permissions'); ?></h5>
                <hr />
                <script>
                    var groups = [];
                    groups.push("0");
                </script>
                <table class="table table-responsive table-striped">
                  <thead>
                  <tr>
                    <th><?php echo $language->get('admin', 'group'); ?></th>
                    <th><?php echo $language->get('admin', 'view_page'); ?></th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                      <?php
                      // Can guests view the page?
                      $group_perms = $queries->getWhere('custom_pages_permissions', array('page_id', '=', $page->id));
                      foreach($group_perms as $perm){
                        if($perm->group_id == 0){
                          $view = $perm->view;
                          break;
                        }
                      }
                      ?>
                    <td onclick="toggleAll(this);"><?php echo $language->get('user', 'guests'); ?></td>
                    <td><input type="hidden" name="perm-view-0" value="0" /><input onclick="colourUpdate(this);" name="perm-view-0" id="Input-view-0" value="1" type="checkbox"<?php if(isset($view) && $view == 1){ echo ' checked'; } ?>></td>
                  </tr>
                  <?php
                  $groups = $queries->getWhere('groups', array('id', '<>', 0));
                  foreach($groups as $group){
                      // Get the existing group permissions
                      $view = 0;

                      foreach($group_perms as $group_perm){
                          if($group_perm->group_id == $group->id){
                              $view = $group_perm->view;
                              break;
                          }
                      }
                      ?>
                    <tr>
                      <td onclick="toggleAll(this);"><?php echo htmlspecialchars($group->name); ?></td>
                      <td><input type="hidden" name="perm-view-<?php echo $group->id; ?>" value="0" /> <input onclick="colourUpdate(this);" name="perm-view-<?php echo $group->id; ?>" id="Input-view-<?php echo $group->id; ?>" value="1" type="checkbox"<?php if(isset($view) && $view == 1){ echo ' checked'; } ?>></td>
                    </tr>
                    <script>groups.push("<?php echo $group->id; ?>");</script>
                      <?php
                  }
                  ?>
                  </tbody>
                </table>
                <div class="form-group">
                  <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
                  <input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
                </div>
            </form>
            <?php
            } else if($_GET['action'] == 'delete'){
              if(isset($_GET['id']) && is_numeric($_GET['id'])){
                try {
                  $queries->delete('custom_pages', array('id', '=', $_GET['id']));
                  Log::getInstance()->log(Log::Action('admin/pages/delete'));
                } catch(Exception $e){
                  die($e->getMessage());
                }

                Redirect::to(URL::build('/admin/pages'));
                die();
              }
            } else {
              Redirect::to(URL::build('/admin/pages'));
              die();
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

<script src="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/switchery/switchery.min.js"></script>

<script>
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function (html) {
        var switchery = new Switchery(html);
    });
</script>
<?php if(isset($_GET['action']) && ($_GET['action'] == 'new' || $_GET['action'] = 'edit')){ ?>
  <script src="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
  <script src="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js"></script>
  <script src="<?php if (defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/ckeditor.js"></script>
  <script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/emojione/dialogs/emojione.json"></script>
  <script type="text/javascript">
      <?php
      echo Input::createEditor('inputContent', true);
      ?>
  </script>
  <script type="text/javascript">
      function colourUpdate(that) {
          var x = that.parentElement;
          if(that.checked) {
              x.className = "bg-success";
          } else {
              x.className = "bg-danger";
          }
      }
      function toggle(group) {
          if(document.getElementById('Input-view-' + group).checked) {
              document.getElementById('Input-view-' + group).checked = false;
          } else {
              document.getElementById('Input-view-' + group).checked = true;
          }
          colourUpdate(document.getElementById('Input-view-' + group));
      }
      for(var g in groups) {
          colourUpdate(document.getElementById('Input-view-' + groups[g]));
      }

      // Toggle all columns in row
      function toggleAll(that){
          var first = (($(that).parents('tr').find(':checkbox').first().is(':checked') == true) ? false : true);
          $(that).parents('tr').find(':checkbox').each(function(){
              $(this).prop('checked', first);
              colourUpdate(this);
          });
      }

      $(document).ready(function(){
          $('td').click(function() {
              let checkbox = $(this).find('input:checkbox');
              let id = checkbox.attr('id');

              if(checkbox.is(':checked')){
                  checkbox.prop('checked', false);

                  colourUpdate(document.getElementById(id));
              } else {
                  checkbox.prop('checked', true);

                  colourUpdate(document.getElementById(id));
              }
          }).children().click(function(e) {
              e.stopPropagation();
          });
      });
  </script>
<?php } ?>
</body>
</html>
