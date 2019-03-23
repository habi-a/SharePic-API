<?php

namespace App;

use Illuminate\Support\Facades\DB;


class CommentsDatabase
{
    function comment($userID, $id, $content)
    {
        if (DB::table('pictures')->where('id', $id)->doesntExist())
            return (-1);

        DB::table('commentaries')->insert([
            'authorID' => $userID,
            'pictureID' => $id,
            'content' => $content
        ]);
    }

    function uncomment($userID, $id)
    {
        if (DB::table('commentaries')->where('id', $id)->doesntExist())
            return (-1);

        $ownerID = DB::table('commentaries')
                    ->where('id', $id)
                    ->first();
        $ownerID = $ownerID->authorID;

        if ($userID == $ownerID)
            DB::table('commentaries')->where('id', $id)->delete();
        else
            return (-2);
    }
}