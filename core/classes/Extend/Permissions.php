<?php

namespace NamelessMC\Framework\Extend;

use DI\Container;

class Permissions extends BaseExtender {

    private $permissions = [];

    public function extend(Container $container): void {
        $coreLanguage = $container->get(\Language::class);
        $moduleLanguage = $container->get("{$this->moduleName}Language");

        if (isset($this->permissions['staffcp'])) {
            foreach ($this->permissions['staffcp'] as $permission => $name) {
                \PermissionHandler::registerPermissions($coreLanguage->get('moderator', 'staff_cp'), [
                    $permission => $this->moduleDisplayName . ' » ' . $moduleLanguage->get($name)
                ]);
            }
        }
    }

    public function register(array $permissions): Permissions {
        $this->permissions = $permissions;

        return $this;
    }
}