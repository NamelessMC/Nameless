<?php
if (isset($_SESSION['modules_selected']) && $_SESSION['modules_selected'] == true) {
    Redirect::to('?step=conversion');
}

if (!isset($_SESSION['admin_setup']) || $_SESSION['admin_setup'] != true) {
    Redirect::to('?step=admin_account_setup');
}

$all_modules = [];
foreach (scandir(ROOT_PATH . '/modules') as $module) {
    if (!str_starts_with($module, '.') && is_dir(ROOT_PATH . '/modules/' . $module)) {
        $all_modules[$module] = $language->get('installer', 'module_' . strtolower(str_replace(' ', '-', $module)) . '_description');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);
    $cache->setCache('modulescache');
    $enabled_modules = array_filter($cache->retrieve('enabled_modules'), static function ($module) {
        return Input::get('modules')[$module['name']] === '1' || $module['name'] === 'Core';
    });
    $cache->store('enabled_modules', $enabled_modules);

    foreach (Input::get('modules') as $module => $value) {
        if ($module === 'Core') {
            continue;
        }

        DB::getInstance()->update('modules', ['name', $module], [
            'enabled' => $value === '1',
        ]);
    }

    $_SESSION['modules_selected'] = true;
    Redirect::to('?step=conversion');
}

function pointer($module) {
    if ($module !== 'Core') {
        return 'cursor: pointer;';
    }
    
    return '';
}
?>

<form action="" method="post">
    <div class="ui segments">
        <div class="ui secondary segment">
            <h4 class="ui header">
                <?php echo $language->get('installer', 'select_modules'); ?>
            </h4>
        </div>
        <div class="ui segment">
            <p><?php echo $language->get('installer', 'select_modules_details'); ?></p>

            <div class="ui two cards">
                <?php foreach ($all_modules as $module => $description) { ?>
                    <div class="ui card fluid" data-module-name="<?php echo $module ?>" style="<?php echo pointer($module); ?> user-select: none; pointer-events: all !important;" onclick="toggleModule(this)">
                        <input type="hidden" name="modules[<?php echo $module ?>]" value="1">
                        <div class="content">
                            <div class="header">
                                <?php echo $module; ?>
                                <?php if ($module === 'Core') { ?>
                                    <i class="ui lock icon yellow"></i>
                                <?php } else { ?>
                                    <i class="ui check icon green"></i>
                                <?php } ?>
                            </div>
                            <div class="meta">
                                <?php echo $description ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="ui right aligned secondary segment">
            <button class="ui small primary button">
                <?php echo $language->get('installer', 'proceed'); ?>
            </button>
        </div>
    </div>
</form>

<script>
    const selectedModules = [];

    <?php foreach (array_keys($all_modules) as $module) { ?>
        selectedModules.push('<?php echo $module ?>')
    <?php } ?>

    function toggleModule(element) {
        const module = element.dataset.moduleName;
        if (module === 'Core') {
            return;
        }

        if (selectedModules.includes(module)) {
            element.children[0].value = '0';
            selectedModules.splice(selectedModules.indexOf(module), 1);
        } else {
            element.children[0].value = '1';
            selectedModules.push(module);
        }

        element.classList.toggle('disabled');

        const icon = element.children[1].children[0].children[0];
        icon.classList.toggle('green');
        icon.classList.toggle('red');
        icon.classList.toggle('check');
        icon.classList.toggle('x');
    }
</script>
