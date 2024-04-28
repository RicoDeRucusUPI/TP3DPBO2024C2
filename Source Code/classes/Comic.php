<?php

class Comic extends DB
{
    function getComicJoin()
    {
        $query = "SELECT * FROM comic JOIN genre ON comic.id_genre=genre.id_genre JOIN publisher ON comic.id_publisher=publisher.id_publisher ORDER BY comic.id_comic";

        return $this->execute($query);
    }

    function getComic()
    {
        $query = "SELECT * FROM comic";
        return $this->execute($query);
    }

    function getComicById($id)
    {
        $query = "SELECT * FROM comic JOIN genre ON comic.id_genre=genre.id_genre JOIN publisher ON comic.id_publisher=publisher.id_publisher WHERE id_comic=$id";
        return $this->execute($query);
    }

    function searchComic($keyword)
    {
        $query = "SELECT * FROM comic JOIN genre ON comic.id_genre=genre.id_genre JOIN publisher ON comic.id_publisher=publisher.id_publisher  WHERE name_comic LIKE '".$keyword."';";
        return $this->execute($query);
    }

    function addData($data, $name_image)
    {
        $query = "INSERT INTO `comic` VALUES (null, '".$data['name']."', '".$data['description']."', '".$name_image."', ".$data['id_genre'].", ".$data['id_publisher']." );";
        return $this->execute($query);
    }

    function updateData($id, $data, $name_image = null)
    {
        if (isset($name_image)) {
            $query = "UPDATE `comic` SET `name_comic` = '".$data['name']."', `description_comic` = '".$data['description']."', `image_comic` = '".$name_image."', `id_genre` = '".$data['id_genre']."', `id_publisher` = '".$data['id_publisher']."' WHERE `comic`.`id_comic` = ".$id.";";
        }else{
            $query = "UPDATE `comic` SET `name_comic` = '".$data['name']."', `description_comic` = '".$data['description']."', `id_genre` = '".$data['id_genre']."', `id_publisher` = '".$data['id_publisher']."' WHERE `comic`.`id_comic` = ".$id.";";
        }
        return $this->execute($query);
    }

    function deleteData($id)
    {
        $query = "DELETE FROM `comic` WHERE `comic`.`id_comic` = ".$id.";";
        return $this->execute($query);
    }
}
