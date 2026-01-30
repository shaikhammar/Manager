<?php

namespace App\Http\Requests;

use App\Models\Account;
use App\Service\Business\BusinessManager;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreAccountRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->parent_id) {
            $parent = Account::find($this->parent_id);
            if ($parent) {
                $this->merge([
                    'type' => $parent->type,
                ]);
            }
        }
    }
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
            "name" => "required",
            "code" => [
                "required",
                function (string $attribute, mixed $value, \Closure $fail) {
                    $existing = Account::where('code', $value)
                        ->where('business_id', app(BusinessManager::class)->getBusinessId())
                        ->first();

                    if ($existing) {
                        $name = e($existing->name);
                        $fail("This account code is already used for <strong>{$name}</strong> please use another code.");
                    }
                }
            ],
            "type" => "required",
            "parent_id" => "nullable",
            "is_selectable" => "required",
        ];
    }

}
