<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\UpdateSkillRequest;
use App\Http\Resources\SkillResource;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SkillController extends Controller
{
    // GET all skills
    public function index(Request $request): JsonResponse
    {
        try{
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
            $sortField = $request->get('sort_by', 'sort_order');
            $sortDirection = $request->get('sort_dir', 'asc');

            $query->orderBy($sortField, $sortDirection)->orderBy('name');
            // Paginate results
            $perPage = $request->get('per_page', 10);
            $skills = $query->paginate($perPage);
            
            return response()->json([
                'data' => SkillResource::collection($skills),
                'meta' => [
                    'current_page' => $skills->currentPage(),
                    'total_pages' => $skills->lastPage(),
                    'total_items' => $skills->total(),
                    'per_page' => $skills->perPage(),
                ],
            ]);
        } catch (\Exception $e){
            Log::error('Skill index error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to fetch skills',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    // POST create skill
    public function store(StoreSkillRequest $request): JsonResponse
    {
        try{
            $this->authorize('create', Skill::class);

            $validated = $request->validated();
            $skill = Skill::create($validated);

            Log::info('Skill created successfully', ['skill_id' => $skill->id]);

            return response()->json([
                'message' => 'Skill created successfully',
                'data' => new SkillResource($skill)
            ], 201);
        } catch (\Exception $e){
            Log::error('Skill creation error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to create skill',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    // GET single skill
    public function show($id): JsonResponse
    {
        try{
            $skill = Skill::findOrFail($id);
            $this->authorize('view', $skill);

            return response()->json([
                'data' => new SkillResource($skill)
            ]);
        } catch (\Exception $e){
            Log::error('Skill show error', ['error' => $e->getMessage(), 'skill_id' => $id]);
            return response()->json([
                'message' => 'Skill not found',
                'error' => config('app.debug') ? $e->getMessage() : 'Not found'
            ], 404);
        }
    }

    // PUT update skill
    public function update(UpdateSkillRequest $request, $id): JsonResponse
    {
        try{
            $skill = Skill::findOrFail($id);
            $this->authorize('update', $skill);

            $skill->update($request->validated());
            Log::info('Skill updated successfully', ['skill_id' => $skill->id]);

            return response()->json([
                'message' => 'Skill updated successfully',
                'data' => new SkillResource($skill)
            ]);
        } catch (\Exception $e){
            Log::error('Skill update error', ['error' => $e->getMessage(), 'skill_id' => $id]);
            return response()->json([
                'message' => 'Failed to update skill',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    // DELETE skill
    public function destroy($id): JsonResponse
    {
        try {
            $skill = Skill::findOrFail($id);
            $this->authorize('delete', $skill);
            
            $skill->delete();
            
            Log::info('Skill deleted successfully', ['skill_id' => $id]);
            
            return response()->json([
                'message' => 'Skill deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Skill destroy error', ['error' => $e->getMessage(), 'skill_id' => $id]);
            return response()->json([
                'message' => 'Failed to delete skill',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
    
    // GET skills by category
    public function byCategory($category): JsonResponse
    {
        try {
            $skills = Skill::where('category', $category)
                         ->orderBy('sort_order')
                         ->orderBy('name')
                         ->get();
                         
            return response()->json([
                'data' => SkillResource::collection($skills),
                'meta' => [
                    'category' => $category,
                    'count' => $skills->count()
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Skills by category error', ['error' => $e->getMessage(), 'category' => $category]);
            return response()->json([
                'message' => 'Failed to fetch skills by category',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}