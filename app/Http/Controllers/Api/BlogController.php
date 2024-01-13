<?php

// app/Http/Controllers/Api/BlogController.php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::all();

        // Add link_image to each blog entry
        foreach ($blogs as $blog) {
            $blog->link_image = $this->getImageUrl($blog->blog_image);
        }

        return response()->json(['blogs' => $blogs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'blog_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'blog_category_id' => 'required|exists:blog_categories,id',
                'writer' => 'required|string|max:255',
            ]);

            $imageName = time() . '.' . $request->blog_image->getClientOriginalExtension();
            $request->blog_image->move(public_path('images/blog'), $imageName);

            DB::beginTransaction();

            $blog = Blog::create([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'blog_image' => $imageName,
                'blog_category_id' => $request->input('blog_category_id'),
                'date' => Carbon::now(),
                'writer' => $request->input('writer'),
            ]);

            DB::commit();

            return response()->json(['blog' => $blog, 'message' => 'Blog created successfully'], 201);
        } catch (\Exception $e) {
            // Rollback the database transaction in case of an error
            DB::rollBack();

            // Log the exception for further analysis
            Log::error('Error creating blog: ' . $e->getMessage());

            // Return an error response
            return response()->json(['error' => 'Failed to create blog', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        return response()->json(['blog' => $blog]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'blog_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'date' => 'required|date',
            'writer' => 'required|string|max:255',
        ]);

        // Update existing image or upload new image
        if ($request->hasFile('blog_image')) {
            // Delete old image
            Storage::delete('public/images/blog/' . $blog->blog_image);

            // Upload new image
            $imageName = time() . '.' . $request->blog_image->getClientOriginalExtension();
            $request->blog_image->move(public_path('images/blog'), $imageName);

            $blog->update([
                'blog_image' => $imageName,
            ]);
        }

        $blog->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'blog_category_id' => $request->input('blog_category_id'),
            'date' => $request->input('date'),
            'writer' => $request->input('writer'),
        ]);

        return response()->json(['blog' => $blog, 'message' => 'Blog updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        // Delete image file
        Storage::delete('public/images/blog/' . $blog->blog_image);

        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully']);
    }

    /**
     * Get the public URL for a blog image.
     *
     * @param string $imageName
     * @return string
     */
    private function getImageUrl($imageName)
    {
        $baseUrl = config('app.url');
        return "{$baseUrl}/images/blog/{$imageName}";
    }
}
