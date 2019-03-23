<?php

namespace App\Http\Controllers\API;

use Validator;
use App\User; 
use App\LikesDatabase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class LikesController extends Controller
{
    function postLikeByID($id) 
    {
        $user = Auth::user(); 

        $LikesDatabase = new LikesDatabase();
        $return_code = $LikesDatabase->like($user['id'], $id);

        if ($return_code == -1)
            return response()->json(['message' => 'Post doesn\'t exist'], 401);
        else if ($return_code == -2)
            return response()->json(['message' => 'Already liked'], 402);
        else
            return response()->json(['message' => 'Like OK'], 200);
    }

    function deleteLikeByID($id) 
    {
        $user = Auth::user(); 

        $LikesDatabase = new LikesDatabase();
        $return_code = $LikesDatabase->unlike($user['id'], $id);
        return response()->json(['message' => 'unlike OK'], 200);
    }
}
