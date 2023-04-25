<?php

class ForumTopicSeeder extends Seeder {

    public array $tables = [
        'nl2_topics',
    ];

    protected function run(DB $db, \Faker\Generator $faker): void {
        $forums = $db->get('forums', ['id', '<>', 0])->results();
        $users = $db->get('users', ['id', '<>', 0])->results();

        foreach ($forums as $forum) {
            $this->times(FORUM_TOPIC_COUNT, function () use ($db, $faker, $forum, $users) {
                if ($forum->forum_type === 'category' && !$faker->boolean) {
                    return;
                }
                $user = $faker->randomElement($users);
                $topic_date = $this->since($user->joined, $faker)->format('U');

                $db->insert('topics', [
                    'forum_id' => $forum->id,
                    'topic_title' => $faker->sentence,
                    'topic_creator' => $user->id,
                    'topic_last_user' => $user->id,
                    'topic_date' => $topic_date,
                    'topic_reply_date' => $this->since($topic_date, $faker)->format('U'),
                    'topic_views' => $faker->numberBetween(0, 1000),
                    'locked' => $faker->boolean(40),
                    'sticky' => $faker->boolean(20),
                ]);
            });
        }
    }
}
