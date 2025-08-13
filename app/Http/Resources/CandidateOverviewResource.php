<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CandidateOverviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id'                            => $this->id,
            'registration_number'           => $this->registration_number,
            'cert_number'                   => $this->cert_number,
            'surname'                       => $this->surname,
            'other_name'                    => $this->other_name,
            'maiden_name'                   => $this->maiden_name,
            'change_of_name'                => $this->change_of_name,
            'email'                         => $this->email,
            'phone'                         => $this->phone,
            'address'                       => $this->address,
            'postal_address'                => $this->postal_address,
            'dob'                           => $this->dob,
            'gender'                        => $this->gender,
            'nationality'                   => $this->nationality,
            'fellowship_type'               => $this->fellowship_type,
            'country'                        => $this->country,
            'faculty_id'                    => $this->faculty_id,
            'sub_speciality'                => $this->sub_speciality,
            'full_registration_date'        => $this->full_registration_date,
            'entry_mode'                    => $this->entry_mode,
            'nysc_discharge_or_exemption'   => $this->nysc_discharge_or_exemption,
            'prefered_exam_center'          => $this->prefered_exam_center,
            'accredited_training_program'   => $this->accredited_training_program,
            'post_registration_appointment' => $this->post_registration_appointment,
            'passport'                       => $this->passport,
            // Relationships needed for Overview
            'faculty'                        => $this->faculty ? ['name' => $this->faculty->name] : null,
            'medical_schools'                => $this->medicalSchools->map(fn($school) => ['name' => $school->name]),
            'institutions'                   => $this->institutions->map(fn($inst) => ['name' => $inst->name]),
            'postgraduate_trainings'         => $this->postgraduateTrainings->map(fn($pg) => [
                'field' => $pg->field,
                'post_training_school' => $pg->post_training_school
            ]),
        ];
    }
}

