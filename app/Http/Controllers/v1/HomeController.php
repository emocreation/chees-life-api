<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\HomeCollection;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Review;
use App\Models\Service;
use App\Models\SocialMedia;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;
use Knuckles\Scribe\Attributes\Unauthenticated;

#[Group('Frontend API')]
#[Subgroup('Home')]
class HomeController extends Controller
{
    #[Endpoint('Home')]
    #[Unauthenticated]
    public function index()
    {
        $categories = Category::enabled()->sequence()->get();
        $banners = Banner::enabled()->get();
        $services = Service::sequence()->enabled()->with('service_descriptions')->limit(9)->get();
        $reviews = Review::enabled()->sortDate()->get();
        $socialMedias = SocialMedia::enabled()->get();
        $data = ['categories' => $categories, 'banners' => $banners, 'services' => $services, 'reviews' => $reviews, 'socialMedias' => $socialMedias];
        return new HomeCollection($data);
    }
}
