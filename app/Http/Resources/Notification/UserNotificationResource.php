<?php

namespace App\Http\Resources\Notification;

use App\Facades\Timezone;
use App\Http\Resources\Shapable;
use App\Services\Common\TimezoneService;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\Resources\Traits\CustomCollection;

class UserNotificationResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'notification';

    public static function shape()
    {
        return [
            'id'             => 'string',
            'title'          => 'string',
            'description'    => 'string',
            'read'           => 'boolean',
            'created_at'     => 'string'
        ];
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $read_at = ($this->read_at === null) ? false : true;

        //$this->resource->load(['notification']);

        $data = json_decode($this->resource->data, true);

        return [
            'id'             => $this->resource->id,
            'title'          => $data['title'],
            'description'    => $data['description'],
            'read'           => $read_at,
            'date'     => $this->created_at,
            //'created_at'     => Carbon::parse($this->created_at)->format('Y-m-d H:i:s')
        ];
    }
}
