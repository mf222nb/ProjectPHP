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

    /**
     * @param $url string
     * @param $id string
     * @param $loginUrl string
     * @param null $post array
     * @return string
     */
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
        <header><a href='$backUrl' class='button'>Back</a></header>
        <form method='post' action='?' class='mainDiv'>
            $this->message
            <textarea name='content' class='Area'>$summary</textarea>
            <input type='hidden' name='threadId' value='$id'>
            <p class='pThreadName'><input type='submit' value='$buttonValue' name='$name' class='submitPost'></p>
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

    /**
     * @param $content string
     */
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