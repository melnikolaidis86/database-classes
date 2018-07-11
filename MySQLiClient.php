<?php

//MySQLiClient class extending the Database class
class MySQLiClient extends Database
{
    //Construct method setting connection params
    public function __construct($host, $db_name, $db_user, $db_password)
    {
        parent::__construct($host, $db_name, $db_user, $db_password);
    }
    
    //Connection Method
    public function connect()
    {
        $this->_handler = new mysqli($this->host, $this->db_user, $this->db_password, $this->db_name);
        $this->_handler->set_charset(DB_ENCODE);
        return $this;
    }

    //Fetching the query results as an object
    public function get()
    {
        return json_decode(json_encode($this->statement->fetch_all(MYSQLI_ASSOC)));
    }

    //Fetching single row result
    public function single()
    {
        return $this->statement->fetch_object();
    }

    //Method to return the total length of a row
    public function rowCount()
    {
        return $this->statement->num_rows;
    }

    //Closing connection
    public function __destruct()
    {
        $this->_handler->close();
    }
}