<?php

class PurgeInactiveUsers extends Task {

    public function run(): string {
        $language = $this->_container->get(Language::class);

        $errors = [];

        $cutoff = (int) Settings::get('purge_inactive_users_cutoff', '0');
        if (!$cutoff) {
            $result = ['cutoff' => $cutoff, 'total' => 'Task disabled.'];
        } else {
            $cutoff_timestamp = strtotime("$cutoff days ago");
            $total = DB::getInstance()->query(
                'SELECT COUNT(*) c FROM nl2_users WHERE active = 0 AND isbanned = 0 AND joined < ?',
                [$cutoff_timestamp]
            )->first()->c;

            if ($total) {
                $users = DB::getInstance()->query(
                    'SELECT id FROM nl2_users WHERE active = 0 AND isbanned = 0 AND joined < ?',
                    [$cutoff_timestamp]
                )->results();

                foreach ($users as $user) {
                    EventHandler::executeEvent(new UserDeletedEvent(new User($user->id)));
                }
            }

            $result = [
                'cutoff' => $cutoff,
                'cutoff_timestamp' => $cutoff_timestamp,
                'total' => $total,
                'users' => $users ?? [],
            ];
        }

        $this->setOutput(['result' => $result, 'errors' => $errors]);
        $this->reschedule($language);

        return Task::STATUS_COMPLETED;
    }

    private function reschedule(Language $language) {
        Queue::schedule((new PurgeInactiveUsers())->fromNew(
            Module::getIdFromName('Core'),
            $language->get('admin', 'purge_inactive_users'),
            [],
            Date::next()->getTimestamp()
        ));
    }

    public static function schedule(Language $language) {
        (new self())->reschedule($language);
    }
}
