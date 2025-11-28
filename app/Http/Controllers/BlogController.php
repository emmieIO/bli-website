<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::published()
            ->with('author')
            ->latest('published_at')
            ->paginate(12);

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
        ]);
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->published()
            ->with('author')
            ->firstOrFail();

        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return Inertia::render('Blog/Show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
