<?php

namespace App\Backend\API;

use App\Models\City;
use App\Models\Trip;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Builder;
use App\Backend\Repositories\SeatRepository;
use App\Backend\Exceptions\DefaultException;

class Seat
{
    private $seatRepo;

    public function __construct(SeatRepository $seatRepo)
    {
        $this->seatRepo = $seatRepo;
    }

    public function availableSeats(Trip $trip, City $from, City $to)
    {
        return $trip->seats()->whereDoesntHave('users', function (Builder $query) use ($from, $to){
            $query->where('from_order', '<=', $from->order)
                ->where('to_order', '>=', $to->order)
                ->orWhere('from_order', '<', $to->order)
                ->Where('to_order', '>', $from->order);
        })->get();
    }


    public function bookSeat($request)
    {
        $cityFrom = City::find($request->from);
        $cityTo = City::find($request->to);

        if (!$seat = $this->availableSeatByUnique($request->unique_id, $cityFrom, $cityTo)) {
            throw new DefaultException(__('messages.seat_taken'), Response::HTTP_FORBIDDEN);
        }

        // TODO Calculate cost by increment costs of the cities user pass -- later

        $cityFrom = City::find($request->from);
        $cityTo = City::find($request->to);

        $this->seatRepo->createUserSeat($seat, $cityFrom, $cityTo);
    }

    protected function availableSeatByUnique($unique, City $from, City $to)
    {
        return \App\Models\Seat::whereUniqueId($unique)
            ->whereDoesntHave('users', function (Builder $query) use ($from, $to){
                $query->where('from_order', '<=', $from->order)
                    ->where('to_order', '>=', $to->order)
                    ->orWhere('from_order', '<', $to->order)
                    ->Where('to_order', '>', $from->order);
            })
            ->first();
    }
}