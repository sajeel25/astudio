<?php

namespace App\Services;

use App\Models\AttributeValue;

class AttributeValueService
{

    public function getAttributeValueQuery($entity_id)
    {
        return AttributeValue::where('entity_id', $entity_id)->with('attribute', 'project');
    }

}
