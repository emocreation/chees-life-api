<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\TimeslotCollection;
use App\Models\Timeslot;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Subgroup;
use Knuckles\Scribe\Attributes\Unauthenticated;

#[Group('Frontend API')]
#[Subgroup('Timeslots')]
class TimeslotController extends Controller
{
    #[Endpoint('Timeslot List')]
    #[Unauthenticated]
    #[QueryParam('date_from', 'string', example: 'No-example')]
    #[QueryParam('date_to', 'string', example: 'No-example')]
    public function index(Request $request)
    {
        $data = Timeslot::enabled()
            ->availableDates()
            ->availableQuotas()
            ->dateRange($request->date_from, $request->date_to)
            ->sortDate()
            ->with('timeslot_quotas')
            ->get();
        return new TimeslotCollection($data);
    }
}
