<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class InstructorResource extends JsonResource
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
        $subjects = [];
        foreach ($this->subjects as $item) {
            array_push($subjects, array(
                "id" => $item->id,
                "name" => $item->subject->name,
                "description" => $item->subject->description,
                "houre" => $item->subject->houre,
                "grade" => $item->subject->grade,
                "year" => $item->year,
                "image" => $item->subject->image,
                "scientific_method" => $item->scientific_method,
                "department" => array(
                    "id" => $item->deparmentdetils->department_id,
                    "name" => $item->deparmentdetils->department->name,
                    "level" => $item->deparmentdetils->Level,
                    "semaster" => array(
                        "name" => $item->deparmentdetils->semester,
                        "strat_date" => $item->deparmentdetils->strat_date,
                        "end_date" => $item->deparmentdetils->end_date
                    ),
                )

            ));
        }
        return $subjects;
    }
}
