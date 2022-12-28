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
class MinecraftServerSeeder extends Seeder {

    /**
     * @var string[]
     */
    public array $tables = [
        'nl2_mc_servers',
    ];

    /**
     * These are randomly picked servers, not endorsements/opinionated.
     *
     * @var string[]
     */
    private array $_mc_server_ips = [
        'hypixel.net',
        'us.mineplex.com',
        'brawl.com',
        'mc-gtm.net',
        'server.minewind.com',
    ];

    /**
     * @param DB $db
     * @param \Faker\Generator $faker
     *
     * @return void
     */
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
