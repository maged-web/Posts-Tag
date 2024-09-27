<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    //
    public function index()
    {
        $tags=Tag::all();
        return response()->json(['message' => 'Tags retrieved successfully', 'tags' => $tags], 200);

    }
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255|unique:tags'
        ]);
        $tag =Tag::create([
            'name'=>$request->name
        ]);
        return response()->json(['message' => 'Tag created successfully', 'tag' => $tag], 201);

    }
    public function update(Request $request,$id)
    {
        $tag=Tag::findOrFail($id);

        $request->validate(['name'=>'required|string|max:255']);
        
        $tag->update($request->all());
    
        return response()->json(['message' => 'Tag updated successfully', 'tag' => $tag], 200);

    }

    public function destroy(Request $request,$id)
    {
        $tag=Tag::findOrFail($id);
        $tag->delete();
    
        return response()->json(['message' => 'Tag deleted successfully'], 200);

    }
}
