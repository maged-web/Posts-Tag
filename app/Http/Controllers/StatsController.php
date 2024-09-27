<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    //
    public function stats()
{
    $stats=Cache::remember('stats',3600,function()
    {
        return[
            'number_of_users' => User::count(),
            'number_of_posts' => Post::count(),
            'number_of_users_with_zero_posts' =>User::doesntHave('posts')->count(),
        ]; 
    });

    return response()->json(['message'=>'stats retrived successfully','stats'=>$stats],200);
 
}

}
