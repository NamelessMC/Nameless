<?php

class ForumSubforumSeeder extends Seeder {

    public array $tables = [
        'nl2_forums',
        'nl2_forums_permissions',
    ];

    public const GROUP_PERMISSIONS = [
        0 => [
            'view' => true,
            'create_topic' => false,
            'edit_topic' => false,
            'create_post' => false,
            'view_other_topics' => false,
            'moderate' => false,
        ],
        1 => [
            'view' => true,
            'create_topic' => true,
            'edit_topic' => true,
            'create_post' => true,
            'view_other_topics' => true,
            'moderate' => false,
        ],
        2 => [
            'view' => true,
            'create_topic' => true,
            'edit_topic' => true,
            'create_post' => true,
            'view_other_topics' => true,
            'moderate' => true,
        ],
        3 => [
            'view' => true,
            'create_topic' => true,
            'edit_topic' => true,
            'create_post' => true,
            'view_other_topics' => true,
            'moderate' => true,
        ],
    ];

    protected function run(DB $db, \Faker\Generator $faker): void {
        $categories = $db->get('forums', ['forum_type', 'category'])->results();

        foreach ($categories as $category) {
            $this->times(FORUM_SUBFORUM_COUNT, static function () use ($db, $faker, $category) {
                $db->insert('forums', [
                    'forum_title' => $faker->words($faker->boolean ? 2 : 3, true),
                    'forum_description' => $faker->boolean(40) ? $faker->sentences($faker->boolean ? 3 : 4, true) : null,
                    'parent' => $category->id,
                    'forum_order' => $category->forum_order + 1,
                    'forum_type' => 'forum',
                    'topic_placeholder' => $faker->boolean ? $faker->sentences(3, true) : null,
                ]);

                $forum_id = $db->lastId();
                foreach (self::GROUP_PERMISSIONS as $group => $permissions) {
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
}
