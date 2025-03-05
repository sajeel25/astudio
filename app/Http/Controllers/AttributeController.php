<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttributeRequest;
use App\Models\Attribute;
use App\Services\AttributeService;
use Exception;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    protected $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try
        {
            $query = $this->attributeService->getAttributeQuery();

            $attributes = $request->has('paginate') ? $query->paginate($request->paginate) : $query->get();

            return response()->json(['status' => 200, 'message' => "Attribute listing successful", 'data' => $attributes], 200);
        }
        catch(Exception $ex)
        {
            return response()->json(["status" => 500, "message" => $ex->getMessage()], 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeRequest $attributeRequest)
    {
        try
        {
            $validated = $attributeRequest->validated();
            if(isset($validated['options']) && is_array($validated['options']))
            {
                $validated['options'] = serialize($validated['options']);
            }
            $attribute = Attribute::create($validated);

            return response()->json(['status' => 200, 'message' => "Attribute created successfully", 'data' => $attribute], 200);

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
        $attribute = $this->attributeService->getAttributeQuery()->findOrFail($id);

        return response()->json(['status' => 200, 'message' => "Attribute details successful", 'data' => $attribute], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeRequest $attributeRequest, Attribute $attribute)
    {
        try
        {
            $validated = $attributeRequest->validated();

            if(isset($validated['options']) && is_array($validated['options']))
            {
                $validated['options'] = serialize($validated['options']);
            }

            $attribute->update($validated);

            return response()->json(['status' => 200, 'message' => "Attribute updated successfully", 'data' => $attribute], 200);

        }
        catch(Exception $ex)
        {
            return response()->json(["status" => 500, "message" => $ex->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute)
    {
        try
        {
            $attribute->delete();

            return response()->json(['status' => 200, 'message' => "Attribute deleted successfully"], 200);
        }
        catch(Exception $ex)
        {
            return response()->json(["status" => 500, "message" => $ex->getMessage()], 500);
        }
    }
}
