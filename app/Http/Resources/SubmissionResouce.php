<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubmissionResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        // return parent::toArray($request);
        $submissions = [];
        foreach ($this->submissions as $item) {
            array_push($submissions, array(
                "id" => $item->id,
                "student_id" => $item->student_id,
                "description" => $item->description,
                "assingment_id" => $item->assingment_id,
                "path" => $item->path,
                "submissions_date"=>$item->submissions_date,
                "grade" => $item->grade,
                "state" => $item->state,
                "student_name" => $item->student->user->name[app()->getLocale()]
            ));
        }

        return  $submissions;
    }
}
