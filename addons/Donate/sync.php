<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 *  Copyright (c) 2016 Samerton
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
	 * PACKAGES SYNC
	 */
	
	// Categories first
	foreach($bc_categories['payload'] as $item){
		// Does it already exist in the database?
		$category_name = htmlspecialchars($item['name']);
		$category = $queries->getWhere('donation_categories', array('name', '=', $category_name));
		
		if(!count($category)){
			// No, it doesn't exist
			$category_id = $item['id'];
			$queries->create('donation_categories', array(
				'name' => $category_name,
				'cid' => $category_id,
				'order' => 0
			));
		}
	}
	
	// Delete any categories which don't exist on the web store anymore
	$categories = $queries->getWhere('donation_categories', array('id', '<>', 0));
	foreach($categories as $category){
		if(!in_array_r($category->cid, $bc_categories['payload'])){
			// It doesn't exist anymore
			$queries->delete('donation_categories', array('id', '=', $category->id));
		}
	}
	
	// Packages next
	foreach($bc_packages['payload'] as $item){
		// Does it already exist in the database?
		$package_name = htmlspecialchars($item['name']);
		$package = $queries->getWhere('donation_packages', array('name', '=', $package_name));
		
		if(!count($package)){
			// No, it doesn't exist
			$package_id = $item['id'];
			$package_category = $item['category'];
			$package_description = htmlspecialchars($item['description']);
			$package_price = $item['price'];
			$package_order = $item['order'];
			
			$queries->create('donation_packages', array(
				'name' => $package_name,
				'description' => $package_description,
				'cost' => $package_price,
				'package_id' => $package_id,
				'active' => 1,
				'package_order' => $package_order,
				'category' => $package_category,
				'url' => ''
			));
		}
	}
	
	// Delete any packages which don't exist on the web store anymore
	$packages = $queries->getWhere('donation_packages', array('id', '<>', 0));
	foreach($packages as $package){
		if(!in_array_r($package->package_id, $bc_packages['payload'])){
			// It doesn't exist anymore
			$queries->delete('donation_packages', array('id', '=', $package->id));
		}
	}
	
	/*
	 * LATEST DONORS SYNC
	 */
	 
	foreach($bc_payments["payload"] as $donation){
		$existing = $queries->getWhere('donation_cache', array("time", "=", $donation["time"]));
		if(!count($existing)){
			try {
				$queries->create('donation_cache', array(
					"time" => $donation["time"],
					"uuid" => $donation["uuid"],
					"ign" => $donation["ign"],
					"price" => $donation["price"],
					"package" => $donation["packages"][0]
				));
			} catch(Exception $e){
				die($e->getMessage());
			}
		}
	}

	/*
	 * GROUP SYNC
	 * 1 - import donor groups from database
	 * 2 - loop through donors
	 * 3 - for each donor, check if they already have a DONOR/STANDARD group (ie not staff)
	 * 4 - if the user is a staff member, do nothing, else:
	 *       a - check if the user is a donor already, if so:
	 *       		i - add most valuable package to the user
			 b - if not, add the most valuable package (if they've bought multiple) to the user
	 */

	$donor_groups = $queries->getWhere('groups', array("buycraft_id", "<>", "NULL"));

	foreach($bc_payments["payload"] as $donor){
		$donor_user = $queries->getWhere('users', array("uuid", "=", $donor["uuid"]));
		if(count($donor_user)){ // see if the user has registered
			// Are they a staff member?
			$user_group = $queries->getWhere('groups', array('id', '=', $donor_user[0]->group_id));
			if($user_group[0]->staff == 1){ 
				// Don't do anything as they're a staff member - we want them to keep their staff rank
			} else {
				if(count($donor["packages"]) === 1){
					$donor_group = $queries->getWhere("groups", array("buycraft_id", "=", $donor["packages"][0]));
					$package_group_id = $donor_group[0]->id;
					if($donor_user[0]->group_id < $package_group_id){
						try {
							$queries->update('users', $donor_user[0]->id, array(
								'group_id' => $package_group_id
							));
						} catch(Exception $e){
							die($e->getMessage());
						}
					}
				}
				/*
				 * TODO: Run check if user has purchased multiple packages
				 */
			}
		}
	}
	
} else if($webstore == 'mm'){
	// MinecraftMarket
	require('integration/minecraftmarket.php');
	
	/*
	 * PACKAGES SYNC
	 */
	
	// Categories first
	foreach($mm_gui['categories'] as $item){
		// Does it already exist in the database?
		$category_name = htmlspecialchars($item['name']);
		$category = $queries->getWhere('donation_categories', array('name', '=', $category_name));
		
		if(!count($category)){
			// No, it doesn't exist
			$category_order = 0;
			if(!empty($category_order)){
				$category_order = $item['order'];
			}
			$category_id = $item['id'];
			$queries->create('donation_categories', array(
				'name' => $category_name,
				'cid' => $category_id,
				'order' => $category_order
			));
		}
	}
	
	// Delete any categories which don't exist on the web store anymore
	$categories = $queries->getWhere('donation_categories', array('id', '<>', 0));
	foreach($categories as $category){
		if(!in_array_r($category->cid, $mm_gui['categories'])){
			// It doesn't exist anymore
			$queries->delete('donation_categories', array('id', '=', $category->id));
		}
	}
	
	// Packages next
	foreach($mm_gui['result'] as $item){
		// Does it already exist in the database?
		$package_name = htmlspecialchars($item['name']);
		$package = $queries->getWhere('donation_packages', array('name', '=', $package_name));
		
		if(!count($package)){
			// No, it doesn't exist
			$package_id = $item['id'];
			$package_category = $item['categoryid'];
			$package_description = htmlspecialchars($item['description']);
			$package_price = $item['price'];
			$package_url = htmlspecialchars($item['url']);
			
			$queries->create('donation_packages', array(
				'name' => $package_name,
				'description' => $package_description,
				'cost' => $package_price,
				'package_id' => $package_id,
				'active' => 1,
				'package_order' => 0,
				'category' => $package_category,
				'url' => $package_url
			));
		}
	}
	
	// Delete any packages which don't exist on the web store anymore
	$packages = $queries->getWhere('donation_packages', array('id', '<>', 0));
	foreach($packages as $package){
		if(!in_array_r($package->package_id, $mm_gui['result'])){
			// It doesn't exist anymore
			$queries->delete('donation_packages', array('id', '=', $package->id));
		}
	}
	
	/*
	 * DONORS SYNC
	 */	

	foreach($mm_donors['result'] as $item){
		// Does it already exist in the database?
		$date = strtotime($item['date']);
		$donor_query = $queries->getWhere('donation_cache', array('time', '=', $date));
		
		if(count($donor_query)){
			// Already exists, we can stop now
			break;
		}
		
		$donor_name = htmlspecialchars($item['username']);
		$price = $item['price'];
		$package = $item['id'];
		
		// Doesn't exist, input into our database
		$queries->create('donation_cache', array(
			'time' => $date,
			'uuid' => '',
			'ign' => $donor_name,
			'price' => $price,
			'package' => $package
		));
	}
	
	/*
	 * GROUP SYNC
	 * 1 - import donor groups from database
	 * 2 - loop through donors
	 * 3 - for each donor, check if they already have a DONOR/STANDARD group (ie not staff)
	 * 4 - if the user is a staff member, do nothing, else:
	 *       a - check if the user is a donor already, if so:
	 *       		i - add most valuable package to the user
			 b - if not, add the most valuable package (if they've bought multiple) to the user
	 */

	$donor_groups = $queries->getWhere("groups", array("buycraft_id", "<>", "NULL"));

	foreach($mm_donors['result'] as $donor){
		$donor_user = $queries->getWhere('users', array('username', '=', htmlspecialchars($donor['username']))); // user from users table
		if(!count($donor_user)){
			$donor_user = $queries->getWhere('users', array('mcname', '=', htmlspecialchars($donor['username']))); 
		}
		if(count($donor_user)){ // if the user has registered on the website..
			// Are they a staff member?
			$user_group = $queries->getWhere('groups', array('id', '=', $donor_user[0]->group_id));
			if($user_group[0]->staff == 1){ 
				// Don't do anything as they're a staff member - we want them to keep their staff rank
			} else {
				$donor_group = $queries->getWhere("groups", array("buycraft_id", "=", $item['id']));
				$package_group_id = $donor_group[0]->id;
				if($donor_user[0]->group_id < $package_group_id){
					try {
						$queries->update("users", $donor_user[0]->id, array(
							'group_id' => $package_group_id
						));
					} catch(Exception $e){
						die($e->getMessage());
					}
				}
				/*
				 * TODO: Run check if user has purchased multiple packages
				 */
			}
		}
	}
	
} else if($webstore == 'mcs'){
	// MCStock
	require('integration/mcstock.php');
	
	/*
	 * PACKAGES SYNC
	 */
	
	// Categories first
	foreach($mcs_categories['data'] as $id => $item){
		// Does it already exist in the database?
		$category_name = htmlspecialchars($item['name']);
		$category = $queries->getWhere('donation_categories', array('cid', '=', htmlspecialchars($id)));
		
		if(!count($category)){
			// No, it doesn't exist
			$queries->create('donation_categories', array(
				'name' => $category_name,
				'cid' => htmlspecialchars($id),
				'order' => $item['position']
			));
		}
	}
	
	// Delete any categories which don't exist on the web store anymore
	$categories = $queries->getWhere('donation_categories', array('id', '<>', 0));
	foreach($categories as $category){
		if(!array_key_exists($category->cid, $mcs_categories['data'])){
			// It doesn't exist anymore
			$queries->delete('donation_categories', array('id', '=', $category->id));
		}
	}
	
	// Packages next
	$package_ids = array();
	foreach($mcs_packages as $mcs_package){
		$cid = htmlspecialchars($mcs_package['cid']);
		foreach($mcs_package['data'] as $id => $item){
			// Does it already exist in the database?
			$package_name = htmlspecialchars($item['name']);
			$package = $queries->getWhere('donation_packages', array('category', '=', htmlspecialchars($id)));
			
			if(!count($package)){
				// No, it doesn't exist
				$package_id = htmlspecialchars($id);
				$package_description = htmlspecialchars($item['description'][0]);
				$package_price = $item['price'];
				
				$queries->create('donation_packages', array(
					'name' => $package_name,
					'description' => $package_description,
					'cost' => $package_price,
					'package_id' => $package_id,
					'active' => 1,
					'package_order' => 0,
					'category' => $cid,
					'url' => ''
				));
			}
			
			$package_ids[] = htmlspecialchars($id);
		}
	}
	
	// Delete any packages which don't exist on the web store anymore
	$packages = $queries->getWhere('donation_packages', array('id', '<>', 0));
	foreach($packages as $package){
		if(!in_array($package->package_id, $package_ids)){
			// It doesn't exist anymore
			$queries->delete('donation_packages', array('id', '=', $package->id));
		}
	}
	
	/*
	 * DONORS SYNC
	 */	

	foreach($mcs_payments['data'] as $id => $item){
		// Does it already exist in the database?
		$date = $item['unix_time'];
		$donor_query = $queries->getWhere('donation_cache', array('time', '=', $date));
		
		if(count($donor_query)){
			// Already exists, we can stop now
			break;
		}
		
		$donor_name = htmlspecialchars($item['username']);
		$price = $item['total'];
		$packages = array();
		
		foreach($item['items'] as $package => $quantity){
			$packages[] = $package;
		}
		
		if(count($packages == 1)){
			$packages = $packages[0];
		} else {
			$packages = 'Multiple';
		}
		
		// Doesn't exist, input into our database
		$queries->create('donation_cache', array(
			'time' => $date,
			'uuid' => '',
			'ign' => $donor_name,
			'price' => $price,
			'package' => $packages
		));
	}
	
	/*
	 * GROUP SYNC
	 * 1 - import donor groups from database
	 * 2 - loop through donors
	 * 3 - for each donor, check if they already have a DONOR/STANDARD group (ie not staff)
	 * 4 - if the user is a staff member, do nothing, else:
	 *       a - check if the user is a donor already, if so:
	 *       		i - add most valuable package to the user
			 b - if not, add the most valuable package (if they've bought multiple) to the user
	 */

	$donor_groups = $queries->getWhere("groups", array("buycraft_id", "<>", "NULL"));

	foreach($mcs_payments['data'] as $item => $donor){
		if(count($donor['items']) == 1){
			// 1 package only
			$donor_user = $queries->getWhere('users', array('username', '=', htmlspecialchars($donor['username']))); // user from users table
			if(!count($donor_user)){
				$donor_user = $queries->getWhere('users', array('mcname', '=', htmlspecialchars($donor['username']))); 
			}
			if(count($donor_user)){ // if the user has registered on the website..
				// Are they a staff member?
				$user_group = $queries->getWhere('groups', array('id', '=', $donor_user[0]->group_id));
				if($user_group[0]->staff == 1){ 
					// Don't do anything as they're a staff member - we want them to keep their staff rank
				} else {
					$donor_group = $queries->getWhere("groups", array("buycraft_id", "=", key($donor['items'])));
					$package_group_id = $donor_group[0]->id;
					if($donor_user[0]->group_id < $package_group_id){
						try {
							$queries->update("users", $donor_user[0]->id, array(
								'group_id' => $package_group_id
							));
						} catch(Exception $e){
							die($e->getMessage());
						}
					}
				}
			}
		} else {
			// Multiple packages
			$donor_user = $queries->getWhere('users', array('username', '=', htmlspecialchars($donor['username']))); // user from users table
			if(!count($donor_user)){
				$donor_user = $queries->getWhere('users', array('mcname', '=', htmlspecialchars($donor['username']))); 
			}
			if(count($donor_user)){ // if the user has registered on the website..
				// Are they a staff member?
				$user_group = $queries->getWhere('groups', array('id', '=', $donor_user[0]->group_id));
				if($user_group[0]->staff == 1){ 
					// Don't do anything as they're a staff member - we want them to keep their staff rank
				} else {
					foreach($donor['items'] as $package_id => $package_quantity){
						$donor_group = $queries->getWhere("groups", array("buycraft_id", "=", $package_id));
						$package_group_id = $donor_group[0]->id;
						if($donor_user[0]->group_id < $package_group_id){
							try {
								$queries->update("users", $donor_user[0]->id, array(
									'group_id' => $package_group_id
								));
							} catch(Exception $e){
								die($e->getMessage());
							}
						}
					}
				}
			}
		}
	}
	
}
 
return true;
 
die('Valid');

if($webstore == "bc"){
	// Buycraft
	require('inc/integration/buycraft.php');


} else if($webstore == "mm"){
	// Minecraft Market
	require('inc/integration/minecraftmarket.php');
	

}

return "true";
?>
