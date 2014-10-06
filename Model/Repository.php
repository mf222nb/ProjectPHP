<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-06
 * Time: 16:17
 */

abstract class Repository {
    protected $dbUsername = 'root';
    protected $dbPassword = '';
    protected $dbConnstring = 'mysql:host=127.0.0.1;dbname=project';
    protected $dbConnection;
    protected $dbTable;

    protected function connection() {
        if ($this->dbConnection == NULL)
            $this->dbConnection = new \PDO($this->dbConnstring, $this->dbUsername, $this->dbPassword);

        $this->dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $this->dbConnection;
    }
}