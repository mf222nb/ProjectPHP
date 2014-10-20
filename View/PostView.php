<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-13
 * Time: 15:26
 */

require_once('NavigationView.php');

class PostView{
    private $content;
    private $threadId;
    private $navigationView;
    private $message;

    public function __construct(){
        $this->navigationView = new NavigationView();
    }

    public function newPostView($url, $id, $loginUrl, $post = null){
        if($post != null){
            $summary = $post['Content'];
            $buttonValue = "Edit";
            $backUrl = $loginUrl;
            $name = $this->navigationView->getAlterPostNameValue();
        }
        else{
            $summary = '';
            $buttonValue = 'Create new post';
            $backUrl = $url.'='.$id;
            $name = $this->navigationView->getCreatePostNameValue();
        }
        $ret = "
        <header><a href='$backUrl'>Back</a></header>
        $this->message
        <div class='mainDiv'><textarea name='content' form='postForm' class='Area'>$summary</textarea>
        <form method='post' action='?' id='postForm'>
            <input type='hidden' name='threadId' value='$id'>
            <p class='submit'><input type='submit' value='$buttonValue' name='$name'></p>
        </form></div>";

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

    public function emptyField($content){
        if($content === ""){
            $this->message = "<p class='error'>Post can't be empty</p>";
        }
    }

    public function createPostMessage(){
        return "<p class='success'>The post was created</p>";
    }

    public function alterPostMessage(){
        return "<p class='success'>The post was edited</p>";
    }

    public function deletedPostMessage(){
        return "<p class='success'>The post was deleted</p>";
    }

    public function userPressedCreatePost(){
        $create = $this->navigationView->getCreatePostNameValue();
        if(isset($_POST[$create])){
            return true;
        }
        return false;
    }

    public function userPressedAlterPost(){
        $alter = $this->navigationView->getAlterPostNameValue();
        if(isset($_POST[$alter])){
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