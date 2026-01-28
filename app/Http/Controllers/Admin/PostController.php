<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Categorie;
use App\Models\CategorieArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('categorie')->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = CategorieArticle::all();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|max:255',
            'resume' => 'nullable|string',
            'contenu' => 'required|string',
            'categorie_articles_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->titre);
        $data['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/posts', 'public');
            $data['image'] = $path;
        }

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('success', 'Article créé avec succès.');
    }

    public function edit(Post $post)
    {
        $categories = CategorieArticle::all();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'titre' => 'required|max:255',
            'resume' => 'nullable|string',
            'contenu' => 'required|string',
            'categorie_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->titre);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/posts', 'public');
            $data['image'] = $path;
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('success', 'Article mis à jour avec succès.');
    }

    public function destroy(Post $post)
    {
        if ($post->image && file_exists(public_path('storage/' . $post->image))) {
            unlink(public_path('storage/' . $post->image));
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Article supprimé avec succès.');
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->with('auteur', 'categorie')->firstOrFail();
        $recentPosts = Post::latest()->take(5)->get();

        return view('blog.show', compact('post', 'recentPosts'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $posts = Post::where('publie', true)
            ->where(function ($q) use ($query) {
                $q->where('titre', 'like', "%$query%")
                  ->orWhere('resume', 'like', "%$query%")
                  ->orWhere('contenu', 'like', "%$query%");
            })
            ->latest()
            ->paginate(6);

        $recentPosts = Post::latest()->take(5)->get();

        return view('actualite', compact('posts', 'recentPosts', 'query'));
    }

}
