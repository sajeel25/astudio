<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'name'    => 'E-Commerce Platform',
                'status'  => 'Active',
            ],
            [
                'name'    => 'Hospital Management System',
                'status'  => 'Active',
            ],
            [
                'name'    => 'AI Chatbot for Customer Support',
                'status'  => 'Active',
            ],
            [
                'name'    => 'Islamic Finance App',
                'status'  => 'Active',
            ],
            [
                'name'    => 'Smart Traffic Management',
                'status'  => 'Active',
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(['name' => $project['name']], $project);
        }
    }
}
