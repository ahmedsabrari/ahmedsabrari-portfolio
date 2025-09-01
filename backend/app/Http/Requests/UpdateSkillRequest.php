<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSkillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255|unique:skills,name,' . $skillId,
            'category' => 'required|string|max:255',
            'level' => 'nullable|integer|min:0|max:100',
            'color' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:100',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Skill name is required',
            'name.unique' => 'Skill name already exists',
            'category.required' => 'Skill rating required',
            'level.min' => 'The level must be between 0 and 100.',
            'level.max' => 'The level must be between 0 and 100.',
        ];
    }
}
