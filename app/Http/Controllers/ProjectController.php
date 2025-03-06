<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;
use Exception;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try
        {
            $query = $this->projectService->getProjectQuery();

            $this->projectService->getProjectFilterQuery($query,$request);

            $query->orderBy('created_at', 'desc');

            $projects = $request->has('paginate') ? $query->paginate($request->paginate) : $query->get();

            return response()->json(['status' => 200, 'message' => "Projects listing successful", 'data' => $projects], 200);
        }
        catch(Exception $ex)
        {
            return response()->json(["status" => 500, "message" => $ex->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $projectRequest)
    {
        try
        {
            $validated = $projectRequest->validated();

            $project = Project::create($validated);

            if($projectRequest->has('user_id'))
            {
                $userId = $projectRequest->input('user_id');

                $this->projectService->assignUsersToProject($project, $userId);
            }

            if(isset($validated['attributes']) && is_array($validated['attributes']))
            {
                $this->projectService->saveAttributeValue($project, $validated['attributes']);
            }

            return response()->json(['status' => 200, 'message' => "Project created successfully", 'data' => $project], 200);
        }
        catch(Exception $ex)
        {
            return response()->json(["status" => 500, "message" => $ex->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = $this->projectService->getProjectQuery()->findOrFail($id);

        return response()->json(['status' => 200, 'message' => "Project details successful", 'data' => $project], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $projectRequest, Project $project)
    {
        try
        {
            $validated = $projectRequest->validated();

            $project->update($validated);

            if($projectRequest->has('user_id'))
            {
                $userId = $projectRequest->input('user_id');

                $this->projectService->assignUsersToProject($project, $userId);
            }

            if(isset($validated['attributes']) && is_array($validated['attributes']))
            {
                $this->projectService->saveAttributeValue($project, $validated['attributes']);
            }

            return response()->json(['status' => 200, 'message' => "Project updated successfully", 'data' => $project->load('user', 'attributes.attribute:id,name')], 200);

        }
        catch(Exception $ex)
        {
            return response()->json(["status" => 500, "message" => $ex->getMessage()], 500);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        try
        {
            $project->delete();

            return response()->json(['status' => 200, 'message' => "Project deleted successfully"], 200);
        }
        catch(Exception $ex)
        {
            return response()->json(["status" => 500, "message" => $ex->getMessage()], 500);
        }
    }
}
