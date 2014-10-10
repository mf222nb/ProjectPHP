<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-10
 * Time: 15:39
 */

class PostRepository extends Repository{
    private $db;
    private static $content = 'Content';
    private static $threadId = 'ThreadId';
    private static $user = 'User';

    public function __construct(){
        $this->db = $this->connection();
        $this -> dbTable = 'post';
    }

    public function addPost(Post $post){
        try{
            $sql = "INSERT INTO $this->dbTable (" . self::$content . ", " . self::$threadId . ", " . self::$user . " ) VALUES (?, ?, ?)";
            $params = array($post->getContent(), $post->getThreadId(), $post->getUser());

            $query = $this->db->prepare($sql);
            $query->execute($params);
        }
        catch(PDOException $e){

        }
    }
}