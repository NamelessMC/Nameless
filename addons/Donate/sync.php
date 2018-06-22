<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Require files
if(!isset($queries)){
    require('../../core/config.php');
    require('../../core/classes/Config.php');
    require('../../core/classes/DB.php');
    require('../../core/classes/Queries.php');
    require('../../core/includes/arrays.php');

    $queries = new Queries();
} else {
    require('core/includes/arrays.php');
}

if(isset($_GET["key"])){
    $key = $queries->getWhere('settings', array('name', '=', 'unique_id'));
    if($_GET["key"] !== $key[0]->value){
        die('Invalid key');
    }
} else {
    die('Invalid key');
}

// Which donor store are we using?
$webstore = $queries->getWhere('donation_settings', array('name', '=', 'store_type'));
$webstore = $webstore[0]->value;

if($webstore == 'bc'){
    // Buycraft
    require('integration/buycraft.php');

    /*
     *  Categories and packages
     */

    $packages = array();
    $categories = array();

    if(count($bc_categories)){
        foreach($bc_categories['categories'] as $item){
            // Does it already exist in the database?
            $category = $queries->getWhere('donation_categories', array('cid', '=', htmlspecialchars($item['id'])));

            if(!count($category)){
                // Input it now
                $queries->create('donation_categories', array(
                    'name' => htmlspecialchars($item['name']),
                    'cid' => $item['id'],
                    '`order`' => $item['order']
                ));
            } else {
                // Update category
                $queries->update('donation_categories', $category[0]->id, array(
                    'name' => htmlspecialchars($item['name']),
                    'cid' => $item['id'],
                    '`order`' => $item['order']
                ));
            }

            // Packages
            if(count($item['packages'])){
                foreach($item['packages'] as $package){
                    // Does it already exist?
                    $package_exists = $queries->getWhere('donation_packages', array('package_id', '=', $package['id']));

                    if(!count($package_exists)){
                        // Input it now
                        $queries->create('donation_packages', array(
                            'name' => htmlspecialchars($package['name']),
                            'description' => 'No description available',
                            'cost' => $package['price'],
                            'package_id' => $package['id'],
                            'active' => 1,
                            'package_order' => $package['order'],
                            'category' => $item['id'],
                            'url' => 'none'
                        ));
                    } else {
                        // Update package
                        // Only update description if no custom one is set
                        if($package_exists[0]->custom_description == 0){
                            $queries->update('donation_packages', $package_exists[0]->id, array(
                                'name' => htmlspecialchars($package['name']),
                                'description' => 'No description available',
                                'cost' => $package['price'],
                                'package_id' => $package['id'],
                                'active' => 1,
                                'package_order' => $package['order'],
                                'category' => $item['id'],
                                'url' => 'none'
                            ));
                        } else {
                            $queries->update('donation_packages', $package_exists[0]->id, array(
                                'name' => htmlspecialchars($package['name']),
                                'cost' => $package['price'],
                                'package_id' => $package['id'],
                                'active' => 1,
                                'package_order' => $package['order'],
                                'category' => $item['id'],
                                'url' => 'none'
                            ));

                        }
                    }

                    // Add to array containing all packages
                    $packages[] = $package['id'];
                }
            }

            // Add to array containing all categories
            $categories[] = $item['id'];
        }
    }

    // Delete categories and packages no longer on web store
    $package_query = $queries->getWhere('donation_packages', array('package_id', '<>', 0));
    foreach($package_query as $item){
        if(!in_array($item->package_id, $packages)){
            $queries->delete('donation_packages', array('package_id', '=', $item->package_id));
        }
    }

    $category_query = $queries->getWhere('donation_categories', array('cid', '<>', 0));
    foreach($category_query as $item){
        if(!in_array($item->cid, $categories)){
            $queries->delete('donation_categories', array('cid', '=', $item->cid));
        }
    }

    /*
     *  Latest donors
     */

    if(count($bc_payments)){
        // Get latest payment already stored in cache
        $latest_payment = $queries->orderWhere('donation_cache', 'id <> 0', 'time', 'DESC');
        if(count($latest_payment)) $latest_payment = $latest_payment[0]->time;
        else $latest_payment = 0;

        foreach($bc_payments as $item){
            if($latest_payment < strtotime($item['date'])){
                // Input into database
                $queries->create('donation_cache', array(
                    'time' => strtotime($item['date']),
                    'uuid' => htmlspecialchars($item['player']['uuid']),
                    'ign' => htmlspecialchars($item['player']['name']),
                    'price' => $item['amount'],
                    'package' => (isset($item['packages'][0]) ? $item['packages'][0]['id'] : 0) // TODO: multiple packages
                ));
            }
        }
    }

} else if($webstore == 'cs'){
    // CraftingStore
    require('integration/craftingstore.php');
    /*
     *  Categories and packages
     */

    $packages = array();
    $categories = array();

    // Categories
    foreach($cs_categories['result'] as $item){
        $category_name = htmlspecialchars($item['name']);
        $category = $queries->getWhere('donation_categories', array('name', '=', $category_name));
        $category_order = $item['order'];
        $category_id = $item['id'];

        // Does it already exist in the database?
        if(!count($category)){
            // Non existing, creating.

            $queries->create('donation_categories', array(
                'name' => $category_name,
                'cid' => $category_id,
                '`order`' => ($category_order ? $category_order : 0)
            ));
        } else {
			// Update
			$cid = $category[0]->id;

			$queries->update('donation_categories', $cid, array(
				'name' => $category_name,
				'`order`' => ($category_order ? $category_order : 0),
				'cid' => $category_id
			));
		}

        $categories[] = $category_name;
    }

    // Delete categories no longer on web store
    $category_query = $queries->getWhere('donation_categories', array('cid', '<>', 0));
    foreach($category_query as $item){
        if(!in_array($item->name, $categories)){
            $queries->delete('donation_categories', array('name', '=', $item->name));
        }
    }

    // Packages
    if(count($cs_packages['result'])){
        foreach($cs_packages['result'] as $package){
            $package_exists = $queries->getWhere('donation_packages', array('package_id', '=', $package['id']));
            if(!count($package_exists)){
                $queries->create('donation_packages', array(
                    'name' => htmlspecialchars($package['name']),
                    'description' => htmlspecialchars($package['description']),
                    'cost' => $package['price'],
                    'package_id' => $package['id'],
                    'active' => $package['enabled'],
                    'package_order' => $package['order'],
                    'category' => $package['category'],
                    'url' => $package['url']
                ));
            } else {
                // Update package
                // Only update description if no custom one is set
                if($package_exists[0]->custom_description == 0){
                    $queries->update('donation_packages', $package_exists[0]->id, array(
                        'name' => htmlspecialchars($package['name']),
                        'description' => $package['description'],
                        'cost' => $package['price'],
                        'package_id' => $package['id'],
                        'active' => $package['enabled'],
                        'package_order' => $package['order'],
                        'category' => $package['category'],
                        'url' => $package['url']
                    ));
                } else {
                    $queries->update('donation_packages', $package_exists[0]->id, array(
                        'name' => htmlspecialchars($package['name']),
                        'cost' => $package['price'],
                        'package_id' => $package['id'],
                        'active' => $package['enabled'],
                        'package_order' => $package['order'],
                        'category' => $package['category'],
                        'url' => $package['url']
                    ));

                }
            }

            // Add to array containing all packages
            $packages[] = $package['id'];
        }
    }

    // Delete packages no longer on web store
    $package_query = $queries->getWhere('donation_packages', array('package_id', '<>', 0));
    foreach($package_query as $item){
        if(!in_array($item->package_id, $packages)){
            $queries->delete('donation_packages', array('package_id', '=', $item->package_id));
        }
    }

    /*
     *  Latest donors
     */
    if(count($cs_donors['result'])){
        // Get latest payment already stored in cache
        $latest_payment = $queries->orderWhere('donation_cache', 'id <> 0', 'time', 'DESC');
        if(count($latest_payment)) $latest_payment = $latest_payment[0]->time;
        else $latest_payment = 0;

        foreach($cs_donors['result'] as $item){
            if($latest_payment < $item['createdAt']){
                // Input into database
                $queries->create('donation_cache', array(
                    'time' => $item['createdAt'],
                    'uuid' => $item['minecraftUUID'],
                    'ign' => htmlspecialchars($item['minecraftName']),
                    'price' => $item['price'],
                    'package' => (isset($item['packages'][0]) ? $item['packages'][0]['packageId'] : 0) // TODO: support multiple packages
                ));
            }
        }
    }
}

return true;

die('Valid');
