<?php

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Plan::class, function (Faker\Generator $faker) {
    return [
        'braintree_id' => str_random(10),
        'price' => random_int(1, 1000),
        'active' => false
    ];
});
