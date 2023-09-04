<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\LatestNewsCollection;
use App\Http\Resources\v1\LatestNewsResource;
use App\Models\LatestNews;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Frontend API')]
#[Subgroup('Latest News')]
class LatestNewController extends Controller
{
    #[Endpoint('Latest News List')]
    #[Unauthenticated]
    public function index()
    {
        $data = LatestNews::enabled()
            ->orderByDesc('news_date')
            ->get();
        return new LatestNewsCollection($data);
    }

    #[Endpoint('Latest News Detail')]
    #[Unauthenticated]
    #[UrlParam('slug', 'string', 'news Slug or Id')]
    public function show(string $slug)
    {
        $data = LatestNews::enabled()->slugOrId($slug)->first();
        return new LatestNewsResource($data);
    }
}
