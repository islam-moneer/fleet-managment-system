<?php

use App\Models\Trip;
use App\Models\City;

if (!function_exists('createTripWithSeats',)) {

    function createTripWithSeats($from, $to): array
    {
        $trip = Trip::factory()->hasSeats(12)->create([
            'from' => $from,
            'to' => $to
        ]);
        return [$trip, $trip->seats()];
    }
}

if (!function_exists('createCities',)) {

    function createCities($count) {
        return City::factory()->count($count)->create();
    }
}