<?php

namespace App;

use Illuminate\Support\Facades\DB;


class PicturesDatabase
{
    function getPictures()
    {
        return DB::table('pictures')->get();
    }

    function getPictureByID($id)
    {
        $picture = DB::select( 
            DB::raw("SELECT pictures.id, pictures.userID, users.email AS authorUsername, pictures.path AS url, 
                        pictures.description, pictures.geolocation, pictures.created_at , 0 AS nbLikes, 0 AS comments 
                    FROM pictures INNER JOIN users 
                    ON pictures.id = ${id} AND users.id = pictures.userID")
        );

        if (isset($picture[0])) {
            $picture = $picture[0];

            $picture->comments = array();
            $pictureID = $picture->id;
                
            $likes = DB::select(
                DB::raw("SELECT COUNT(id) as nbLikes FROM `likes` WHERE pictureID = ${pictureID}")
            );
            $picture->nbLikes = (isset($likes[0]->nbLikes)) ? $likes[0]->nbLikes : 0;

            $comments = DB::select( 
                DB::raw("SELECT commentaries.id, commentaries.authorID, users.email AS authorUsername, commentaries.content, commentaries.created_at 
                        FROM commentaries INNER JOIN users 
                        ON pictureID = ${pictureID} AND authorID = users.id")
            );
            foreach ($comments as $comment) {
                array_push($picture->comments, $comment);
            }
        }
        return $picture;
    }

    function getPicturesOfFollowed($userID) 
    {
        $pictures = DB::select( 
            DB::raw("SELECT pictures.id, pictures.userID, users.email as authorUsername, pictures.path AS url, 
                        pictures.description, pictures.geolocation, pictures.created_at, 0 AS nbLikes, 0 AS comments 
                    FROM follow INNER JOIN pictures 
                    ON followerID = ${userID} AND pictures.userID = followedID 
                    INNER JOIN users ON userID = users.id
                    ORDER BY pictures.created_at DESC")
        );

        foreach ($pictures as $picture) {
            $picture->comments = array();
            $pictureID = $picture->id;
            
            $likes = DB::select( 
                DB::raw("SELECT COUNT(id) as nbLikes FROM `likes` WHERE pictureID=${pictureID}")
            );
            $picture->nbLikes = (isset($likes[0]->nbLikes)) ? $likes[0]->nbLikes : 0;

            $comments = DB::select( 
                DB::raw("SELECT commentaries.id, commentaries.authorID, users.email AS authorUsername, commentaries.content, commentaries.created_at 
                        FROM commentaries INNER JOIN users 
                        ON pictureID = ${pictureID} AND authorID = users.id")
            );
            foreach ($comments as $comment) {
                array_push($picture->comments, $comment);
            }
        }
        return $pictures;
    }

    function getPicturesOfUser($userID)
    {
        $pictures = DB::select( 
            DB::raw("SELECT pictures.id, pictures.userID, users.email as authorUsername, pictures.path AS url, 
                        pictures.description, pictures.geolocation, pictures.created_at, 0 AS nbLikes, 0 AS comments
                    FROM pictures INNER JOIN users ON userID = users.id AND userID = ${userID}
                    ORDER BY pictures.created_at DESC")
        );

        foreach ($pictures as $picture) {
            $picture->comments = array();
            $pictureID = $picture->id;
            
            $likes = DB::select( 
                DB::raw("SELECT COUNT(id) as nbLikes FROM `likes` WHERE pictureID=${pictureID}")
            );
            $picture->nbLikes = (isset($likes[0]->nbLikes)) ? $likes[0]->nbLikes : 0;

            $comments = DB::select( 
                DB::raw("SELECT commentaries.id, commentaries.authorID, users.email AS authorUsername, commentaries.content, commentaries.created_at 
                        FROM commentaries INNER JOIN users 
                        ON pictureID = ${pictureID} AND authorID = users.id")
            );
            foreach ($comments as $comment) {
                array_push($picture->comments, $comment);
            }
        }
        return $pictures;
    }

    function getPicturesOfUserWithQuantity($userID, $quantity)
    {
        $pictures = DB::select( 
            DB::raw("SELECT pictures.id, pictures.userID, users.email as authorUsername, pictures.path AS url, 
                        pictures.description, pictures.geolocation, pictures.created_at, 0 AS nbLikes, 0 AS comments
                    FROM pictures INNER JOIN users ON userID = users.id AND userID = ${userID}
                    ORDER BY pictures.created_at DESC LIMIT ${quantity}")
        );

        foreach ($pictures as $picture) {
            $picture->comments = array();
            $pictureID = $picture->id;
            
            $likes = DB::select( 
                DB::raw("SELECT COUNT(id) as nbLikes FROM `likes` WHERE pictureID=${pictureID}")
            );
            $picture->nbLikes = (isset($likes[0]->nbLikes)) ? $likes[0]->nbLikes : 0;

            $comments = DB::select( 
                DB::raw("SELECT commentaries.id, commentaries.authorID, users.email AS authorUsername, commentaries.content, commentaries.created_at 
                        FROM commentaries INNER JOIN users 
                        ON pictureID = ${pictureID} AND authorID = users.id")
            );
            foreach ($comments as $comment) {
                array_push($picture->comments, $comment);
            }
        }
        return $pictures;
    }

    function createPicture($userID, $description, $geolocation, $path)
    {
        DB::table('pictures')->insert([
            'userID' => $userID,
            'description' => $description,
            'geolocation' => $geolocation,
            'path' => $path
        ]);
    }

    function removePictureByID($userID, $id)
    {
        if (DB::table('pictures')->where('id', $id)->doesntExist())
            return (-1);

        $ownerID = DB::table('pictures')
                    ->where('id', $id)
                    ->first();

        $ownerID = $ownerID->userID;
        if ($userID == $ownerID) {
            DB::table('likes')->where('pictureID', $id)->delete();
            DB::table('commentaries')->where('pictureID', $id)->delete();
            DB::table('pictures')->where('id', $id)->delete();
        }
        else
            return (-2);
    }
}