<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;

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
            $project = Project::updateOrCreate(['name' => $project['name']], $project);

            $users = User::inRandomOrder()->limit(2)->pluck('id');
            $project->user()->sync($users);


            $attributes = Attribute::all();

            // Attach each attribute with a random or default value
            foreach ($attributes as $attribute) {
                $value = $this->getRandomValue($attribute->slug);
                $project->attributes()->updateOrCreate(
                    ['attribute_id' => $attribute->id],
                    ['value' => $value]
                );
            }
        }
    }

    private function getRandomValue($slug)
    {
        return match ($slug) {
            'department'  => ['IT', 'Finance', 'HR'][array_rand(['IT', 'Finance', 'HR'])],
            'priority'    => ['High', 'Medium', 'Low'][array_rand(['High', 'Medium', 'Low'])],
            'start_date'  => now()->subDays(rand(10, 30))->toDateString(),
            'end_date'    => now()->addDays(rand(10, 60))->toDateString(),
            default       => 'N/A',
        };
    }
}
