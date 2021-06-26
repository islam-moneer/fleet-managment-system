<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Trip;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\SeatResource;
use App\Http\Requests\Api\SeatRequest;
use App\Backend\API\Seat as SeatClass;
use App\Backend\Exceptions\DefaultException;

class SeatController extends Controller
{
    private $seat;

    public function __construct(SeatClass $seat)
    {
        $this->seat = $seat;
    }

    public function available(Trip $trip, City $from, City $to)
    {
        if ($from->order >= $to->order) {
            return response()->json(['error' => __('messages.no_trip_found')], Response::HTTP_NOT_FOUND);
        }

        $seats = $this->seat->availableSeats($trip, $from, $to);

        return SeatResource::collection($seats);
    }

    public function book(SeatRequest $request)
    {
        try {
            $this->seat->bookSeat($request);
        } catch(DefaultException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }

        return response()->json(['message' => __('messages.seat_reserved')], Response::HTTP_CREATED);
    }
}
