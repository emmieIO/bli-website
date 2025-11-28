<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('author')
            ->latest()
            ->paginate(15);

        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        if (!isset($validated['slug']) || empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('blog-images', 'public');
        }

        $validated['author_id'] = auth()->id();

        if ($validated['status'] === 'published' && !isset($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        BlogPost::create($validated);

        return redirect()->route('admin.blog.index')
            ->with([
                'type' => 'success',
                'message' => 'Blog post created successfully.'
            ]);
    }

    public function edit(BlogPost $blog)
    {
        $blog->load('author');

        return view('admin.blog.edit', ['post' => $blog]);
    }

    public function update(Request $request, BlogPost $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:blog_posts,slug,{$blog->id}",
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        if (!isset($validated['slug']) || empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')
                ->store('blog-images', 'public');
        }

        if ($validated['status'] === 'published' && !$blog->published_at && !isset($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $blog->update($validated);

        return redirect()->route('admin.blog.index')
            ->with([
                'type' => 'success',
                'message' => 'Blog post updated successfully.'
            ]);
    }

    public function destroy(BlogPost $blog)
    {
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->delete();

        return redirect()->route('admin.blog.index')
            ->with([
                'type' => 'success',
                'message' => 'Blog post deleted successfully.'
            ]);
    }
}
