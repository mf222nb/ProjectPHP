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

    private $idArray;
    private $threadList;

    public function __construct(){
        $this->db = $this->connection();
        $this -> dbTable = 'thread';
        $this->idArray = array();
        $this->threadList = array();
    }

    public function addThread(Thread $thread){
        try{
            $sql = "INSERT INTO $this->dbTable (" . self::$name . ", " . self::$user . ") VALUES (?, ?)";
            $params = array($thread->getThreadName(), $thread->getUser());

            $query = $this->db->prepare($sql);
            $query->execute($params);
        }
        catch(PDOException $e){
            var_dump($e->getMessage());
        }
    }

    public function getThread($user){
        try{
            $sql = "SELECT * FROM $this->dbTable WHERE ". self::$user. " = ?";
            $params = array($user);

            $query = $this->db->prepare($sql);
            $query->execute($params);

            $result = $query->fetchAll();

            foreach($result as $id){
                $id = $id['ThreadId'];

                $this->idArray[] = $id;
            }

            $id = end($this->idArray);

            return $id;
        }
        catch(PDOException $e){
            var_dump($e->getMessage());
        }
    }

    public function getAllThreads(){
        try{
            $sql = "SELECT * FROM $this->dbTable";

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
            var_dump($e->getMessage());
        }
    }
}