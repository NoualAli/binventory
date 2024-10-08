<?php

namespace App\Http\Requests\Equipment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return can('platform.equipments.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'serial_number' => ['required', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'agency_id' => ['required', 'exists:agencies,id'],
            'entered_at' => ['required', 'date_format:Y-m-d H:i'],
            'repair' => ['required', 'boolean'],
            'install_ad' => ['required', 'boolean'],
            'input_discharge.*' => ['nullable', 'array'],
            'input_discharge.*' => ['exists:attachments,id'],
        ];
        if (!Auth::user()->inRole('technicien')) {
            $riles['technicians'] = ['required', 'array'];
            $rules['technicians.*'] = ['required', 'exists:users,id'];
        }
        return $rules;
    }
}
