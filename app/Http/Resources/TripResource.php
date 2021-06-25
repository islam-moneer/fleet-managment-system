<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        dd($this);
        return [
            'id' => $this->id,
            'from' => $this->cityFrom->name,
            'to' => $this->cityTo->name,
            'launch' => Carbon::make($this->launch)->format('d-m-Y H:i')
        ];
    }
}
