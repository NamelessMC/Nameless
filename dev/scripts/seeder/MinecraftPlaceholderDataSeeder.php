<?php

class MinecraftPlaceholderDataSeeder extends Seeder {

    public array $tables = [
        'nl2_users_placeholders',
    ];

    protected function run(DB $db, \Faker\Generator $faker): void {
        $placeholders = $db->get('placeholders_settings', ['server_id', '<>', 0])->results();
        $users = $db->get('users', ['id', '<>', 0])->results();
        $saved = [];
        $user_uuids = [];

        $this->times(1000, function() use ($db, $faker, $placeholders, $users, &$saved, &$user_uuids) {
            $placeholder = $faker->randomElement($placeholders);
            $user = $faker->randomElement($users);

            if (!array_key_exists($user->id, $user_uuids)) {
                $uuid = hex2bin($db->query('SELECT identifier FROM nl2_users_integrations WHERE user_id = ?', [$user->id])->first()->identifier);
                $user_uuids[$user->id] = $uuid;
            } else {
                $uuid = $user_uuids[$user->id];
            }

            if (in_array($placeholder->server_id.$uuid.$placeholder->name, $saved)) {
                return;
            }

            $db->insert('users_placeholders', [
                'server_id' => $placeholder->server_id,
                'uuid' => $uuid,
                'name' => $placeholder->name,
                'value' => $faker->numberBetween(0, 1000),
                'last_updated' => $this->since($user->joined, $faker)->format('U'),
            ]);

            $saved[] = $placeholder->server_id.$uuid.$placeholder->name;
        });
    }
}
