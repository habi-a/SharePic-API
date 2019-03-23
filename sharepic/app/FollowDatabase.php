<?php

namespace App;

use Illuminate\Support\Facades\DB;


class FollowDatabase
{
    function follow($userID, $id)
    {
        if (DB::table('users')->where('id', $id)->doesntExist())
            return (-1);

        if (DB::table('follow')->where([
                ['followerID', $userID],
                ['followedID', $id]
           ])->exists())
            return (-2);

        DB::table('follow')->insert([
            'followerID' => $userID,
            'followedID' => $id,
        ]);
    }

    function unfollow($userID, $id)
    {
        DB::table('follow')->where([
            ['followerID', $userID],
            ['followedID', $id]
        ])->delete();
    }
}