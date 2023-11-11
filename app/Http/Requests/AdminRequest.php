<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
      'name' => 'required',
      'email' => 'required|email',
      'password' => 'required|min:6',
      'photo' => 'required|image|mimes:jpg,jpeg,png,svg|max:2048',
      'gender' => 'required',
      'blood_group' => 'required',
      'birthday' => 'required',
      'phone' => 'required',
      'address' => 'required',
    ];
  }
}
