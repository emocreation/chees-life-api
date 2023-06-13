<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ReviewCollection;
use App\Models\Review;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;
use Knuckles\Scribe\Attributes\Unauthenticated;

#[Group('Frontend API')]
#[Subgroup('Reviews')]
class ReviewController extends Controller
{
    #[Endpoint('Review List')]
    #[Unauthenticated]
    public function index()
    {
        $data = Review::enabled()->sortDate()->get();
        return new ReviewCollection($data);
    }
}
