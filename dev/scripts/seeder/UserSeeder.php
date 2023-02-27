<?php

class UserSeeder extends Seeder {

    public array $tables = [
        'nl2_users',
        'nl2_users_groups',
        'nl2_users_integrations',
    ];

    public function run(DB $db, \Faker\Generator $faker): void {
        $password = password_hash('password', PASSWORD_BCRYPT, ['cost' => 13]);

        $db->insert('users', [
            'username' => 'admin',
            'nickname' => 'admin',
            'password' => $password,
            'pass_method' => 'default',
            'joined' => date('U'),
            'email' => 'admin@localhost',
            'lastip' => '127.0.0.1',
            'active' => true,
            'last_online' => date('U'),
            'language_id' => $db->get('languages', ['is_default', '=', 1])->first()->id,
            'register_method' => 'nameless',
        ]);
        $user_id = $db->lastId();
        $db->query('INSERT INTO `nl2_users_groups` (`user_id`, `group_id`, `received`, `expire`) VALUES (?, ?, ?, ?)', [
            1,
            2,
            date('U'),
            0,
        ]);
        $db->query(
            'INSERT INTO nl2_users_integrations (user_id, integration_id, identifier, username, verified, date, code) VALUES (?, ?, ?, ?, ?, ?, ?)', [
                $user_id,
                1,
                str_replace('-', '', $faker->unique()->uuid),
                'admin',
                1,
                date('U'),
                null
            ]
        );

        $this->times(USER_COUNT, function () use ($db, $faker, $password) {
            $username = substr($faker->unique()->userName, 0, 20);
            $full_name = substr($faker->unique()->name, 0, 20);
            $active = $faker->boolean(90) ? 1 : 0;
            $joined = $faker->dateTimeBetween('-1 years')->format('U');

            $db->insert('users', [
                'username' => $username,
                'nickname' => $faker->boolean ? $username : $full_name,
                'password' => $password,
                'email' => $faker->email,
                'isbanned' => $faker->boolean(20) ? 1 : 0,
                'lastip' => $faker->ipv4,
                'active' => $active,
                'signature' => $faker->boolean ? $faker->text(500) : null,
                'profile_views' => $faker->numberBetween(0, 500),
                'reputation' => $faker->numberBetween(0, 500),
                'gravatar' => $faker->boolean(20) ? 1 : 0,
                'topic_updates' => $faker->boolean(40) ? 1 : 0,
                'private_profile' => $faker->boolean(40) ? 1 : 0,
                'last_online' => $this->since($joined, $faker)->format('U'),
                'joined' => $joined,
                'user_title' => $faker->boolean(20) ? $faker->text(60) : null,
                'night_mode' => $faker->boolean ? 1 : 0,
                'timezone' => $faker->boolean ? $faker->timezone : 'America/Vancouver',
                'register_method' => 'nameless',
            ]);

            $user_id = $db->lastId();

            $db->insert('users_groups', [
                'user_id' => $user_id,
                'group_id' => $active ? 1 : 4,
                'received' => $this->since($joined, $faker)->format('U'),
                'expire' => 0,
            ]);

            if ($active && $faker->boolean(40)) {
                $db->insert('users_groups', [
                    'user_id' => $user_id,
                    'group_id' => 2,
                    'received' => $this->since($joined, $faker)->format('U'),
                    'expire' => 0,
                ]);
            }

            if ($active && $faker->boolean(40)) {
                $db->insert('users_groups', [
                    'user_id' => $user_id,
                    'group_id' => 3,
                    'received' => $this->since($joined, $faker)->format('U'),
                    'expire' => 0,
                ]);
            }

            $db->query(
                'INSERT INTO nl2_users_integrations (user_id, integration_id, identifier, username, verified, date, code) VALUES (?, ?, ?, ?, ?, ?, ?)', [
                    $user_id,
                    1,
                    str_replace('-', '', $faker->unique()->uuid),
                    $username,
                    1,
                    date('U'),
                    null
                ]
            );
        });
    }
}
