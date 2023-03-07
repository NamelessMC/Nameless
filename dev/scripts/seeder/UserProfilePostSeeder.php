<?php

class UserProfilePostSeeder extends Seeder {

    public array $tables = [
        'nl2_user_profile_wall_posts',
        'nl2_user_profile_wall_posts_replies',
        'nl2_user_profile_wall_posts_reactions',
    ];

    protected function run(DB $db, \Faker\Generator $faker): void {
        $users = $db->get('users', ['id', '<>', 0])->results();

        $this->times(PROFILE_POST_COUNT, function () use ($db, $faker, $users) {
            $user = $faker->randomElement($users);
            $author = $faker->randomElement($users);
            while ($user->id == $author->id) {
                $author = $faker->randomElement($users);
            }

            $db->insert('user_profile_wall_posts', [
                'user_id' => $user->id,
                'author_id' => $author->id,
                'time' => $this->since($author->joined, $faker)->format('U'),
                'content' => $faker->text,
            ]);
        });

        $profile_posts = $db->get('user_profile_wall_posts', ['id', '<>', 0])->results();
        $this->times(PROFILE_POST_REPLY_COUNT, function () use ($db, $faker, $profile_posts) {
            $post = $faker->randomElement($profile_posts);
            $author_id = $faker->randomElement($profile_posts)->author_id;

            $db->insert('user_profile_wall_posts_replies', [
                'post_id' => $post->id,
                'author_id' => $author_id,
                'time' => $this->since($post->time, $faker)->format('U'),
                'content' => $faker->text,
            ]);
        });

        $this->times(PROFILE_POST_REACTION_COUNT, function () use ($db, $faker, $profile_posts) {
            $post = $faker->randomElement($profile_posts);
            $user_id = $faker->randomElement($profile_posts)->user_id;

            $db->insert('user_profile_wall_posts_reactions', [
                'user_id' => $user_id,
                'post_id' => $post->time,
                'reaction_id' => 1,
                'time' => $this->since($post->time, $faker)->format('U'),
            ]);
        });
    }
}
