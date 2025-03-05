<?php

namespace App\Services;

use App\Models\TimeSheet;
use Illuminate\Support\Facades\Auth;

class TimeSheetService
{

    public function getTimeSheetQuery()
    {
        return TimeSheet::with('project:id,name')->where('user_id', Auth::id());
    }

}
