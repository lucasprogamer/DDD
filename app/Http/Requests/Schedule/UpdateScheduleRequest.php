<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
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
        return [
            'title' => 'string|max:40',
            'description' => 'string|max:255',
            'starts_at' => 'date_format:Y-m-d H:i:s',
            'ends_at' => 'date_format:Y-m-d H:i:s|after:starts_at|nullable'
        ];
    }
}
