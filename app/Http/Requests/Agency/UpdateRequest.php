<?php

namespace App\Http\Requests\Agency;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $agency = request('agency');

        return [
            'name' => ['required', 'unique:agencies,name,' . $agency . ',id', 'string', 'max:50'],
            'code' => ['required', 'unique:agencies,code,' . $agency . ',id', 'numeric', 'max_digits:4'],
        ];
    }
}
