<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    //
    public function index()
    {
        $user=Auth::user();
        $posts = $user->posts()
        ->orderBy('pinned', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();
        
        return response()->json(['message'=>'posts retrived successfully','posts'=>$posts],200);
    }
    public function store(Request $request)
    {
        $user=Auth::user();

        $request->validate([
            'title'=>'required|max:255',
            'body'=>'required|string',
            'cover_image'=>'required|image',
            'pinned'=>'required|boolean',
            'tags'=>'array',
            'tags.*'=>'exists:tags,id',
        ]);

        $coverImagePath = $request->file('cover_image')->store('cover_images', 'public');


        $post=$user->posts()->create([
            'title' => $request->title,
            'body' => $request->body,
            'cover_image' => $coverImagePath,
            'pinned' => $request->pinned,
        ]);

        $post->tags()->sync($request->tags);

        return response()->json(['message'=>'post created successfully','post'=>$post],201);
    }
    public function show(Request $request,$id)
    {
        $user=Auth::user();
        $post=$user->posts()->with('tags')->findOrFail($id);
        return response()->json(['message'=>'post retrived successfully','post'=>$post],200);

    }

    
    public function update(Request $request, $id)
{
    $user = Auth::user();

    $request->validate([
        'title' => 'sometimes|required|string|max:255',
        'body' => 'sometimes|required|string',
        'cover_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'pinned' => 'sometimes|required|boolean',
        'tags' => 'sometimes|array',
        'tags.*' => 'exists:tags,id'
    ]);


    $post = $user->posts()->findOrFail($id);

    if ($request->hasFile('cover_image')) {
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }

        $coverImagePath = $request->file('cover_image')->store('cover_images', 'public');
        $post->cover_image = $coverImagePath;
    }

    $post->update($request->only(['title', 'body', 'pinned']));

    if ($request->has('tags')) {
        $post->tags()->sync($request->tags);
    }

    return response()->json(['message' => 'Post updated successfully', 'post' => $post], 200);

}
public function destroy($id)
{
    $user=Auth::user();
    $post=$user->posts()->findOrFail($id)->delete();
    return response()->json(['message' => 'Post deleted successfully'], 200);

}
public function trashed()
{
    $user=Auth::user();

    $posts=$user->posts()->onlyTrashed()->get();
    return response()->json(['message' => 'Post retrived successfully','posts'=>$posts], 200);

}
public function restore($id)
{
    $user=Auth::user();

    $post=$user->posts()->onlyTrashed()->findOrFail($id);
    $post->restore();
    return response()->json(['message' => 'Post restored successfully', 'post' => $post], 200);


}

}
