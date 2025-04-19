<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::latest()->get();
        return view('admin.news.index', compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'nullable|url',
        ]);
        $validated['image_path'] = $request->file('image_path')->store('news', 'public');
        News::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_path' => $validated['image_path'],
            'url' => $validated['url'],
        ]);
        return to_route('news.index')->with('success', 'News created successfully');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'nullable|url',
        ]);
        $news = News::findOrFail($validated['id']);
        $news->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'url' => $validated['url'],
        ]);
        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('news', 'public');
            $news->update(['image_path' => $validated['image_path']]);
        }
        if ($request->has('delete_image')) {
            $news->update(['image_path' => null]);
        }
        return to_route('news.index')->with('success', value: 'News updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:news,id',
        ]);
        $news = News::findOrFail($validated['id']);
        if ($news->image_path) {
            \Storage::disk('public')->delete($news->image_path);
        }
        $news->delete();
        return to_route('news.index')->with('success', 'News deleted successfully');
    }
}
