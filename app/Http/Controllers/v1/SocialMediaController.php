<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\SocialMediaCollection;
use App\Models\SocialMedia;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;
use Knuckles\Scribe\Attributes\Unauthenticated;

#[Group('Frontend API')]
#[Subgroup('Social Media')]
class SocialMediaController extends Controller
{
    #[Endpoint('Social Media List')]
    #[Unauthenticated]
    public function index()
    {
        $data = SocialMedia::enabled()->get();
        return new SocialMediaCollection($data);
    }
}
