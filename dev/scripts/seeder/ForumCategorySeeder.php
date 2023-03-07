<?php

class ForumCategorySeeder extends Seeder {

    public array $tables = [
        'nl2_forums',
    ];

    protected function run(DB $db, \Faker\Generator $faker): void {
        $order = 1;
        $this->times(FORUM_CATEGORY_COUNT, static function () use ($db, $faker, &$order) {
            $db->insert('forums', [
                'forum_title' => $faker->words($faker->boolean ? 2 : 3, true),
                'forum_description' => $faker->boolean(75) ? $faker->sentences($faker->boolean ? 3 : 4, true) : null,
                'parent' => 0,
                'forum_order' => $order++,
                'news' => $faker->boolean(20),
                'forum_type' => 'category',
            ]);

            $forum_id = $db->lastId();
            foreach (ForumSubforumSeeder::GROUP_PERMISSIONS as $group => $permissions) {
                $db->insert('forums_permissions', [
                    'forum_id' => $forum_id,
                    'group_id' => $group,
                    'view' => $permissions['view'],
                    'create_topic' => $permissions['create_topic'],
                    'edit_topic' => $permissions['edit_topic'],
                    'create_post' => $permissions['create_post'],
                    'view_other_topics' => $permissions['view_other_topics'],
                    'moderate' => $permissions['moderate'],
                ]);
            }
        });
    }
}
