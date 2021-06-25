<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    protected $trip;
    protected $seats;
    protected $cities;
    protected $from;
    protected $to;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(
            User::factory()->create()
        );
        $this->createTripWithSeats();
    }

    protected function createTripWithSeats()
    {
        $this->cities = createCities(5);
        $citiesIDs = collect($this->cities)->sortBy('order')->pluck('id');

        $this->from = $citiesIDs[0];
        $this->to = array_values(array_slice($citiesIDs->toArray(), -1))[0];

        list($this->trip, $this->seats) = createTripWithSeats($this->from, $this->to);

    }

    public function test_empty_trip_has_available_seats()
    {
        $this->get("/api/available-seats/{$this->trip->id}/{$this->from}/{$this->to}", [
            'Accept' => 'application/json'
        ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'trip_id',
                        'unique_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }


}
