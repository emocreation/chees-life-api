<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\TimeslotCollection;
use App\Models\Timeslot;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;
use Knuckles\Scribe\Attributes\Unauthenticated;

#[Group('Frontend API')]
#[Subgroup('Timeslots')]
class TimeslotController extends Controller
{
    #[Endpoint('Timeslot List')]
    #[Unauthenticated]
    public function index()
    {
        $data = Timeslot::enabled()
            ->availableDates()
            ->availableQuotas()
            ->sortDate()
            ->with('timeslot_quotas')
            ->get();
        return new TimeslotCollection($data);
    }
}
