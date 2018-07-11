<?php

//PDOClient class extending the Database class
class PDOClient extends Database
{
    protected $dsn;
    protected $options;
    
    //Construct method setting dsn and connection params
    public function __construct($driver, $host, $db_name, $db_user, $db_password, $db_encoding)
    {
        parent::__construct($host, $db_name, $db_user, $db_password);
        $this->dsn = "{$driver}:host={$this->host};dbname={$this->db_name};charset={$db_encoding}";
        $this->options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        );
    }
    

    //Connection Method
    public function connect()
    {
        try{
            $this->_handler = new PDO($this->dsn, $this->db_user, $this->db_password, $this->options);
        }catch (PDOException $ex){
            die($ex->getMessage());
        }
        return $this;
    }

    //Method to return the query as an object without prepared statement
    public function get()
    {
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }

    //Method to prepare a stament
    public function query($sql)
    {
        $this->statement = $this->_handler->prepare($sql);
    }

    //Custom method to bind the type of a param
    public function bind($param, $value, $type = null) 
    {
        if(is_null($type)) {
            switch (true) {
                case is_int($value) : 
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value) : 
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value) : 
                    $type = PDO::PARAM_NULL;
                    break;
                default :
                    $type = PDO::PARAM_STR;
            }
        }

        return $this->statement->bindParam($param, $value, $type);
    }

    //Execute the statement
    public function execute() 
    {
        return $this->statement->execute();
    }

    //Method to return the total row of a table as an object
    public function resultset() 
    {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }

    //Method to return a single row as an object
    public function single()
    {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    //Method to return the total length of a row
    public function rowCount()
    {
        return $this->statement->rowCount();
    }

    //Method to return the last inserted ID for transaction purposes
    public function lastInsertId()
    {
        return $this->_handler->lastInsertId();
    }


    //Transaction methods
    public function beginTransaction()
    {
        return $this->_handler->beginTransaction();
    }

    public function endTransaction()
    {
        return $this->_handler->commit();
    }

    public function cancelTransaction()
    {
        return $this->_handler->rollback();
    }
}