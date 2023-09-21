<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
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
            'title' => 'required|string|max:40',
            'description' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:users,id',
            'starts_at' => 'required|date_format:Y-m-d H:i:s',
            'ends_at' => 'date_format:Y-m-d H:i:s|after:starts_at|nullable'
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id
        ]);
    }
}
