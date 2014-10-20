<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-09
 * Time: 14:08
 */

require_once("Thread.php");

class ThreadRepository extends Repository{
    private $db;
    private static $name = 'ThreadName';
    private static $user = 'User';
    private static $threadId = 'ThreadId';
    private static $time = 'Time';

    private $threadList;

    public function __construct(){
        $this->db = $this->connection();
        $this->dbTable = 'thread';
        $this->dbTable2 = 'post';
        $this->idArray = array();
        $this->threadList = array();
    }

    public function addThread(Thread $thread){
        try{
            $sql = "INSERT INTO $this->dbTable (" . self::$name . ", " . self::$user . ") VALUES (?, ?)";
            $params = array($thread->getThreadName(), $thread->getUser());

            $query = $this->db->prepare($sql);
            $query->execute($params);

            return $this->db->lastInsertId();
        }
        catch(PDOException $e){

        }
    }

    public function getSingleThread($threadId){
        try{
            $sql = "SELECT * FROM $this->dbTable WHERE ".self::$threadId." = ?";
            $params = array($threadId);

            $query = $this->db->prepare($sql);
            $query->execute($params);

            $result = $query->fetch();

            return $result;
        }
        catch(PDOException $e){

        }
    }

    public function getAllThreads(){
        try{
            $sql = "SELECT * FROM $this->dbTable INNER JOIN (SELECT $this->dbTable2.".self::$threadId.", MAX(".self::$time.")
                    AS latest FROM $this->dbTable2
                    GROUP BY $this->dbTable2.".self::$threadId.") r
                    ON $this->dbTable.".self::$threadId." = r.".self::$threadId."
                    ORDER BY latest DESC";

            $query = $this->db->prepare($sql);
            $query->execute();

            $result = $query->fetchAll();

            foreach($result as $thread){
                $threadId = $thread['ThreadId'];
                $threadName = $thread['ThreadName'];
                $user = $thread['User'];

                $threads = new Thread($threadName, $user, $threadId);

                $this->threadList[] = $threads;
            }

            return $this->threadList;
        }
        catch(PDOException $e){

        }
    }

    public function updateThread($name, $threadId){
        try{
            $sql = "UPDATE $this->dbTable SET ".self::$name." = ? WHERE ".self::$threadId." = ?";
            $params = array($name, $threadId);

            $query = $this->db->prepare($sql);
            $query->execute($params);
        }
        catch(PDOException $e){

        }
    }

    public function deleteThread($threadId){
        try{
            $sql = "DELETE FROM $this->dbTable WHERE ".self::$threadId." = ?";
            $params = array($threadId);

            $query = $this->db->prepare($sql);
            $query->execute($params);
        }
        catch(PDOException $e){

        }
    }

    public function fieldsAreEmpty($threadName, $content){
        if(empty($threadName) & empty($content)){
            return true;
        }
        if(empty($threadName)){
            return true;
        }
        if(empty($content)){
            return true;
        }
        return false;
    }

    public function invalidLength($threadName){
        if(mb_strlen($threadName) > 100){
            return true;
        }
        return false;
    }

    public function fieldAreEmpty($threadName){
        if(empty($threadName)){
            return true;
        }
        return false;
    }
}