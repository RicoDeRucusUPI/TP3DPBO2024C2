<?php

class Genre extends DB
{
    function getGenre()
    {
        $query = "SELECT * FROM genre";
        return $this->execute($query);
    }

    function getGenreById($id)
    {
        $query = "SELECT * FROM genre WHERE id_genre=$id";
        return $this->execute($query);
    }

    function addGenre($data)
    {
        $name = $data['name'];
        $query = "INSERT INTO genre VALUES('', '$name')";
        return $this->executeAffected($query);
    }

    function updateGenre($id, $data)
    {
        $query = "UPDATE `genre` SET `name_genre` = '".$data['name']."' WHERE `genre`.`id_genre` = ".$id.";";
        return $this->executeAffected($query);
    }

    function deleteGenre($id)
    {
        $query = "DELETE FROM genre WHERE `genre`.`id_genre` =".$id.";";
        return $this->executeAffected($query);
    }
}
