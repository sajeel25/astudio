<?php

namespace App\Services;

use App\Models\Project;

class ProjectService
{

    public function getProjectQuery()
    {
        return Project::with('user', 'attributes.attribute:id,name');
    }

    public function getProjectFilterQuery($query, $request)
    {
        if ($request->has('filters')) {
            foreach ($request->filters as $field => $value) {
                if (in_array($field, ['name', 'status'])) {
                    $query->where($field, 'LIKE', "%{$value}%");
                } else {
                    preg_match('/^(<=?|>=?)/', $value, $matches);
                    $operator = $matches[0] ?? '=';
                    $cleanValue = preg_replace('/^(<=?|>=?)/', '', $value);

                    $query->whereHas('attributes', function ($q) use ($field, $operator, $cleanValue) {
                        $q->whereHas('attribute', fn($subQuery) => $subQuery->where('slug', $field))
                          ->whereRaw('LOWER(value) ' . $operator . ' ?', [strtolower($cleanValue)]);
                    });
                }
            }
        }
    }

    public function assignUsersToProject(Project $project, array $userIds)
    {
        $project->user()->syncWithoutDetaching($userIds);

        return true;
    }

    public function saveAttributeValue(Project $project, array $attributes): object
    {
        foreach ($attributes as $attr)
        {
            $project->attributes()->updateOrCreate(
                ['attribute_id' => $attr['id']],
                ['value' => $attr['value']]
            );
        }

        return $project->attributes;

    }
}
