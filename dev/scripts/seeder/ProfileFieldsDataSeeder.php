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
class ProfileFieldsDataSeeder extends Seeder {

    /**
     * @var string[]
     */
    public array $tables = [
        'nl2_users_profile_fields',
    ];

    /**
     * @param DB $db
     * @param \Faker\Generator $faker
     *
     * @return void
     */
    protected function run(DB $db, \Faker\Generator $faker): void {
        $profile_fields = $db->get('profile_fields', ['id', '<>', 0])->results();

        foreach ($db->get('users', ['id', '<>', '0'])->results() as $user) {
            foreach ($profile_fields as $profile_field) {
                if (!$profile_field->required && $faker->boolean(30)) {
                    continue;
                }

                switch ($profile_field->type) {
                    case Fields::TEXT:
                        $value = $faker->text(30);
                        break;
                    case Fields::TEXTAREA:
                        $value = $faker->text(70);
                        break;
                    case Fields::DATE:
                        $value = $faker->date();
                        break;
                }

                $db->insert('users_profile_fields', [
                    'user_id' => $user->id,
                    'field_id' => $profile_field->id,
                    'value' => $value ?? null,
                    'updated' => $this->since($user->joined, $faker)->format('U'),
                ]);
            }
        }
    }
}
