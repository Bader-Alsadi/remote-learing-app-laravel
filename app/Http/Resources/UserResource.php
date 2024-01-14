<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = array(
            "id" => $this->id,
            "name" => $this->name[app()->getLocale()],
            "email" => $this->email,
            "token" => $this->token,
            "role_type" => $this->role_type,
        );
        if ($this->role_type == "Student") {
            $deparment = $this->student->department;
            $user["student_id"] = $this->student->id;
            $user["deparment_id"] = $deparment->id;
            $user["department"] = $deparment->department->name;
            $user["level"] = $deparment->Level;
            $user["semester"] = $deparment->semester;
        }
        return $user;
        // return array_merge(parent::toArray($request), ['name' => $this->name[app()->getLocale()]]);
    }
}
