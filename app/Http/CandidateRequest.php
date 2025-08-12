<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CandidateRequest extends FormRequest
{
    /**
     * West African countries for validation
     */
    private const WEST_AFRICAN_COUNTRIES = [
        'Benin', 'Burkina Faso', 'Cape Verde', "Côte d'Ivoire", 'Gambia',
        'Ghana', 'Guinea', 'Guinea-Bissau', 'Liberia', 'Mali',
        'Niger', 'Nigeria', 'Senegal', 'Sierra Leone', 'Togo'
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $candidateId = $this->route('candidate')?->id;

        return [
            'registration_number' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[A-Z0-9\/\-]+$/', // Allow alphanumeric, slashes, and hyphens
                Rule::unique('candidates')->ignore($candidateId)
            ],
            'surname' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s\-\'\.]+$/' // Allow letters, spaces, hyphens, apostrophes, dots
            ],
            'other_name' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s\-\'\.]+$/'
            ],
            'maiden_name' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s\-\'\.]+$/'
            ],
            'entry_mode' => [
                'nullable',
                'string',
                'max:255',
                'in:Direct Entry,UTME,Transfer,Postgraduate'
            ],
            'country' => [
                'required',
                'string',
                Rule::in(self::WEST_AFRICAN_COUNTRIES)
            ],
            'dob' => [
                'required',
                'date',
                'before:today',
                'after:1900-01-01' // Reasonable birth date range
            ],
            'gender' => [
                'nullable',
                'string',
                'in:Male,Female'
            ],
            'nationality' => [
                'required',
                'string',
                Rule::in(self::WEST_AFRICAN_COUNTRIES)
            ],
            'fellowship_type' => [
                'nullable',
                'string',
                'max:255'
            ],
            'faculty_id' => [
                'nullable',
                'integer',
                'exists:faculty,id'
            ],
            'sub_speciality' => [
                'nullable',
                'string',
                'max:255'
            ],
            'nysc_discharge_or_exemption' => [
                'nullable',
                'string',
                'max:255',
                'in:Discharge Certificate,Exemption Certificate,Not Applicable'
            ],
            'accredited_training_program' => [
                'nullable',
                'string',
                'max:255'
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('candidates')->ignore($candidateId)
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[\+]?[0-9\-\(\)\s]+$/' // Allow international format
            ],
            'address' => [
                'nullable',
                'string',
                'max:500'
            ],
            'postal_address' => [
                'nullable',
                'string',
                'max:500'
            ]
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'registration_number.unique' => 'This registration number is already taken.',
            'registration_number.regex' => 'Registration number can only contain letters, numbers, slashes, and hyphens.',
            'surname.required' => 'Surname is required.',
            'surname.regex' => 'Surname can only contain letters, spaces, hyphens, apostrophes, and dots.',
            'other_name.regex' => 'Other name can only contain letters, spaces, hyphens, apostrophes, and dots.',
            'maiden_name.regex' => 'Maiden name can only contain letters, spaces, hyphens, apostrophes, and dots.',
            'country.required' => 'Country is required.',
            'country.in' => 'Please select a valid West African country.',
            'dob.required' => 'Date of birth is required.',
            'dob.before' => 'Date of birth must be before today.',
            'dob.after' => 'Please enter a valid date of birth.',
            'gender.in' => 'Gender must be either Male or Female.',
            'nationality.required' => 'Nationality is required.',
            'nationality.in' => 'Please select a valid West African nationality.',
            'faculty_id.exists' => 'Please select a valid faculty.',
            'entry_mode.in' => 'Please select a valid entry mode.',
            'nysc_discharge_or_exemption.in' => 'Please select a valid NYSC status.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.regex' => 'Please enter a valid phone number.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'registration_number' => 'registration number',
            'other_name' => 'other name',
            'maiden_name' => 'maiden name',
            'entry_mode' => 'entry mode',
            'dob' => 'date of birth',
            'faculty_id' => 'faculty',
            'sub_speciality' => 'sub-speciality',
            'nysc_discharge_or_exemption' => 'NYSC status',
            'accredited_training_program' => 'accredited training program',
            'postal_address' => 'postal address',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean and format data before validation
        $this->merge([
            'registration_number' => $this->cleanString($this->registration_number),
            'surname' => $this->cleanString($this->surname),
            'other_name' => $this->cleanString($this->other_name),
            'maiden_name' => $this->cleanString($this->maiden_name),
            'email' => $this->cleanEmail($this->email),
            'phone' => $this->cleanString($this->phone),
        ]);
    }

    /**
     * Clean string input
     */
    private function cleanString(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        return trim(preg_replace('/\s+/', ' ', $value));
    }

    /**
     * Clean email input
     */
    private function cleanEmail(?string $email): ?string
    {
        if (empty($email)) {
            return null;
        }

        return strtolower(trim($email));
    }

    /**
     * Get the West African countries list
     */
    public static function getWestAfricanCountries(): array
    {
        return self::WEST_AFRICAN_COUNTRIES;
    }
}

