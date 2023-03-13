<?php

if (!isset($_SESSION['modules_selected']) || $_SESSION['modules_selected'] != true) {
    Redirect::to('?step=select_modules');
}

try {
    Config::set('core.installed', true);

    unset($_SESSION['admin_setup'], $_SESSION['database_initialized'], $_SESSION['site_initialized'], $_SESSION['modules_selected']);

} catch (Exception $e) {

    $error = $e->getMessage();

}

?>

<?php if (isset($error)) { ?>

    <div class="ui error message">
        <?php echo $error; ?> <a href="?step=finish"><?php echo $language->get('installer', 'reload_page'); ?></a>
    </div>

<?php } else { ?>

    <div class="ui segments">
        <div class="ui secondary segment">
            <h4 class="ui header">
                <?php echo $language->get('installer', 'finish'); ?>
            </h4>
        </div>
        <div class="ui segment">
            <p><?php echo $language->get('installer', 'finish_message'); ?></p>
            <p><?php echo $language->get('installer', 'support_message', [
                'websiteLinkStart' => '<a href="https://namelessmc.com" target="_blank">',
                'websiteLinkEnd' => '</a>',
                'discordLinkStart' => '<a href="https://discord.gg/nameless" target="_blank">',
                'discordLinkEnd' => '</a>',
                'githubLinkStart' => '<a href="https://github.com/NamelessMC/Nameless/" target="_blank">',
                'githubLinkEnd' => '</a>'
            ]); ?></p>
        </div>
        <div class="ui secondary segment">
            <h4 class="ui header">
                <?php echo $language->get('installer', 'credits'); ?>
            </h4>
        </div>
        <div class="ui segment">
            <p><?php echo $language->get('installer', 'credits_message', [
                'contribLinkStart' => '<a href="https://github.com/NamelessMC/Nameless/graphs/contributors" target="_blank">',
                'contribLinkEnd' => '</a>'
            ]); ?></p>
        </div>
        <div class="ui secondary right aligned segment">
            <a href="index.php?route=/panel" class="ui small primary button">
                <?php echo $language->get('installer', 'finish'); ?>
            </a>
        </div>
    </div>

    <?php
}
