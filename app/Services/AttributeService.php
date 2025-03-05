<?php

namespace App\Services;

use App\Models\Attribute;

class AttributeService
{

    public function getAttributeQuery()
    {
        return Attribute::query();
    }

}
