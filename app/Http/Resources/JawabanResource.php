<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JawabanResource extends JsonResource
{
    public $message;
    public $resource;
    public $status;

    public function __construct($status, $message, $resource){
        parent::__construct($resource);
        $this->message = $message;
        $this->status = $status;
    }
    public function toArray(Request $request): array
    {
        return [
            'status'    => true,
            'message'   => $this->message,
            'data'      => $this->resource
        ];
    }
}
