<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CategoryCollection;
use App\Http\Resources\v1\CategoryResource;
use App\Models\Category;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Frontend API')]
#[Subgroup('Categories')]
class CategoryController extends Controller
{
    #[Endpoint('Category List')]
    #[Unauthenticated]
    public function index()
    {
        $data = Category::enabled()->sequence()->get();
        return new CategoryCollection($data);
    }

    #[Endpoint('Category Detail')]
    #[Unauthenticated]
    #[UrlParam('slug', 'string', 'Category Slug or Id')]
    public function show(string $slug)
    {
        $data = Category::enabled()->slugOrId($slug)->first();
        if (!$data) {
            return $this->error('Category not found', 404);
        }
        return new CategoryResource($data);
    }
}
