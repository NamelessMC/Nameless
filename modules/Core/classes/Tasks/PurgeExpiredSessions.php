<?php
class PurgeExpiredSessions extends Task
{
    public function run(): string {
        $language = $this->_container->get(Language::class);

        $cutoff = strtotime('1 month ago');
        $count = DB::getInstance()->query(
            <<<SQL
                SELECT
                    COUNT(*) c
                FROM
                    `nl2_users_session`
                WHERE
                    `last_seen` < ? OR
                    (
                        `last_seen` IS NULL AND
                        `active` = 0 AND
                        `expires_at` IS NULL
                    )
            SQL,
            [
                $cutoff,
            ]
        )->first()->c;

        if ($count) {
            DB::getInstance()->query(
                'DELETE FROM `nl2_users_session` WHERE `last_seen` < ? OR (`last_seen` < ? AND `active` = 0 AND `expires_at` IS NULL)',
                [
                    $cutoff,
                ]
            );
        }

        $this->setOutput(['result' => $language->get('admin', 'sessions_purged', ['count' => $count])]);
        $this->reschedule($language);

        return Task::STATUS_COMPLETED;
    }

    private function reschedule(Language $language) {
        Queue::schedule((new PurgeExpiredSessions())->fromNew(
            Module::getIdFromName('Core'),
            $language->get('admin', 'purge_sessions'),
            [],
            Date::next()->getTimestamp()
        ));
    }

    public static function schedule(Language $language) {
        (new self())->reschedule($language);
    }
}
