<?php

class MinecraftServerSeeder extends Seeder {

    public array $tables = [
        'nl2_mc_servers',
    ];

    // Disclaimer: These are randomly picked servers, not endorsements/opinionated.
    private array $_mc_server_ips = [
        'hypixel.net',
        'us.mineplex.com',
        'brawl.com',
        'mc-gtm.net',
        'server.minewind.com',
    ];

    protected function run(DB $db, \Faker\Generator $faker): void {
        $default_id = $faker->randomElement([1, 2, 3, 4, 5]);

        $id = 1;
        $this->times(5, function () use ($db, $faker, $default_id, &$id) {
            $server = $faker->unique()->randomElement($this->_mc_server_ips);
            $db->insert('mc_servers', [
                'ip' => $server,
                'query_ip' => $server,
                'name' => $server,
                'is_default' => $id === $default_id ? 1 : 0,
                'display' => $faker->boolean(70) ? 1 : 0,
            ]);
            $id++;
        });
    }
}
