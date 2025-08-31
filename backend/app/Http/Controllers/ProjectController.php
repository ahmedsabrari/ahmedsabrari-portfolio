<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    // GET all projects (with optional filters)
    public function index(Request $request): JsonResponse
    {
        $query = Project::query();
        
        // Filter by published status
        if ($request->has('published')) {
            $query->where('is_published', $request->boolean('published'));
        }
        
        // Filter by category if needed
        if ($request->has('category')) {
            // Assuming you have a category field or relation
        }
        
        // Sort results
        $query->orderBy('sort_order')->orderBy('created_at', 'desc');
        
        $projects = $query->get();
        
        return response()->json($projects);
    }

    // POST create project
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:projects,slug',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'technologies' => 'nullable|array',
            'project_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('projects', 'public');
            $data['image'] = $imagePath;
        }

        // Convert technologies array to JSON
        if (isset($data['technologies'])) {
            $data['technologies'] = json_encode($data['technologies']);
        }

        $project = Project::create($data);

        return response()->json($project, 201);
    }

    // GET single project
    public function show($id): JsonResponse
    {
        $project = Project::findOrFail($id);
        return response()->json($project);
    }

    // GET project by slug
    public function showBySlug($slug): JsonResponse
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        return response()->json($project);
    }

    // PUT update project
    public function update(Request $request, $id): JsonResponse
    {
        $project = Project::findOrFail($id);
        
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|nullable|string|max:255|unique:projects,slug,' . $project->id,
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'technologies' => 'nullable|array',
            'project_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            
            $imagePath = $request->file('image')->store('projects', 'public');
            $data['image'] = $imagePath;
        }

        // Convert technologies array to JSON
        if (isset($data['technologies'])) {
            $data['technologies'] = json_encode($data['technologies']);
        }

        $project->update($data);

        return response()->json($project);
    }

    // DELETE project
    public function destroy($id): JsonResponse
    {
        $project = Project::findOrFail($id);
        
        // Delete associated image
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}