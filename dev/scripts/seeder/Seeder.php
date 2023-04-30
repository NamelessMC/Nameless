<?php

abstract class Seeder {

    public array $tables;

    public function seed(DB $db, \Faker\Generator $faker): void {
        print '🌱 Seeding ' . get_class($this) . '...' . PHP_EOL;
        $start = microtime(true);

        $this->run($db, $faker);

        print '🌲 ' . get_class($this) . ' complete! (' . round((microtime(true) - $start), 2) . 's)' . PHP_EOL;
    }

    abstract protected function run(DB $db, \Faker\Generator $faker): void;

    protected function times(int $count, Closure $factory): void {
        for ($i = 0; $i < $count; $i++) {
            $factory();
        }
    }

    protected function since(int $past, \Faker\Generator $faker): DateTime {
        // convert the epoch time to a DateTime object
        $date = new DateTime();
        $date->setTimestamp($past);
        return $date->modify('+' .
            $faker->numberBetween(1, 10) . ' ' . $faker->randomElement(['days', 'months'])
        );
    }
}
