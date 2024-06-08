<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KasetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "jdl_kaset" => $this->jdl_kaset,
            "thn_kaset" => $this->thn_kaset,
            "harga" => $this->harga,
            "status_kaset" => $this->status_kaset,
        ];
    }
}
