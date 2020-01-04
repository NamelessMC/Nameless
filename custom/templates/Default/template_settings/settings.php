<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr7
 *
 *  License: MIT
 *
 *  Default template settings
 */

// Custom language
$default_theme_language = new Language(ROOT_PATH . '/custom/templates/Default/template_settings/language', LANGUAGE);

if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$cache->setCache('default_template');

		if(isset($_POST['theme'])){
			$cache->store('bootswatch', $_POST['theme']);
		}

		if(isset($_POST['navbarType']) && ($_POST['navbarType'] == 'dark' || $_POST['navbarType'] == 'light')){
			$cache->store('nav_style', $_POST['navbarType']);
		}

		if(isset($_POST['navbarColour'])){
			$cache->store('nav_bg', $_POST['navbarColour']);
		}

		Session::flash('admin_templates', $language->get('admin', 'successfully_updated'));

	} else
		$errors = array($language->get('general', 'invalid_token'));
}

// Get values
$cache->setCache('default_template');
if($cache->isCached('bootswatch')){
	$selected_theme = $cache->retrieve('bootswatch');
} else {
	$selected_theme = 'bootstrap';
	$cache->store('bootswatch', 'bootstrap');
}

if($cache->isCached('nav_style')){
	$nav_style = $cache->retrieve('nav_style');
} else {
	$nav_style = 'light';
	$cache->store('nav_style', 'light');
}

if($cache->isCached('nav_bg')){
	$nav_bg = $cache->retrieve('nav_bg');
} else {
	$nav_bg = 'light';
	$cache->store('nav_bg', 'light');
}

$themes = array(
	0 => array(
		'value' => 'bootstrap',
		'name' => 'Default',
		'selected' => ($selected_theme == 'bootstrap')
	),
	1 => array(
		'value' => 'cerulean',
		'name' => 'Cerulean',
		'selected' => ($selected_theme == 'cerulean')
	),
	2 => array(
		'value' => 'cosmo',
		'name' => 'Cosmo',
		'selected' => ($selected_theme == 'cosmo')
	),
	3 => array(
		'value' => 'cyborg',
		'name' => 'Cyborg',
		'selected' => ($selected_theme == 'cyborg')
	),
	4 => array(
		'value' => 'darkly',
		'name' => 'Darkly',
		'selected' => ($selected_theme == 'darkly')
	),
	5 => array(
		'value' => 'flatly',
		'name' => 'Flatly',
		'selected' => ($selected_theme == 'flatly')
	),
	6 => array(
		'value' => 'journal',
		'name' => 'Journal',
		'selected' => ($selected_theme == 'journal')
	),
	7 => array(
		'value' => 'litera',
		'name' => 'Litera',
		'selected' => ($selected_theme == 'litera')
	),
	8 => array(
		'value' => 'lumen',
		'name' => 'Lumen',
		'selected' => ($selected_theme == 'lumen')
	),
	9 => array(
		'value' => 'lux',
		'name' => 'Lux',
		'selected' => ($selected_theme == 'lux')
	),
	10 => array(
		'value' => 'materia',
		'name' => 'Materia',
		'selected' => ($selected_theme == 'materia')
	),
	11 => array(
		'value' => 'minty',
		'name' => 'Minty',
		'selected' => ($selected_theme == 'minty')
	),
	12 => array(
		'value' => 'pulse',
		'name' => 'Pulse',
		'selected' => ($selected_theme == 'pulse')
	),
	13 => array(
		'value' => 'sandstone',
		'name' => 'Sandstone',
		'selected' => ($selected_theme == 'sandstone')
	),
	14 => array(
		'value' => 'simplex',
		'name' => 'Simplex',
		'selected' => ($selected_theme == 'simplex')
	),
	15 => array(
		'value' => 'sketchy',
		'name' => 'Sketchy',
		'selected' => ($selected_theme == 'sketchy')
	),
	16 => array(
		'value' => 'slate',
		'name' => 'Slate',
		'selected' => ($selected_theme == 'slate')
	),
	17 => array(
		'value' => 'solar',
		'name' => 'Solar',
		'selected' => ($selected_theme == 'solar')
	),
	18 => array(
		'value' => 'spacelab',
		'name' => 'Spacelab',
		'selected' => ($selected_theme == 'spacelab')
	),
	19 => array(
		'value' => 'superhero',
		'name' => 'Superhero',
		'selected' => ($selected_theme == 'superhero')
	),
	20 => array(
		'value' => 'united',
		'name' => 'United',
		'selected' => ($selected_theme == 'united')
	),
	21 => array(
		'value' => 'yeti',
		'name' => 'Yeti',
		'selected' => ($selected_theme == 'yeti')
	)
);

$nav_colours = array(
	0 => array(
		'value' => 'light',
		'name' => 'Light',
		'selected' => ($nav_bg == 'light')
	),
	1 => array(
		'value' => 'primary',
		'name' => 'Primary',
		'selected' => ($nav_bg == 'primary')
	),
	2 => array(
		'value' => 'secondary',
		'name' => 'Secondary',
		'selected' => ($nav_bg == 'secondary')
	),
	3 => array(
		'value' => 'success',
		'name' => 'Success',
		'selected' => ($nav_bg == 'success')
	),
	4 => array(
		'value' => 'danger',
		'name' => 'Danger',
		'selected' => ($nav_bg == 'danger')
	),
	5 => array(
		'value' => 'warning',
		'name' => 'Warning',
		'selected' => ($nav_bg == 'warning')
	),
	6 => array(
		'value' => 'info',
		'name' => 'Info',
		'selected' => ($nav_bg == 'info')
	),
	7 => array(
		'value' => 'dark',
		'name' => 'Dark',
		'selected' => ($nav_bg == 'dark')
	)
);

$smarty->assign(array(
	'DEFAULT_THEME' => $default_theme_language->get('language', 'default_theme_title'),
	'SUBMIT' => $language->get('general', 'submit'),
	'THEME' => $default_theme_language->get('language', 'theme'),
	'THEMES' => $themes,
	'NAVBAR_STYLE' => $default_theme_language->get('language', 'navbar_style'),
	'NAVBAR_STYLE_VALUE' => $nav_style,
	'NAVBAR_COLOUR' => $default_theme_language->get('language', 'navbar_colour'),
	'NAVBAR_COLOURS' => $nav_colours,
	'SETTINGS_TEMPLATE' => ROOT_PATH . '/custom/templates/Default/template_settings/settings.tpl'
));
