<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimesheetRequest;
use App\Models\TimeSheet;
use App\Services\TimeSheetService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeSheetController extends Controller
{
    protected $timesheetService;

    public function __construct(TimeSheetService $timesheetService)
    {
        $this->timesheetService = $timesheetService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try
        {
            $query = $this->timesheetService->getTimeSheetQuery();

            $timesheets = $request->has('paginate') ? $query->paginate($request->paginate) : $query->get();

            return response()->json(['status' => 200, 'message' => "Timesheet listing successful", 'data' => $timesheets], 200);
        }
        catch(Exception $ex)
        {
            return response()->json(["status" => 500, "message" => $ex->getMessage()], 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TimesheetRequest $timesheetRequest)
    {
        try
        {
            $validated = $timesheetRequest->validated();

            $timesheet = Auth::user()->timesheet()->create($validated);

            return response()->json(['status' => 200, 'message' => "Timesheet created successfully", 'data' => $timesheet], 200);

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
        $timesheet = $this->timesheetService->getTimeSheetQuery()->findOrFail($id);

        return response()->json(['status' => 200, 'message' => "Timesheet details successful", 'data' => $timesheet], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TimesheetRequest $timesheetRequest, TimeSheet $timesheet)
    {
        try
        {
            $validated = $timesheetRequest->validated();

            $timesheet->update($validated);

            return response()->json(['status' => 200, 'message' => "Timesheet updated successfully", 'data' => $timesheet->load('project:id,name')], 200);

        }
        catch(Exception $ex)
        {
            return response()->json(["status" => 500, "message" => $ex->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timesheet $timesheet)
    {
        try
        {
            $timesheet->delete();

            return response()->json(['status' => 200, 'message' => "Timesheet deleted successfully"], 200);
        }
        catch(Exception $ex)
        {
            return response()->json(["status" => 500, "message" => $ex->getMessage()], 500);
        }
    }
}
