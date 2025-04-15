<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest()->get();
        return response()->json([
            'status' => true,
            'message' => 'News fetched successfully',
            'data' => $news
        ]);
    }
}
