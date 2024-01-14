<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class showStudentone extends JsonResource
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
        foreach ($this->grades as $grade) {
            array_push($subjects, array(
                "name" => $grade->subject->subject->name,
                "grade" => $grade->subject->subject->grade,
                "student_grade" => $grade->final_mark,
            ));
        }

        $student =  array(
            "id" => $this->id,
            "name" => $this->user->name,
            "email" => $this->user->email,
            "deparmten" => $this->department->department->name,
            "subjects" => $subjects

        );

        return $student;
    }
}
