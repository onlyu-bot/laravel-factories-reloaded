<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Christophrumpel\LaravelFactoriesReloaded\Tests\Models\Group;
use Faker\Generator as Faker;

$factory->define(Group::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'size' => $faker->numberBetween(1, 10),
    ];
});

$factory->state(Group::class, 'some-state', [
    'name' => 'cool',
]);
