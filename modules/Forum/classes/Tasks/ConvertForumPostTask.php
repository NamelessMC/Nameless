<?php

class ConvertForumPostTask extends Task {

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
                   `post_content`
            FROM nl2_posts
            LIMIT $start, 50
            SQL
        );

        if ($posts->count()) {
            $posts = $posts->results();
            foreach ($posts as $post) {
                DB::getInstance()->update('posts', $post->id, [
                    'post_content' => Output::getDecoded($post->post_content),
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
        $hasBeenScheduled = DB::getInstance()->query('SELECT COUNT(*) c FROM nl2_queue WHERE `task` = \'ConvertForumPostTask\'')->first()->c;

        if (!$hasBeenScheduled) {
            $totalForumPosts = DB::getInstance()->query('SELECT COUNT(*) c FROM nl2_posts')->first()->c;

            Queue::schedule((new ConvertForumPostTask())->fromNew(
                Module::getIdFromName('Forum'),
                'Convert forum posts',
                [],
                date('U'),
                null,
                null,
                true,
                $totalForumPosts
            ));
        }
    }
}
