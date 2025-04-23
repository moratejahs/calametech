<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\News;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    public function index()
    {
        $news = Cache::rememberForever(
            'news',
            fn() => News::latest()->get()
        );

        return response()->json([
            'data' => $news
        ]);
    }
}
