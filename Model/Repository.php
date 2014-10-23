<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-06
 * Time: 16:17
 */
require_once("./Settings.php");

abstract class Repository{
    protected $dbUsername = Username;
    protected $dbPassword = Password;
    protected $dbConnstring = ConnectionString;
    protected $dbConnection;
    protected $dbTable;
    protected $dbTable2;

    protected function connection() {
        if ($this->dbConnection == NULL)
            $this->dbConnection = new \PDO($this->dbConnstring, $this->dbUsername, $this->dbPassword);

        $this->dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $this->dbConnection;
    }
}