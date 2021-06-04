<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CastMember;
use App\Models\Enums\CastMemberType;
use Faker\Generator as Faker;

$factory->define(CastMember::class, function (Faker $faker) {
    //$types = [CastMemberType::DIRECTOR, CastMemberType::ACTOR];
    return [
        'name' => $faker->name,
        'type' => $faker->numberBetween(1,2)
        //'type' => $faker->$types[array_rand($types)] 
    ];
});
