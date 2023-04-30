<?php

class MinecraftPlaceholderSeeder extends Seeder {

    public array $tables = [
        'nl2_placeholders_settings',
    ];

    protected function run(DB $db, \Faker\Generator $faker): void {
        $db->query('UPDATE nl2_settings SET value = ? WHERE name = ?', [
            1, 'placeholders',
        ]);

        $servers = $db->get('mc_servers', ['id', '<>', 0])->results();

        $this->times(5, function() use ($db, $faker, $servers) {
            $name = str_replace(' ', '_', $faker->words(2, true));

            if ($faker->boolean) {
                $friendly_name = $name;
                /** @phpstan-ignore-next-line Bad */
            } else if ($faker->boolean) {
                $friendly_name = str_replace('_', ' ', $name);
            } else {
                $friendly_name = $faker->words(2, true);
            }

            $db->insert('placeholders_settings', [
                'server_id' => $faker->randomElement($servers)->id,
                'name' => $name,
                'friendly_name' => $friendly_name,
                'show_on_profile' => $faker->boolean(75) ? 1 : 0,
                'show_on_forum' => $faker->boolean(75) ? 1 : 0,
                'leaderboard' => true,
                'leaderboard_title' => $friendly_name . ' leaderboard',
                'leaderboard_sort' => $faker->randomElement(['DESC', 'ASC']),
            ]);
        });
    }
}
