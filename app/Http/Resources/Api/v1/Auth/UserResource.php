<?php

namespace App\Http\Resources\Api\v1\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attr = [];

        foreach ($this->resource as $attribute) {
            $attr[str_replace("custom:", "", $attribute['Name'])] = $attribute['Value'];
        }

        $attr['user_id']     = request()->user_id;
        $attr['roles']       = request()->roles;
        $attr['permissions'] = request()->permissions;
       
        return $attr;
    }
}
