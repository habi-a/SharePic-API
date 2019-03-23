<?php

namespace App\Http\Controllers\API;

use Validator;
use App\User;
use App\UsersDatabase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 

class UsersController extends Controller 
{
    public $successStatus = 200;

    static function convert_path_to_url(&$path) {
        $server_address = request()->getHttpHost();
        preg_match('/(\/profile_pics).+$/', $path, $url);
        if ($server_address === '::1' || $server_address == '')
            $server_address = '127.0.0.1';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            $path = 'https://' . $server_address . $url[0];
        else
            $path = 'http://' . $server_address . $url[0];
    }

    public function login()
    { 
        if (Auth::attempt(['email' => request('username'), 'password' => request('password')])) { 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json($success, $this-> successStatus); 
        } 
        else { 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }

    public function logout()
    { 
        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return response()->json(['message' => 'logout_success'], 200); 
        }
        else
            return response()->json(['error' => 'api.something_went_wrong'], 500);
    }
    
    public function register(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required', 
            'username' => 'required', 
            'password' => 'required', 
            'c_password' => 'required|same:password',
            'picture' => 'required|mimes:png,jpg,jpeg,gif',
        ]);
        
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        if (Auth::attempt(['email' => request('username'), 'password' => request('password')])) { 
            return response()->json(['message' => 'Account already exists!'], 200);
        }

        $pic = $request->file('picture');
        $path = public_path() . '/profile_pics/';
        $name_file = time() . '.' . $pic->getClientOriginalExtension();
        $pic->move($path, $name_file);

        $input = $request->all(); 
        $input['email'] = $input['username'];
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        $success['token'] =  $user->createToken('MyApp') -> accessToken; 
        $success['name'] =  $user->name;

        $UsersDatabase = new UsersDatabase();
        $UsersDatabase->addProfilePicture($input['email'], $path . $name_file);
        
        return response()->json($success, $this->successStatus); 
    }

    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this->successStatus); 
    }

    public function getUsers()
    {
        $UsersDatabase = new UsersDatabase();
        $users = $UsersDatabase->getUsers();

        foreach ($users as $user) {
            if (isset($user->url))
                UsersController::convert_path_to_url($user->url);
        }
        return response()->json($users, $this->successStatus); 
    }

    public function getUserByID($id)
    {
        $UsersDatabase = new UsersDatabase();
        $user = $UsersDatabase->getUserByID($id);

        if (isset($user[0])) {
            $user = $user[0];
            if (isset($user->url))
                UsersController::convert_path_to_url($user->url);
        }
        return response()->json($user, $this->successStatus); 
    }

    public function findUsersByUsername($username)
    {
        $UsersDatabase = new UsersDatabase();
        $users = $UsersDatabase->findUsersByUsername($username);

        foreach ($users as $user) {
            if (isset($user->url))
                UsersController::convert_path_to_url($user->url);
        }
        return response()->json($users, $this->successStatus);
    }

    public function getUserFollowing()
    {
        $user = Auth::user(); 

        $UsersDatabase = new UsersDatabase();
        $users = $UsersDatabase->getUserFollowing($user['id']);

        foreach ($users as $user) {
            if (isset($user->url))
                UsersController::convert_path_to_url($user->url);
        }
        return response()->json($users, $this->successStatus);
    }

    public function getUserFollowingByID($userID)
    {
        $UsersDatabase = new UsersDatabase();
        $users = $UsersDatabase->getUserFollowing($userID);

        foreach ($users as $user) {
            if (isset($user->url))
                UsersController::convert_path_to_url($user->url);
        }
        return response()->json($users, $this->successStatus);
    }

    public function getUserFollowers()
    {
        $user = Auth::user(); 

        $UsersDatabase = new UsersDatabase();
        $users = $UsersDatabase->getUserFollowers($user['id']);

        foreach ($users as $user) {
            if (isset($user->url))
                UsersController::convert_path_to_url($user->url);
        }
        return response()->json($users, $this->successStatus);
    }

    public function getUserFollowersByID($userID)
    {
        $UsersDatabase = new UsersDatabase();
        $users = $UsersDatabase->getUserFollowers($userID);

        foreach ($users as $user) {
            if (isset($user->url))
                UsersController::convert_path_to_url($user->url);
        }
        return response()->json($users, $this->successStatus);
    }
}