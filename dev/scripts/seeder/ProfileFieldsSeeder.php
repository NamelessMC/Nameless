<?php

class ProfileFieldsSeeder extends Seeder {

    public array $tables = [
        'nl2_profile_fields',
    ];

    protected function run(DB $db, \Faker\Generator $faker): void {
        $this->times(PROFILE_FIELDS_COUNT, function () use ($db, $faker) {
            $db->insert('profile_fields', [
                'name' => $faker->unique()->word,
                'type' => $faker->randomElement([Fields::TEXT, Fields::TEXTAREA, Fields::DATE]),
                'public' => $faker->boolean(75),
                'required' => $faker->boolean,
                'description' => $faker->sentence,
                'length' => null,
                'forum_posts' => $faker->boolean(75),
                'editable' => $faker->boolean,
            ]);
        });
    }
}
