<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Image uploads
 */

// Initialisation
$page = 'image_uploads';
define('ROOT_PATH', '../..');

require(ROOT_PATH . '/core/init.php');

// Require Bulletproof
require(ROOT_PATH . '/core/includes/bulletproof/bulletproof.php');

if(!$user->isLoggedIn())
    die();

// Deal with input
if(Input::exists()){
	// Check token
	if(Token::check(Input::get('token'))){
		// Token valid
		$image = new Bulletproof\Image($_FILES);
		$image->setSize(1, 2097152); // between 1b and 2mb
		$image->setDimension(2000, 2000); // 2k x 2k pixel maximum
		$image->setMime(array('jpg', 'png', 'gif', 'jpeg'));
		
		if(Input::get('type') == 'background'){
            $image->setLocation(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'backgrounds')));
        } else if(Input::get('type') == 'template_banner'){
			$image->setLocation(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'template_banners')));
		} else if(Input::get('type') == 'default_avatar'){
		    $image->setLocation(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'avatars', 'defaults')));
		} else if(Input::get('type') == 'profile_banner'){
			if(!$user->hasPermission('usercp.profile_banner')){
				Redirect::to(URL::build('/profile/' . Output::getClean($user->data()->username)));
				die();
			}

			if(!is_dir(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'profile_images', $user->data()->id)))){
				if(!mkdir(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'profile_images', $user->data()->id))))
					die('uploads/profile_images folder not writable! <a href="' . URL::build('/profile/' . Output::getClean($user->data()->username)) . '">Back</a>');
			}

			$image->setLocation(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'profile_images', $user->data()->id)));
		} else {
            // Default to normal avatar upload
            if(!defined('CUSTOM_AVATARS'))
                die('Custom avatar uploading is disabled');

			$image->setLocation(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'avatars')));
			$image->setName($user->data()->id);
		}

		if($image['file']){
		    try {
                $upload = $image->upload();

                if($upload){
                    // OK
                    // Avatar?
                    if(Input::get('type') == 'avatar'){
                        $user->update(array(
                            'has_avatar' => 1
                        ));

                        Redirect::to(URL::build('/user/settings'));
                        die();
                    } else if(Input::get('type') == 'profile_banner'){
	                    $user->update(array(
		                    'banner' => Output::getClean($user->data()->id . '/' . $upload->getName() . '.' . $upload->getMime())
	                    ));

	                    Redirect::to(URL::build('/profile/' . Output::getClean($user->data()->username)));
	                    die();
                    } else {
						die('OK');
					}
                } else {
					http_response_code(400);
                    echo $image["error"];
                    die();
                }
            } catch(Exception $e){
                // Error
				http_response_code(400);
				echo $e->getMessage();
				die();
            }
		} else {
			if(Input::get('type') == 'avatar'){
				Redirect::to(URL::build('/user/settings'));
				die();
			} else
				die('No image selected');
		}
		
	} else {
		// Invalid token
		if(Input::get('type') == 'background'){
			Session::flash('admin_images', '<div class="alert alert-danger">' . $language->get('general', 'invalid_token') . '</div>');
		}
	}
}

die('Invalid input');