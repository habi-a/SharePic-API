<?php

namespace App\Http\Controllers\API;

use Validator;
use App\User; 
use App\CommentsDatabase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class CommentsController extends Controller
{
    function postCommentByID(Request $request, $id) 
    {
        $user = Auth::user(); 

        $validator = Validator::make($request->all(), [ 
            'content' => 'required', 
        ]);
        
        if ($validator->fails()) { 
            return response()->json(['error' => $validator->errors()], 401);            
        }

        $allParams = $request->all();
        $CommentsDatabase = new CommentsDatabase();
        $return_code = $CommentsDatabase->comment($user['id'], $id, $allParams['content']);

        if ($return_code == -1)
            return response()->json(['message' => 'Post doesn\'t exist'], 402);
        else
            return response()->json(['message' => 'Comment OK'], 200);
    }

    function deleteCommentByID($id) 
    {
        $user = Auth::user(); 

        $CommentsDatabase = new CommentsDatabase();
        $return_code = $CommentsDatabase->uncomment($user['id'], $id);

        if ($return_code == -1)
            return response()->json(['message' => 'Commentary doesn\'t exist'], 402);
        else if ($return_code == -2)
            return response()->json(['message' => 'Not your comment'], 403);
        else
            return response()->json(['message' => 'Delete comment OK'], 200);
    }
}
