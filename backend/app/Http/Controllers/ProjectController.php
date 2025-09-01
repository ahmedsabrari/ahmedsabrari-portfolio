<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Str;

class ProjectController extends Controller
{
    // GET all projects (with optional filters)
    public function index(Request $request): JsonResponse
    {
        try{
            $query = Project::query();
        
            // Filter by published status
            if ($request->has('published')) {
                $query->where('is_published', $request->boolean('published'));
            }
            
            // Filter by category if needed
            if ($request->has('category')) {
                // Assuming you have a category field or relation
                // $query->where('category', $request->category);
            }
            // Search by title
            if ($request->has('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }
            
            // Sort results
            $sortField = $request->get('sort_by', 'sort_order');
            $sortDirection = $request->get('sort_dir', 'asc');
            
            $query->orderBy($sortField, $sortDirection);
            
            // Paginate results
            $perPage = $request->get('per_page', 10);
            $projects = $query->paginate($perPage);
            
            return response()->json([
                'data' => ProjectResource::collection($projects),
                'meta' => [
                    'current_page' => $projects->currentPage(),
                    'total_pages' => $projects->lastPage(),
                    'total_items' => $projects->total(),
                    'per_page' => $projects->perPage(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Project index error: ', ['error' => $e->getMessage()]);
            return response()->json([
                'message'=> 'Failed to retrieve projects',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
        
    }

    // POST create project
    public function store(StoreProjectRequest  $request): JsonResponse
    {
        try{
            $this->authorize('create', Project::class);
            $validated = $request->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('projects', 'public');
                $validated['image'] = $imagePath;
            }

            // Convert technologies array to JSON
            if (isset($validated['technologies'])) {
                $validated['technologies'] = json_encode($validated['technologies']);
            }

            $project = Project::create($validated);

            Log::info('Project created successfully', ['project_id' => $project->id]);

            return response()->json([
                'message' => 'Project created successfully',
                'data' => new ProjectResource($project)
            ], 201);
        } catch (\Exception $e){
            Log::error('Project creation error: ', ['error' => $e->getMessage()]);
            return response()->json([
                'message'=> 'Project creation failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    // GET single project
    public function show($id): JsonResponse
    {
        try{
            $project = Project::findOrFail($id);
            $this->authorize('view', $project);

            return response()->json([
                'data'=> new ProjectResource($project)
            ]);
        } catch (\Exception $e){
            Log::error('Project retrieval error: ', ['error' => $e->getMessage(), 'project_id'=>$id]);
            return response()->json([
                'message'=> 'Project not found',
                'error' => config('app.debug') ? $e->getMessage() : 'Not Found'
            ], 404);
        }
    }

    // GET project by slug
    public function showBySlug($slug): JsonResponse
    {
        try{
            $project = Project::where('slug', $slug)->firstOrFail();
            $this->authorize('view', $project);

            return response()->json([
                'data'=> new ProjectResource($project)
            ]);
        } catch (\Exception $e){
            Log::error('Project showBySlug error', ['error' => $e->getMessage(), 'slug'=>$slug]);
            return response()->json([
                'message'=> 'Project not found',
                'error' => config('app.debug') ? $e->getMessage() : 'Not Found'
            ], 404);
        }
    }

    // PUT update project
    public function update(UpdateProjectRequest $request, $id): JsonResponse
    {
        try{
            $project = Project::findOrFail($id);
            $this->authorize('update', $project);
            
            $validated = $request->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($project->image) {
                    Storage::disk('public')->delete($project->image);
                }
                
                $imagePath = $request->file('image')->store('projects', 'public');
                $validated['image'] = $imagePath;
            }

            // Convert technologies array to JSON
            if (isset($validated['technologies'])) {
                $validated['technologies'] = json_encode($validated['technologies']);
            }

            $project->update($validated);
            Log::info('Project updated successfully', ['project_id' => $project->id]);

            return response()->json([
                'message' => 'Project updated successfully',
                'data' => new ProjectResource($project)
            ]);
        } catch (\Exception $e){
            Log::error('Project update error: ', ['error' => $e->getMessage(), 'project_id'=>$id]);
            return response()->json([
                'message'=> 'Project update failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    // DELETE project
    public function destroy($id): JsonResponse
    {
        try{
            $project = Project::findOrFail($id);
            $this->authorize('delete', $project);
            
            // Delete associated image
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            
            $project->delete();

            Log::info('Project deleted successfully', ['project_id' => $id]);

            return response()->json([
                'message' => 'Project deleted successfully'
            ]);
        } catch (\Exception $e){
            Log::error('Project destroy error',['error' => $e->getMessage(), 'project_id'=>$id]);
            return response()->json([
                'message'=> 'Project deletion failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }
}