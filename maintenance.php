<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Maintenance Mode page
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo SITE_NAME; ?> - Maintenance Mode">
    <meta name="robots" content="noindex">

    <?php if(isset($custom_meta)){ echo $custom_meta; } ?>

    <?php $title = 'Maintenance'; ?>

    <?php require('core/templates/header.php'); ?>

</head>
<body>
<?php
// Assign Smarty variables
$smarty->assign(array(
    'TITLE' => $language->get('errors', 'maintenance_title'),
    'RETRY' => $language->get('errors', 'maintenance_retry')
));

// Retrieve maintenance message
$maintenance_message = $maintenance['message'];
if(!empty($maintenance_message)) $smarty->assign('MAINTENANCE_MESSAGE', Output::getPurified(htmlspecialchars_decode($maintenance_message)));
else $smarty->assign('MAINTENANCE_MESSAGE', 'Maintenance mode is enabled.');

// Maintenance template
$smarty->display('custom/templates/' . TEMPLATE . '/maintenance.tpl');
?>
</body>
</html>