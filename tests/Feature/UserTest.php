<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    protected $to;
    protected $user;
    protected $trip;
    protected $from;
    protected $seats;
    protected $cities;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs(
            $this->user
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

    public function test_book_a_seat()
    {
        $seat = $this->seats->first();
        $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/book-seat", [
            "trip_id" => $this->trip->id,
            "unique_id" => $seat->unique_id,
            "from" => $this->from,
            "to" => $this->to,
        ])
            ->assertCreated();

        $this->assertDatabaseHas('seat_user', [
            'seat_id' => $seat->id,
            'user_id' => $this->user->id,
            'from' => $this->from,
            'to' => $this->to
        ]);
    }

}
