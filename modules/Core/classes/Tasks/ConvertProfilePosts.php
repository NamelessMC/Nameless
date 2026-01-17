<?php

class ConvertProfilePosts extends Task {

    public function run(): string {
        $start = $this->getFragmentNext() ?? 0;
        $end = $start + 50;
        $nextStatus = Task::STATUS_READY;

        if ($end > $this->getFragmentTotal()) {
            $end = $this->getFragmentTotal();
            $nextStatus = Task::STATUS_COMPLETED;
        }

        $posts = DB::getInstance()->query(
            <<<SQL
            SELECT `id`,
                   `content`
            FROM nl2_user_profile_wall_posts
            LIMIT $start, 50
            SQL
        );

        if ($posts->count()) {
            $posts = $posts->results();
            foreach ($posts as $post) {
                DB::getInstance()->update('user_profile_wall_posts', $post->id, [
                    'content' => Output::getDecoded($post->content),
                ]);
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
        $hasBeenScheduled = DB::getInstance()->query('SELECT COUNT(*) c FROM nl2_queue WHERE `task` = \'ConvertProfilePosts\'')->first()->c;

        if (!$hasBeenScheduled) {
            $totalProfilePosts = DB::getInstance()->query('SELECT COUNT(*) c FROM nl2_user_profile_wall_posts')->first()->c;

            Queue::schedule((new ConvertProfilePosts())->fromNew(
                Module::getIdFromName('Core'),
                'Convert profile posts',
                [],
                date('U'),
                null,
                null,
                true,
                $totalProfilePosts
            ));
        }
    }
}
