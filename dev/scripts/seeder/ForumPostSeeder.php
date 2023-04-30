<?php

class ForumPostSeeder extends Seeder {

    public array $tables = [
        'nl2_posts',
    ];

    protected function run(DB $db, \Faker\Generator $faker): void {
        $topics = $db->get('topics', ['id', '<>', '0'])->results();
        $users = $db->get('users', ['id', '<>', '0'])->results();

        foreach ($topics as $topic) {
            $created = $faker->dateTimeBetween('-1 years');

            $db->insert('posts', [
                'forum_id' => $topic->forum_id,
                'topic_id' => $topic->id,
                'post_creator' => $topic->topic_creator,
                'post_content' => $faker->boolean(40) ? $faker->randomHtml() : $faker->sentences($faker->numberBetween(3, 30), true),
                'post_date' => $created->format('Y-m-d H:i:s'),
                'created' => $created->format('U'),
            ]);

            $this->times(FORUM_POST_COUNT, function () use ($db, $faker, $topic, $users) {
                $user = $faker->randomElement($users);
                $created = $this->since($user->joined, $faker);

                $db->insert('posts', [
                    'forum_id' => $topic->forum_id,
                    'topic_id' => $topic->id,
                    'post_creator' => $user->id,
                    'post_content' => $faker->boolean(40) ? $faker->randomHtml() : $faker->sentences($faker->numberBetween(3, 30), true),
                    'post_date' => $created->format('Y-m-d H:i:s'),
                    'created' => $created->format('U'),
                ]);

                $db->update('forums', ['id', $topic->forum_id], [
                    'last_post_date' => $created->format('U'),
                    'last_user_posted' => $user->id,
                    'last_topic_posted' => $topic->id,
                ]);

                if ($faker->boolean(60)) {
                    return;
                }

                $this->times($faker->numberBetween(0, 5), function () use ($db, $faker, $user, $users) {
                    $post_id = $db->lastId();
                    $db->insert('forums_reactions', [
                        'post_id' => $post_id,
                        'user_received' => $user->id,
                        'user_given' => $faker->randomElement($users)->id,
                        'reaction_id' => $faker->randomElement([1, 2, 3]),
                        'time' => date('U'),
                    ]);
                });
            });
        }
    }
}
