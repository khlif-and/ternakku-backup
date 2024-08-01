<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Qurban\BlogResource;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::active()->get();
        return ResponseHelper::success(BlogResource::collection($blogs), 'Blogs retrieved successfully.');
    }
}
