<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SkillController extends Controller
{
    // GET all skills
    public function index(Request $request): JsonResponse
    {
        $query = Skill::query();
        
        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter by featured status
        if ($request->has('featured')) {
            $query->where('is_featured', $request->boolean('featured'));
        }
        
        // Sort results
        $query->orderBy('sort_order')->orderBy('name');
        
        $skills = $query->get();
        
        return response()->json($skills);
    }

    // POST create skill
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:skills,name',
            'category' => 'nullable|string|max:255',
            'level' => 'nullable|integer|min:0|max:100',
            'color' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:100',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $skill = Skill::create($data);

        return response()->json($skill, 201);
    }

    // GET single skill
    public function show($id): JsonResponse
    {
        $skill = Skill::findOrFail($id);
        return response()->json($skill);
    }

    // PUT update skill
    public function update(Request $request, $id): JsonResponse
    {
        $skill = Skill::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:skills,name,' . $skill->id,
            'category' => 'nullable|string|max:255',
            'level' => 'nullable|integer|min:0|max:100',
            'color' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:100',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $skill->update($data);

        return response()->json($skill);
    }

    // DELETE skill
    public function destroy($id): JsonResponse
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return response()->json(['message' => 'Skill deleted successfully']);
    }
    
    // GET skills by category
    public function byCategory($category): JsonResponse
    {
        $skills = Skill::where('category', $category)
                     ->orderBy('sort_order')
                     ->orderBy('name')
                     ->get();
                     
        return response()->json($skills);
    }
}