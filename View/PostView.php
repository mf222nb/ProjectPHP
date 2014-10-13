<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-13
 * Time: 15:26
 */

class PostView{
    private $content;
    private $threadId;

    public function newPostView($url, $id){
        $ret = "
        <a href='$url=$id'>Back</a>
        <p><textarea name='content' form='postForm'></textarea></p>
        <form method='post' action='?' id='postForm'>
            <input type='hidden' name='threadId' value='$id'>
            <input type='submit' value='Create new post' name='createPost'>
        </form>";

        return $ret;
    }

    public function getPostInformation(){
        if(isset($_POST['content'])){
            $this->content = $_POST['content'];
        }

        if(isset($_POST['threadId'])){
            $this->threadId = $_POST['threadId'];
        }
        return false;
    }

    public function userPressedCreatePost(){
        if(isset($_POST['createPost'])){
            return true;
        }
        return false;
    }

    public function getContent(){
        return $this->content;
    }

    public function getThreadId(){
        return $this->threadId;
    }
}