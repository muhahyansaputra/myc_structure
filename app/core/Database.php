<?php 

class Database{
    private $dbHost = DBHOST;
    private $dbName = DBNAME;
    private $dbUser = DBUSER;
    private $dbPassword = DBPASSWORD;

    private $dbh;
    private $statement;

    public function __construct()
    {
        $dsn = 'mysql:host='.$this->dbHost.';dbname='.$this->dbName;

        $option = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        try {
            $this->dbh = new PDO($dsn, $this->dbUser, $this->dbPassword, $option);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function query($query){
        $this->statement = $this->dbh->prepare($query);
    }

    public function bind($arg, $value, $type = null){
        if(is_null($type)){
            switch(true){
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                $type = PDO::PARAM_STR;
            }
        }
        $this->statement->bindValue($arg, $value, $type);
    }

    public function execute(){
        $this->statement->execute();
    }

    public function single(){
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    public function resultSet(){
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rowCount(){
        return $this->statement->rowCount();
    }
}