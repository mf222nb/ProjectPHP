<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-09
 * Time: 13:27
 */

require_once('NavigationView.php');

class ThreadView{
    private $threadName;
    private $content;
    private $threadId;
    private $navigationView;

    public function __construct(){
        $this->navigationView = new NavigationView();
    }

    public function newThreadView($url, $thredId = null,$thread = null){
        if($thread != null){
            $summary = $thread['ThreadName'];
            $buttonValue = "Edit";
            $textArea = "";
            $inputField = "<input type='hidden' value='$thredId' name='threadId'>";
            $name = $this->navigationView->getAlterThreadNameValue();
        }
        else{
            $summary = "";
            $buttonValue = "Create new thread";
            $textArea = "<textarea name='content' form='threadForm'></textarea>";
            $inputField = "";
            $name = $this->navigationView->getCreateThreadNameValue();
        }
        $ret = "
        <a href='$url'>Back</a>
        <form method='post' action='?' id='threadForm'>
            Thread name: <input type='text' name='name' value='$summary'>
            <input type='submit' value='$buttonValue' name='$name'>
            $inputField
        </form>

        $textArea";

        return $ret;
    }

    public function showThreadPosts($posts, $loginUrl, $indexUrl, $authenticated, $username){
        $html = "";
        $id = 0;

        foreach($posts as $post){
            $id = $post->getThreadId();
            $name = $post->getUser();
            if($username === $name || $username === "Admin"){
                $delete = "<a href='?delete_post=". $post->getPostId() ."'>Delete post</a>";
                $update = "<a href='?edit_post=". $post->getPostId() ."'>Edit</a>";
            }
            else {
                $delete = "";
                $update = "";
            }
            $html .= "<div>". $post->getContent() ." " . $post->getUser() . " $update $delete</div>";
        }
        if($authenticated === true){
            $side = "<a href='$loginUrl'>Back</a>
                     <a href='?create_post=$id'>Create new post</a>";
        }
        else{
            $side = "<a href='$indexUrl'>Back</a>";
        }
        $ret = "
        $side
        <p>$html</p>";

        return $ret;
    }

    public function getThreadInfromation(){
        if(isset($_POST['name'])){
            $this->threadName = $_POST['name'];
        }

        if(isset($_POST['content'])){
            $this->content = $_POST['content'];
        }

        if(isset($_POST['threadId'])){
            $this->threadId = $_POST['threadId'];
        }
    }

    public function userPressedCreate(){
        $create = $this->navigationView->getCreateThreadNameValue();
        if(isset($_POST[$create])){
            return true;
        }
        return false;
    }

    public function userPressedAlter(){
        $alter = $this->navigationView->getAlterThreadNameValue();
        if(isset($_POST[$alter])){
            return true;
        }
        return false;
    }

    public function userPressedCreatePost(){
        if(isset($_GET['create_post'])){
            return true;
        }
        return false;
    }

    public function userPressedDeletePost(){
        if(isset($_GET['delete_post'])){
            return true;
        }
        return false;
    }

    public function userPressedEditPost(){
        if(isset($_GET['edit_post'])){
            return true;
        }
        return false;
    }

    public function getThreadName(){
        return $this->threadName;
    }

    public function getContent(){
        return $this->content;
    }

    public function getThreadId(){
        return $this->threadId;
    }
}