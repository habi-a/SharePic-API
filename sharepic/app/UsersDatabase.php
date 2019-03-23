<?php

namespace App;

use Illuminate\Support\Facades\DB;


class UsersDatabase
{
    function addProfilePicture($email, $path)
    {   
        DB::table('users')
            ->where('email', $email)
            ->update(['path' => $path]);
    }

    function getUsers()
    {
        return DB::select( 
            DB::raw("SELECT id, users.name, email AS username, users.path AS `url`, created_at FROM users")
        );
    }

    function getUserByID($id)
    {
        return DB::select( 
            DB::raw("SELECT id, users.name, email AS username, users.path AS `url`, created_at FROM users WHERE id = ${id}")
        );
    }
    
    function getUserFollowing($id)
    {
        return DB::select( 
            DB::raw("SELECT users.id, users.name, users.email AS username, users.path AS `url`, users.created_at FROM users 
                    INNER JOIN follow ON followerID = ${id} AND followedID = users.id")
        );
    }

    function getUserFollowers($id)
    {
        return DB::select( 
            DB::raw("SELECT users.id, users.name, users.email AS username, users.path AS `url`, users.created_at FROM users 
                    INNER JOIN follow ON followedID = ${id} AND followerID = users.id")
        );
    }

    function findUsersByUsername($username)
    {
        return DB::select( 
            DB::raw("SELECT id, users.name, email AS username, users.path AS `url`, created_at FROM users WHERE email LIKE \"${username}%\"")
        );    
    }
}