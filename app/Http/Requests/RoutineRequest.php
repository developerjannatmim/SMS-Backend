<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoutineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
   * Get the error messages for the defined validation rules.
   *
   * @return array
   */
  public function messages(): array
  {
    return [];
  }
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'day' => 'required',
            'routine_creator' => 'required',
            'starting_hour' => 'required',
            'starting_minute' => 'required',
            'ending_hour' => 'required',
            'ending_minute' => 'required',
            'class_id' => 'required',
            'subject_id' => 'required',
            'section_id' => 'required',
            'room_id' => 'required'
        ];
    }
}
