<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Site terms page
 */

// Always define page name
define('PAGE', 'terms');
?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
    <?php
    $title = $language->get('user', 'terms_and_conditions');
    require('core/templates/header.php');
    ?>

</head>
<body>
<?php
require('core/templates/navbar.php');
require('core/templates/footer.php');

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

$smarty->display('custom/templates/' . TEMPLATE . '/terms.tpl');

require('core/templates/scripts.php'); ?>

</body>
</html>