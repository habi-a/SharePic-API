<?php

namespace App;

use Illuminate\Support\Facades\DB;


class LikesDatabase
{
    function like($userID, $id)
    {
        if (DB::table('pictures')->where('id', $id)->doesntExist())
            return (-1);

        if (DB::table('likes')->where([
                ['authorID', $userID],
                ['pictureID', $id]
           ])->exists())
            return (-2);

        DB::table('likes')->insert([
            'authorID' => $userID,
            'pictureID' => $id
        ]);
    }

    function unlike($userID, $id)
    {
        DB::table('likes')->where([
            ['authorID', $userID],
            ['pictureID', $id]
        ])->delete();
    }
}