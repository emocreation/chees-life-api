<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\BannerCollection;
use App\Models\Banner;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;
use Knuckles\Scribe\Attributes\Unauthenticated;

#[Group('Frontend API')]
#[Subgroup('Banners')]
class BannerController extends Controller
{
    #[Endpoint('Banner List')]
    #[Unauthenticated]
    public function index()
    {
        $data = Banner::enabled()->sequence()->get();
        return new BannerCollection($data);
    }
}
