<?php

namespace App\Http\Controllers\API;

use Validator;
use App\User; 
use App\FollowDatabase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class FollowController extends Controller
{
    function postFollowByID($id) 
    {
        $user = Auth::user(); 

        $FollowDatabase = new FollowDatabase();
        if ($id == $user['id'])
            return response()->json(['message' => 'You can\'t follow yourself'], 400);

        $return_code = $FollowDatabase->follow($user['id'], $id);
        if ($return_code == -1)
            return response()->json(['message' => 'User doesn\'t exist'], 401);
        else if ($return_code == -2)
            return response()->json(['message' => 'Already followed'], 402);
        else
            return response()->json(['message' => 'Follow OK'], 200);
    }

    function deleteFollowByID($id)
    {
        $user = Auth::user(); 

        $FollowDatabase = new FollowDatabase();
        $return_code = $FollowDatabase->unfollow($user['id'], $id);
        return response()->json(['message' => 'unfollow OK'], 200);
    }
}