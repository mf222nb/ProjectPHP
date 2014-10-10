<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-09
 * Time: 14:08
 */

class ThreadRepository extends Repository{
    private $db;
    private static $name = 'ThreadName';
    private static $user = 'User';

    private $idArray;

    public function __construct(){
        $this->db = $this->connection();
        $this -> dbTable = 'thread';
        $this->idArray = array();
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
}