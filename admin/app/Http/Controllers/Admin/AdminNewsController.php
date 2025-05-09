<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class AdminNewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::all();

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'url' => 'required|string',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        // Store the image first
        $imagePath = $request->file('image_path')->store('images', 'public');

        // Then create the news record with the correct image path
        $news = News::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'url' => $validated['url'],
            'image_path' => '/storage/' . $imagePath,
        ]);

        return redirect()->back()->with('success', 'News created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = News::find($id);
        return response()->json([
            'status' => true,
            'message' => 'News retrieved successfully',
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'url' => 'required|string',
            'image_path' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $news = News::findOrFail($id);

        // Update fields
        $news->title = $validated['title'];
        $news->description = $validated['description'];
        $news->url = $validated['url'];

        // Optional image update
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('images', 'public');
            $news->image_path = '/storage/' . $imagePath;
        }

        $news->save();

        return redirect()->back()->with('success', 'News updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $news = News::find($id);
        $news->delete();
        return redirect()->back()->with('success', 'News deleted successfully');
    }
}
