<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SeatResource;
use App\Models\City;
use App\Models\Seat;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function available(Trip $trip, City $from, City $to):
    \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $seats = $trip->seats()->whereDoesnthave('cities', function (Builder $query) use ($from, $to){
            $query->where('city_id', $from->id)
                ->where('city_id', $to->id);
        })->get();
        return SeatResource::collection($seats);
    }
}
