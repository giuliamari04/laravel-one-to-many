<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {

        $formData = $request->validated();
        //CREATE SLUG
        $slug = Str::slug($formData['title'], '-');
        //add slug to formData
        $formData['slug'] = $slug;
        //prendiamo l'id dell'utente loggato
        $userId = Auth::id();
        //dd($userId);
        //aggiungiamo l'id dell'utente
        $formData['user_id'] = $userId;
        if ($request->hasFile('image')) {
            $path = Storage::put('images', $request->image);
            $formData['image'] = $path;
        }
        $post = Post::create($formData);
        //dd($path);
        return redirect()->route('admin.posts.show', $post->id);
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $formData = $request->validated();
        //CREATE SLUG
        $slug = Str::slug($formData['title'], '-');
        //add slug to formData
        $formData['slug'] = $slug;

        //aggiungiamo l'id dell'utente proprietario del post
        $formData['user_id'] = $post->user_id;
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::delete($post->image);
            }

            $path = Storage::put('images', $request->image);
            $formData['image'] = $path;
        }
        $post->update($formData);
        return redirect()->route('admin.posts.show', $post->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::delete($post->image);
        }

        $post->delete();
        return to_route('admin.posts.index')->with('message', "$post->title eliminato con successo");
    }
}
