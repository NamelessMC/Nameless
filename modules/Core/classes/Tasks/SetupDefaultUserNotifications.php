<?php

class SetupDefaultUserNotifications extends Task {

    public function run(): string {
        $start = $this->getFragmentNext() ?? 0;
        $end = $start + 50;
        $nextStatus = Task::STATUS_READY;

        if ($end > $this->getFragmentTotal()) {
            $end = $this->getFragmentTotal();
            $nextStatus = Task::STATUS_COMPLETED;
        }

        $users = DB::getInstance()->query(
            <<<SQL
            SELECT `id`
            FROM nl2_users
            LIMIT $start, 50
            SQL
        );

        if ($users->count()) {
            $users = $users->results();
            foreach ($users as $user) {
                DefaultUserNotificationPreferencesHook::subscribeUserToDefaultNotifications($user->id);
            }
        }

        $this->setOutput(['start' => $start, 'end' => $end, 'next_status' => $nextStatus]);
        $this->setFragmentNext($end);
        return $nextStatus;
    }

    /**
     * Schedule this task
     *
     * @return void
     */
    public static function schedule() {
        $hasBeenScheduled = DB::getInstance()->query('SELECT COUNT(*) c FROM nl2_queue WHERE `task` = \'SetupDefaultUserNotifications\'')->first()->c;

        if (!$hasBeenScheduled) {
            $totalUsers = DB::getInstance()->query('SELECT COUNT(*) c FROM nl2_users')->first()->c;

            Queue::schedule((new SetupDefaultUserNotifications())->fromNew(
                Module::getIdFromName('Core'),
                'Setup default user notifications',
                [],
                date('U'),
                null,
                null,
                true,
                $totalUsers
            ));
        }
    }
}
