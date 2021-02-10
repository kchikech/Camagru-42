<?php


class Database
{

    private $host = DB_HOST;
    private $user = DB_USER;
    private $passwd = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $err;
    private $tables = ['users', 'posts', 'comments', 'likes', 'resetPasswd'];

    public function __construct()
    {


        $dsn = 'mysql:host=' . $this->host ;
        $option = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->passwd, $option);
            $db = $this->query("CREATE DATABASE IF NOT EXISTS $this->dbname");
            $this->execute($db);
            $dsn = 'mysql:host=' . $this->host .';dbname=' . $this->dbname;
            $this->dbh = new PDO($dsn, $this->user, $this->passwd, $option);
            foreach ($this->tables as $table) {
                $res = $this->dbh->query("SHOW TABLES LIKE '$table'");
                if (!$res || $res->rowCount() === 0) {
                    redirect('pages/setup');
                }
            }
        } catch (PDOException $e) {
            $this->err = $e->getMessage();
            die($this->err);
        }
    }

    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
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
        $this->stmt->bindValue($param, $value, $type);
    }


    public function execute()
    {
        return $this->stmt->execute();
    }

    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }
}
