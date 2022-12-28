<?php
declare(strict_types=1);

/**
 * Seeder class.
 *
 * @package NamelessMC\Seeder
 * @author Tadgh Boyle
 * @version 2.1.0
 * @license MIT
 */
class ProfileFieldsSeeder extends Seeder {

    /**
     * @var string[]
     */
    public array $tables = [
        'nl2_profile_fields',
    ];

    /**
     * @param DB $db
     * @param \Faker\Generator $faker
     *
     * @return void
     */
    protected function run(DB $db, \Faker\Generator $faker): void {
        $this->times(7, function () use ($db, $faker) {
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
