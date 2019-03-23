<?php

namespace App\Http\Controllers\API;

use File;
use Validator;
use App\User; 
use App\PicturesDatabase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class PicturesController extends Controller
{
    static function convert_path_to_url(&$path) {
        $server_address = request()->getHttpHost();
        preg_match('/(\/pictures).+$/', $path, $url);
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            $path = 'https://' . $server_address . $url[0];
        else
            $path = 'http://' . $server_address . $url[0];
    }

    function getPictures() {
        $PicturesDatabase = new PicturesDatabase();
        $Pictures = $PicturesDatabase->getPictures();
        return response()->json($Pictures, 200);
    }

    function getPictureByID($id) {
        $PicturesDatabase = new PicturesDatabase();
        $Picture = $PicturesDatabase->getPictureByID($id);
        if (isset($Picture->url))
            PicturesController::convert_path_to_url($Picture->url);
        return response()->json($Picture, 200);
    }

    function getPicturesOfFollowed() {
        $user = Auth::user(); 

        $PicturesDatabase = new PicturesDatabase();
        $Pictures = $PicturesDatabase->getPicturesOfFollowed($user['id']);

        foreach ($Pictures as $Picture) {
            if (isset($Picture->url))
                PicturesController::convert_path_to_url($Picture->url);
        }
        return response()->json($Pictures, 200);
    }

    function getPicturesOfFollowedByID($userID) {
        $PicturesDatabase = new PicturesDatabase();
        $Pictures = $PicturesDatabase->getPicturesOfFollowed($userID);

        foreach ($Pictures as $Picture) {
            if (isset($Picture->url))
                PicturesController::convert_path_to_url($Picture->url);
        }
        return response()->json($Pictures, 200);
    }

    function getPicturesOfUser() {
        $user = Auth::user(); 

        $PicturesDatabase = new PicturesDatabase();
        $Pictures = $PicturesDatabase->getPicturesOfUser($user['id']);

        foreach ($Pictures as $Picture) {
            if (isset($Picture->url))
                PicturesController::convert_path_to_url($Picture->url);
        }
        return response()->json($Pictures, 200);
    }

    function getPicturesOfUserByID($userID) {
        $PicturesDatabase = new PicturesDatabase();
        $Pictures = $PicturesDatabase->getPicturesOfUser($userID);

        foreach ($Pictures as $Picture) {
            if (isset($Picture->url))
                PicturesController::convert_path_to_url($Picture->url);
        }
        return response()->json($Pictures, 200);
    }

    function getPicturesOfUserWithQuantity($quantity) {
        $user = Auth::user(); 

        $PicturesDatabase = new PicturesDatabase();
        $Pictures = $PicturesDatabase->getPicturesOfUserWithQuantity($user['id'], $quantity);

        foreach ($Pictures as $Picture) {
            if (isset($Picture->url))
                PicturesController::convert_path_to_url($Picture->url);
        }
        return response()->json($Pictures, 200);
    }

    function getPicturesOfUserByIDWithQuantity($userID, $quantity) {
        $PicturesDatabase = new PicturesDatabase();
        $Pictures = $PicturesDatabase->getPicturesOfUserWithQuantity($userID, $quantity);

        foreach ($Pictures as $Picture) {
            if (isset($Picture->url))
                PicturesController::convert_path_to_url($Picture->url);
        }
        return response()->json($Pictures, 200);
    }

    function postPicture(Request $request) {
        $user = Auth::user(); 

        $validator = Validator::make($request->all(), [ 
            'picture' => 'required|mimes:png,jpg,jpeg,gif', 
        ]);
        
        if ($validator->fails()) { 
            return response()->json(['error' => $validator->errors()], 401);            
        }

        $pic = $request->file('picture');
        $path = public_path() . '/pictures/';
        $name_file = time() . '.' . $pic->getClientOriginalExtension();
        $pic->move($path, $name_file);

        $allParams = $request->all();
        $PicturesDatabase = new PicturesDatabase();
        $PicturesDatabase->createPicture($user['id'], $allParams['description'], $allParams['geolocation'], $path . $name_file);
        return response()->json(['message' => 'Insert picture OK'], 200);
    }

    function deletePictureByID($id) {
        $user = Auth::user(); 

        $PicturesDatabase = new PicturesDatabase();
        $Picture = $PicturesDatabase->getPictures($id);
        $result = $PicturesDatabase->removePictureByID($user['id'], $id);

        if ($result == -1)
            return response()->json(['message' => 'Photo doesn\'t exist'], 402);
        else if ($result == -2)
            return response()->json(['message' => 'Not your picture'], 403);
        else {
            File::delete($Picture->first()->path);
            return response()->json(['message' => 'Delete picture OK'], 200);
        }
        
    }
}