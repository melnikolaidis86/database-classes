<?php

//Abstract class sets the main variables as well as the main methods
abstract class Database
{
    protected $_handler; //A handler to control either mysqli or pdo instances
    protected $statement;
    protected $host, $db_name, $db_user, $db_password;
    
    //Main construct method
    public function __construct($host, $db_name, $db_user, $db_password)
    {
        $this->host = $host;
        $this->db_name = $db_name;
        $this->db_password = $db_password;
        $this->db_user = $db_user;
    }
    
    //Connection method to be extended in clients
    abstract public function connect();
    
    //Query method
    public function select($sql)
    {
        $this->statement = $this->_handler->query($sql);
        return $this;
    }
    
    //Methor to return the instance of the handler
    public function getConnection()
    {
        return $this->_handler;
    }
    
    //Method to fetch the results through queries
    abstract public function get();
}