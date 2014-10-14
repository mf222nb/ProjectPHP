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
    private static $postId = 'PostId';

    private $postList;

    public function __construct(){
        $this->db = $this->connection();
        $this -> dbTable = 'post';
        $this->postList = array();
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

    public function getThreadPost($id){
        try{
            $sql = "SELECT * FROM $this->dbTable WHERE ". self::$threadId ." = ?";
            $params = array($id);

            $query = $this->db->prepare($sql);
            $query->execute($params);

            $result = $query->fetchAll();

            foreach($result as $post){
                $content = $post['Content'];
                $threadId = $post['ThreadId'];
                $user = $post['User'];
                $postId = $post['PostId'];

                $posts = new Post($content, $threadId, $user, $postId);

                $this->postList[] = $posts;
            }

            return $this->postList;
        }
        catch(PDOException $e){

        }
    }

    public function getSinglePost($id){
        try{
            $sql = "SELECT * FROM $this->dbTable WHERE ". self::$postId ." = ?";
            $params = array($id);

            $query = $this->db->prepare($sql);
            $query->execute($params);

            $result = $query->fetch();

            return $result;
        }
        catch(PDOException $e){

        }
    }

    public function deletePost($id){
        try{
            $sql = "DELETE FROM $this->dbTable WHERE ". self::$postId ." = ?";
            $params = array($id);

            $query = $this->db->prepare($sql);
            $query->execute($params);
        }
        catch(PDOException $e){

        }
    }

    public function updatePost($content, $id){
        try{
            $sql = "UPDATE $this->dbTable SET ". self::$content ." = ? WHERE ". self::$postId." = ?";
            $params = array($content, $id);

            $query = $this->db->prepare($sql);
            $query->execute($params);
        }
        catch(PDOException $e){

        }
    }
}