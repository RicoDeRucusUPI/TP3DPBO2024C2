<?php

class Publisher extends DB
{
    function getPublisher()
    {
        $query = "SELECT * FROM publisher";
        return $this->execute($query);
    }

    function getPublisherById($id)
    {
        $query = "SELECT * FROM publisher WHERE id_publisher=$id";
        return $this->execute($query);
    }

    function addPublisher($data)
    {
        $name = $data['name'];
        $query = "INSERT INTO publisher VALUES('', '$name')";
        return $this->executeAffected($query);
    }

    function updatePublisher($id, $data)
    {
        $query = "UPDATE `publisher` SET `name_publisher` = '".$data['name']."' WHERE `publisher`.`id_publisher` = ".$id.";";
        return $this->executeAffected($query);
    }

    function deletePublisher($id)
    {
        $query = "DELETE FROM publisher WHERE `publisher`.`id_publisher` =".$id.";";
        return $this->executeAffected($query);
    }
}
