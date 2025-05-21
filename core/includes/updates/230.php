<?php

return new class() extends UpgradeScript {
    public function run(): void
    {
        $this->runMigrations();

        ConvertProfilePosts::schedule();

        // Convert OnlineUsersWidget to use settings table
        $this->_cache->setCache('online_members');
        $use_nickname_show = $this->_cache->fetch('show_nickname_instead', 0);
        $include_staff = $this->_cache->fetch('include_staff_in_users', 0);
        Settings::set('online_users_widget_use_nicknames', $use_nickname_show);
        Settings::set('online_users_widget_include_staff', $include_staff);
        $this->_cache->eraseAll();

        $this->setVersion('2.3.0');
    }
};
