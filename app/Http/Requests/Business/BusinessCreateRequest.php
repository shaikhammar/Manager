<?php

namespace App\Http\Requests\Business;
use App\Models\Business;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BusinessCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'string|max:255',
            'country_id' => 'required|exists:countries,id',
            'currency_id' => 'required|exists:currencies,id',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $code = $this->input('code');

            if ($code) {
                // Check if the exact code (case insensitive) is already taken
                $exists = Business::where('code', $code)->exists();

                if ($exists) {
                     // Generate a suggestion based on the Name and the desired Code
                     // If desired Code is "TPT" and it exists, this returns "TPT1"
                     $suggestion = Business::generateUniqueCode($this->input('name', ''), $code);
                     
                     $validator->errors()->add(
                         'code', 
                         "The code '{$code}' is already in use. Did you mean '{$suggestion}'?"
                     );
                }
            }
        });
    }
}
