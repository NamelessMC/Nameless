<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  License: MIT
 *
 *  Discord widget settings
 */

// Check input
$cache->setCache('social_media');

if(Input::exists()){
    if(Token::check(Input::get('token'))){
        if(isset($_POST['theme']))
            $cache->store('discord_widget_theme', $_POST['theme']);

        $discord_id = $queries->getWhere('settings', array('name', '=', 'discord'));
        $discord_id = $discord_id[0]->id;

        if(isset($_POST['discord_api_key'])){
            $discord_api_key = $_POST['discord_api_key'];

        } else {
            $discord_api_key = '';

        }

        $queries->update('settings', $discord_id, array(
            'value' => Output::getClean($discord_api_key)
        ));

        $cache->store('discord', Output::getClean($discord_api_key));

    } else {
        $error = $language->get('general', 'invalid_token');
    }
}

if($cache->isCached('discord'))
    $discord_api = $cache->retrieve('discord');
else
    $discord_api = '';

if($cache->isCached('discord_widget_theme'))
    $discord_theme = $cache->retrieve('discord_widget_theme');
else
    $discord_theme = 'dark';
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
        <label for="inputDiscordId"><?php echo $language->get('admin', 'discord_id'); ?></label>
        <input class="form-control" type="text" name="discord_api_key"
               id="inputDiscordId"
               value="<?php echo Output::getClean($discord_api); ?>">
    </div>
    <div class="form-group">
        <label for="inputDiscordTheme"><?php echo $language->get('admin', 'discord_widget_theme'); ?></label>
        <select class="form-control" id="inputDiscordTheme" name="theme">
            <option value="dark"<?php if($discord_theme == 'dark') echo ' selected'; ?>><?php echo $language->get('admin', 'dark'); ?></option>
            <option value="light"<?php if($discord_theme == 'light') echo ' selected'; ?>><?php echo $language->get('admin', 'light'); ?></option>
        </select>
    </div>
    <div type="form-group">
        <input type="hidden" name="token" value="<?php echo Token::get(); ?>">
        <input type="submit" class="btn btn-primary" value="<?php echo $language->get('general', 'submit'); ?>">
    </div>
</form>
