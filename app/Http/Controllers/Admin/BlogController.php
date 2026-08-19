<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::latest()->paginate(10);
        return view('admin.blog.index',compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blog.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
            'summary' => 'required',
        ]);
        $image = $this->uploader($request->file('image'));
        Blog::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'image' => $image,
            'summary' => $data['summary'],
            'featured' => $request->boolean('featured'),
        ]);
        return redirect()->route('admin.blog.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        return view('admin.blog.edit',compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable',
            'summary' => 'required',
        ]);
        $image = $blog->image;
        if ($request->file('image')) {
            File::delete($image);
            $image = $this->uploader($request->file('image'));
        }
        $blog->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'image' => $image,
            'summary' => $data['summary'],
            'featured' => $request->boolean('featured'),
        ]);
        return redirect()->route('admin.blog.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        File::delete($blog->image);
        $blog->delete();
        return redirect()->route('admin.blog.index');
    }

    function uploader($file)
    {
        $path = 'uploads/blog/';
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move($path, $filename);
        return $path . $filename;
    }
}
