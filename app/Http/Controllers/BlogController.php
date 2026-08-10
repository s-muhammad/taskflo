<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    //
    public function index()
    {
        $blogs = Blog::latest()->paginate(9);
        return view('blog.index', compact('blogs'));
    }

    public function single(Blog $blog)
    {
        $related = Blog::latest()->take(2)->get();
        return view('blog.single', compact('blog', 'related'));
    }
}
