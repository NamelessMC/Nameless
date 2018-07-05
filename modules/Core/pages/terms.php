<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Site terms page
 */

// Always define page name
define('PAGE', 'terms');
?>
<!DOCTYPE html>
<html<?php if(defined('HTML_CLASS')) echo ' class="' . HTML_CLASS . '"'; ?> lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>" <?php if(defined('HTML_RTL') && HTML_RTL === true) echo ' dir="rtl"'; ?>>
<head>
    <!-- Standard Meta -->
    <meta charset="<?php echo (defined('LANG_CHARSET') ? LANG_CHARSET : 'utf-8'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
    <?php
    $title = $language->get('user', 'terms_and_conditions');
    require(ROOT_PATH . '/core/templates/header.php');
    ?>
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/css/spoiler.css">

</head>
<body>
<?php
require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Retrieve terms from database
$site_terms = $queries->getWhere('settings', array('name', '=', 't_and_c_site'));
$site_terms = Output::getPurified($site_terms[0]->value);

$nameless_terms = $queries->getWhere('settings', array('name', '=', 't_and_c'));
$nameless_terms = Output::getPurified($nameless_terms[0]->value);

$smarty->assign(array(
    'TERMS' => $language->get('user', 'terms_and_conditions'),
    'SITE_TERMS' => $site_terms,
    'NAMELESS_TERMS' => $nameless_terms
));

$smarty->display(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/terms.tpl');

require(ROOT_PATH . '/core/templates/scripts.php'); ?>
<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js"></script>

</body>
</html>