<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Donation addon page
$page = $donate_language['donate_icon'] . $donate_language['donate']; // for navbar

// Ensure the addon is enabled
if(!in_array('Donate', $enabled_addon_pages)){
    // Not enabled, redirect to homepage
    echo '<script data-cfasync="false">window.location.replace(\'/\');</script>';
    die();
}

// HTMLPurifier
require('core/includes/htmlpurifier/HTMLPurifier.standalone.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Donation page for the <?php echo $sitename; ?> community">
    <meta name="author" content="<?php echo $sitename; ?>">
    <meta name="theme-color" content="#454545" />
    <?php if(isset($custom_meta)){ echo $custom_meta; } ?>

    <?php
    // Generate header and navbar content
    // Page title
    $title = $donate_language['donate'];

    require('core/includes/template/generate.php');
    ?>

    <!-- Custom style -->
    <style>
        html {
            overflow-y: scroll;
        }
    </style>

</head>
<body>
<?php
// Load navbar
$smarty->display('styles/templates/' . $template . '/navbar.tpl');

// Get all donation settings
try {
    $settings = $queries->getWhere('donation_settings', array('id', '<>', 0));
} catch(Exception $e){
    die($e->getMessage());
}

// Can guests view?
if($settings[2]->value == 0 && !$user->isLoggedIn()){
    // User needs to log in
    echo '<script data-cfasync="false">window.location.replace(\'/signin\');</script>';
    die();

} else {
    // Check to see if the integrated store is enabled or not
    if($settings[3]->value == 1){
        // Integrated
        // Generate content
        // Latest donors
        $latest_donors = $queries->orderAll('donation_cache', 'time', 'DESC LIMIT 5');

        // Get store currency
        // TODO: get currency from store
        $currency = $queries->getWhere('donation_settings', array('name', '=', 'currency'));
        if($currency[0]->value == "0"){
            $currency = '$';
        } else if($currency[0]->value == "1"){
            $currency = '£';
        } else if($currency[0]->value == "2"){
            $currency = '€';
        } else if($currency[0]->value == "3"){
            $currency = 'R$';
        }

        $latest_donors_string = '';
        foreach($latest_donors as $latest_donor){
            // Append to string to display in template
            $latest_donors_string .= '<p><div class="row vertical-align"><div class="col-md-3"><a href="/profile/' . htmlspecialchars($latest_donor->ign) . '"><img class="img-rounded" src="https://cravatar.eu/avatar/' . ((strlen($latest_donor->uuid) > 0 && $latest_donor->uuid != '----') ? htmlspecialchars($latest_donor->uuid) : htmlspecialchars($latest_donor->ign)) . '/30.png" /></a></div>';
            $latest_donors_string .= '<div class="col-md-9"><a href="/profile/' . htmlspecialchars($latest_donor->ign) . '">' . htmlspecialchars($latest_donor->ign) . '</a> - ' . $currency . $latest_donor->price . '<br />' . date('d M Y', $latest_donor->time) . '</div></div></p>';
        }

        $smarty->assign('LATEST_DONORS', $donate_language['latest_donors']);
        $smarty->assign('LATEST_DONORS_LIST', $latest_donors_string);

        // Display categories
        $categories = $queries->orderAll('donation_categories', '`order`', 'ASC');

        $categories_list = '<ul class="nav nav-tabs">';
        $categories_content = '<div class="tab-content">';
        $package_modals = '';
        $n = 0;
        foreach($categories as $category){
            $categories_list .= '<li';
            if($n == 0){
                $categories_list .= ' class="active"';
            }
            $categories_list .= '><a href="#' . $category->id . '" data-toggle="tab">' . htmlspecialchars($category->name) . '</a></li>';

            // Get packages in that category
            $packages = $queries->getWhere('donation_packages', array('category', '=', $category->cid));

            $categories_content .= '<div id="' . $category->id . '" class="tab-pane';
            if($n == 0){
                $categories_content .= ' active';
            }
            $categories_content .= '">';

            if(count($packages) > 4){
                // How many packages on the second row?
                $second_row = count($packages) - 4;
                if($second_row == 1){
                    // one central package
                    $col = '12';
                } else if($second_row == 2){
                    // two central packages
                    $col = '6';
                } else if($second_row == 3){
                    // three wider packages
                    $col = '4';
                } else if($second_row == 4){
                    // four packages
                    $col = '3';
                }
            } else {
                // How many packages on the top row?
                $top_row = count($packages);
                if($top_row == 1){
                    // one central package
                    $col = '12';
                } else if($top_row == 2){
                    // two central packages
                    $col = '6';
                } else if($top_row == 3){
                    // three wider packages
                    $col = '4';
                } else if($top_row == 4){
                    // four packages
                    $col = '3';
                }
            }

            // HTMLPurifier
            $config = HTMLPurifier_Config::createDefault();
            $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
            $config->set('URI.DisableExternalResources', false);
            $config->set('URI.DisableResources', false);
            $config->set('HTML.Allowed', 'u,a,p,b,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
            $config->set('CSS.AllowedProperties', array('float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
            $config->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style');
            $config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
            $purifier = new HTMLPurifier($config);

            if(isset($top_row)){
                // One row only
                $categories_content .= '<div class="row">';
                foreach($packages as $package){
                    $categories_content .= '<div class="col-md-' . $col . '">
												  <div class="panel panel-primary">
													<div class="panel-heading">
													  ' . htmlspecialchars($package->name) . '
													  <span class="pull-right">
														' . (($package->cost > 0) ? $currency . htmlspecialchars($package->cost) : '') . '
													  </span>
													</div>
													<div class="panel-body">
													  <div class="forum_post">' . $purifier->purify(htmlspecialchars_decode($package->description)) . '</div>
													  <center>
														<a data-toggle="modal" href="#package' . $package->id . '" class="btn btn-primary">' . $donate_language['select'] . '</a>
													  </center>
													</div>
												  </div>
												</div>';
                }
                $categories_content .= '</div>';
            } else if(isset($second_row)){
                // Two rows
                $categories_content .= '<div class="row">';
                $i = 0;
                while($i < 4){
                    $categories_content .= '<div class="col-md-3">
												  <div class="panel panel-primary">
													<div class="panel-heading">
													  ' . htmlspecialchars($packages[$i]->name) . '
													  <span class="pull-right">
														' . $currency . htmlspecialchars($packages[$i]->cost) . '
													  </span>
													</div>
													<div class="panel-body">
													  <div class="forum_post">' . $purifier->purify(htmlspecialchars_decode($packages[$i]->description)) . '</div>
													  <center>
														<a data-toggle="modal" href="#package' . $packages[$i]->id . '" class="btn btn-primary">' . $donate_language['select'] . '</a>
													  </center>
													</div>
												  </div>
												</div>';
                    $i++;
                }
                $categories_content .= '</div><br />
					<div class="row">';
                $i = 0;
                $n = 4;
                while($i < $second_row){
                    $categories_content .= '<div class="col-md-' . $col . '">
												  <div class="panel panel-primary">
													<div class="panel-heading">
													  ' . htmlspecialchars($packages[$n]->name) . '
													  <span class="pull-right">
														' . $currency . htmlspecialchars($packages[$n]->cost) . '
													  </span>
													</div>
													<div class="panel-body">
													  <div class="forum_post">' . $purifier->purify(htmlspecialchars_decode($packages[$n]->description)) . '</div>
													  <center>
														<a data-toggle="modal" href="#package' . $packages[$n]->id . '" class="btn btn-primary">' . $donate_language['select'] . '</a>
													  </center>
													</div>
												  </div>
												</div>';
                    $i++;
                    $n++;
                }
                $categories_content .= '</div>';
            }

            $categories_content .= '</div>';

            // Package modals
            $store_type = $queries->getWhere('donation_settings', array('name', '=', 'store_type'));
            $store_type = $store_type[0]->value;

            if($store_type == 'bc'){
                $buycraft_url = $queries->getWhere('donation_settings', array('name', '=', 'store_url'));
                $buycraft_url = $buycraft_url[0]->value;

                foreach($packages as $package){
                    $package_modals .= '<div class="modal fade" id="package' . $package->id . '" tabindex="-1" role="dialog" aria-hidden="true">
											  <div class="modal-dialog">
												<div class="modal-content">
												  <div class="modal-header">
													<h4 class="modal-title">' . htmlspecialchars($package->name) . '<span class="pull-right">' . $currency . htmlspecialchars($package->cost) . '</span></h4>
												  </div>
												  <div class="modal-body">
												  ' . $donate_language['agree_with_terms'] . '
												  </div>
												  <div class="modal-footer">
													<form method="get" action="' . htmlspecialchars($buycraft_url) . '/checkout/packages">
													  <input type="hidden" name="direct" value="true">
													  <input type="text" class="form-control" placeholder="' . $user_language['minecraft_username'] . '" name="ign" value="" required autofocus>
													  <input type="hidden" name="action" value="add">
													  <input type="hidden" name="package" value="' . $package->package_id . '"><br />
													  <button type="button" class="btn btn-default" data-dismiss="modal">' . $donate_language['cancel'] . '</button>
													  <button type="submit" class="btn btn-primary btn-large">' . $donate_language['agree'] . '</button>
													</form>
												  </div>
												</div>
											  </div>
											</div>';
                }
            } else {
                // get store URL for MCStock
                $store_url = $queries->getWhere('donation_settings', array('name', '=', 'store_url'));
                $store_url = $store_url[0]->value;

                foreach($packages as $package){
                    $package_modals .= '<div class="modal fade" id="package' . $package->id . '" tabindex="-1" role="dialog" aria-hidden="true">
											  <div class="modal-dialog">
												<div class="modal-content">
												  <div class="modal-header">
													<h4 class="modal-title">' . htmlspecialchars($package->name) . '<span class="pull-right">' . $currency . htmlspecialchars($package->cost) . '</span></h4>
												  </div>
												  <div class="modal-body">
												  ' . $donate_language['agree_with_terms'] . '
												  </div>
												  <div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">' . $donate_language['cancel'] . '</button>';
                    if($store_type == 'mm'){
                        $package_modals .= '<a href="' . htmlspecialchars($package->url) . '" class="btn btn-primary btn-large">' . $donate_language['agree'] . '</a>';
                    } else if($store_type == 'mcs'){
                        $package_modals .= '<a href="' . rtrim(htmlspecialchars($store_url), '/') . '/cart/add/' . $package->package_id . '" class="btn btn-primary btn-large">' . $donate_language['agree'] . '</a>';
                    } else if($store_type == 'cs'){
                        $package_modals .= '<a href="' . htmlspecialchars($package->url) . '" class="btn btn-primary btn-large">' . $donate_language['agree'] . '</a>';
                    }
                    $package_modals .= '</form>
												  </div>
												</div>
											  </div>
											</div>';
                }
            }

            $n++;
        }
        $categories_list .= '</ul>';
        $categories_content .= '</div>';

        $smarty->assign('CATEGORIES_LIST', $categories_list);
        $smarty->assign('CATEGORIES_CONTENT', $categories_content);

        // Display template
        $smarty->display('addons/Donate/template/donate.tpl');

        // Display package modals
        echo $package_modals;

    } else {
        // External store
        echo '<iframe src="' . htmlspecialchars($settings[4]->value) . '" width="100%" height="900px"></iframe>';
    }
}

// Footer
require('core/includes/template/footer.php');
$smarty->display('styles/templates/' . $template . '/footer.tpl');

// Scripts
require('core/includes/template/scripts.php');
?>
</body>
</html>
