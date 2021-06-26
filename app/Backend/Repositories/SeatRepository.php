<?php


namespace App\Backend\Repositories;


use App\Models\City;
use App\Models\Seat;
use Illuminate\Support\Facades\Auth;

class SeatRepository
{

    public function createUserSeat(Seat $seat, City $cityFrom, City $cityTo)
    {
        $seat->users()->attach(Auth::id(), [
            'from' => $cityFrom->id,
            'to' => $cityTo->id,
            'cost' => 0,
            'from_order' => $cityFrom->order,
            'to_order' => $cityTo->order,
        ]);
    }
}