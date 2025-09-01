<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
        $projectId = $this->route('id') ?? $this->route('project');
        return [
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:projects,slug,' . $projectId,
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string|max:100',
            'project_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }
    public function messages(): array
    {
        return [
            'slug.unique' => 'The short link already exists.',
            'image.max' => 'Image size must not exceed 2MB.',
            'technologies.*.string' => 'Techniques should be textual.',
            'end_date.after_or_equal' => 'End date must be later than or equal to start date.',
        ];
    }
    protected function prepareForValidation()
    {
        if ($this->has('title') && !$this->has('slug')) {
            $this->merge([
                'slug' => Str::slug($this->title)
            ]);
        }
    }
}
