<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // إضافة هذا السطر
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('projects')->insert([
            [
                'title' => 'Project 1',
                'slug' => Str::slug('Project 1'),
                'short_description' => 'Short description for project 1.',
                'description' => 'Full description for project 1.',
                'image' => 'image1.jpg',
                'technologies' => json_encode(['Laravel', 'Vue.js']),
                'project_url' => 'https://example.com/project1',
                'github_url' => 'https://github.com/example/project1',
                'start_date' => '2023-01-01',
                'end_date' => '2023-06-01',
                'is_published' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Project 2',
                'slug' => Str::slug('Project 2'),
                'short_description' => 'Short description for project 2.',
                'description' => 'Full description for project 2.',
                'image' => 'image2.jpg',
                'technologies' => json_encode(['React', 'Node.js']),
                'project_url' => 'https://example.com/project2',
                'github_url' => 'https://github.com/example/project2',
                'start_date' => '2023-02-01',
                'end_date' => '2023-07-01',
                'is_published' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Project 3',
                'slug' => Str::slug('Project 3'),
                'short_description' => 'Short description for project 3.',
                'description' => 'Full description for project 3.',
                'image' => 'image3.jpg',
                'technologies' => json_encode(['PHP', 'MySQL']),
                'project_url' => 'https://example.com/project3',
                'github_url' => 'https://github.com/example/project3',
                'start_date' => '2023-03-01',
                'end_date' => '2023-08-01',
                'is_published' => false,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
