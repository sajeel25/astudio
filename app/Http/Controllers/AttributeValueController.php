<?php

namespace App\Http\Controllers;

use App\Models\AttributeValue;
use App\Services\AttributeValueService;
use Exception;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    protected $attributeValueService;

    public function __construct(AttributeValueService $attributeValueService)
    {
        $this->attributeValueService = $attributeValueService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $entity_id)
    {
        try
        {
            $query = $this->attributeValueService->getAttributeValueQuery($entity_id);

            $attributeValues = $request->has('paginate') ? $query->paginate($request->paginate) : $query->get();

            return response()->json(['status' => 200, 'message' => "Attribute values listing successful", 'data' => $attributeValues], 200);
        }
        catch(Exception $ex)
        {
            return response()->json(["status" => 500, "message" => $ex->getMessage()], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttributeValue $attributeValue)
    {
        try
        {
            $attributeValue->delete();

            return response()->json(['status' => 200, 'message' => "Attribute value deleted successfully"], 200);
        }
        catch(Exception $ex)
        {
            return response()->json(["status" => 500, "message" => $ex->getMessage()], 500);
        }
    }
}
