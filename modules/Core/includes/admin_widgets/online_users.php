<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr3
 *
 *  License: MIT
 *
 *  Online users widget settings
 */

// Check input
$cache->setCache('online_members');

if(Input::exists()){
    if(Token::check(Input::get('token'))){
        if(isset($_POST['staff']) && $_POST['staff'] == 1)
            $cache->store('include_staff_in_users', 1);
        else
            $cache->store('include_staff_in_users', 0);

    } else {
        $error = $language->get('general', 'invalid_token');
    }
}

$include_staff = $cache->retrieve('include_staff_in_users');
?>

<h4 style="display:inline;"><?php echo str_replace('{x}', Output::getClean($widget->name), $language->get('admin', 'editing_widget_x')); ?></h4>
<span class="pull-right">
    <a class="btn btn-warning"
       href="<?php echo URL::build('/admin/widgets/', 'action=edit&amp;w=' . $widget->id); ?>"><?php echo $language->get('general', 'back'); ?></a>
</span>
<br /><br />

<?php
if(isset($error))
    echo '<div class="alert alert-danger">' . $error . '</div>';
?>

<form action="" method="post">
    <div class="form-group">
        <label for="inputIncludeStaff"><?php echo $language->get('admin', 'include_staff_in_user_widget'); ?></label>
        <input class="js-switch" type="checkbox" name="staff"
               id="inputIncludeStaff"
               value="1"<?php if($include_staff == 1) echo ' checked'; ?>>
    </div>
    <div type="form-group">
        <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
        <input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
    </div>
</form>
