<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Seat;
use App\Models\Trip;
use App\Http\Controllers\Controller;
use App\Http\Resources\SeatResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\SeatRequest;
use Illuminate\Database\Eloquent\Builder;

class SeatController extends Controller
{
    public function available(Trip $trip, City $from, City $to)
    {
        // check from before to
        if ($from->order >= $to->order) {
            return response()->json(['error' => __('messages.no_trip_found')], Response::HTTP_NOT_FOUND);
        }

        $seats = $trip->seats()->whereDoesntHave('users', function (Builder $query) use ($from, $to){
            $query->where('from_order', '<=', $from->order)
                ->where('to_order', '>=', $to->order)
                ->orWhere('from_order', '<=', $to->order)
                ->Where('to_order', '>=', $from->order)
                ->Where('to_order', '<>', $from->order);

        })->get();
        return SeatResource::collection($seats);
    }

    public function book(SeatRequest $request)
    {
        $seat = Seat::whereUniqueId($request->unique_id)
                ->whereTripId($request->trip_id)
            ->first();

        // TODO Calculate cost by increment costs of the cities user pass -- later

        $cityFrom = City::find($request->from);
        $cityTo = City::find($request->to);

        $seat->users()->attach(Auth::id(), [
            'from' => $request->from,
            'to' => $request->to,
            'cost' => 0,
            'from_order' => $cityFrom->order,
            'to_order' => $cityTo->order,
        ]);

        $citiesUserPass = City::whereBetween('order', [$cityFrom->order, $cityTo->order])->pluck('id')->toArray();

        // Delete last city so the next user can book starting from it
        array_pop($citiesUserPass);
        $seat->cities()->sync($citiesUserPass);

        return response()->json(['message' => __('messages.seat_reserved')], 201);
    }
}
