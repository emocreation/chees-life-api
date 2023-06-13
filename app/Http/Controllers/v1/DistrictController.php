<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\DistrictCollection;
use App\Models\District;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;
use Knuckles\Scribe\Attributes\Unauthenticated;

#[Group('Frontend API')]
#[Subgroup('Districts')]
class DistrictController extends Controller
{
    #[Endpoint('District List')]
    #[Unauthenticated]
    public function index()
    {
        $data = District::enabled()->sequence()->get();
        return new DistrictCollection($data);
    }
}
