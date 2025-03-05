<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name'  => 'Department',
                'type'  => 'select',
                'options' => serialize(['IT', 'HR', 'Finance', 'Marketing', 'Operations']),
            ],
            [
                'name'  => 'Priority',
                'type'  => 'select',
                'options' => serialize(['Low', 'Medium', 'High', 'Critical']),
            ],
            [
                'name'  => 'Start Date',
                'type'  => 'date',
                'options' => null,
            ],
            [
                'name'  => 'End Date',
                'type'  => 'date',
                'options' => null,
            ],
        ];

        foreach ($attributes as $attribute) {
            Attribute::updateOrCreate(['name' => $attribute['name']], $attribute);
        }
    }
}
