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
    private $message;

    public function __construct(){
        $this->navigationView = new NavigationView();
    }

    /**
     * @param $url string
     * @param null $threadId string
     * @param null $thread string
     * @return string string
     */
    public function newThreadView($url, $threadId = null,$thread = null){
        if($thread != null){
            $summary = $thread['ThreadName'];
            $buttonValue = "Edit";
            $textArea = "";
            $inputField = "<input type='hidden' value='$threadId' name='threadId'>";
            $name = $this->navigationView->getAlterThreadNameValue();
        }
        else{
            $summary = "";
            $buttonValue = "Create new thread";
            $textArea = "<textarea name='content' class='Area'></textarea>";
            $inputField = "";
            $name = $this->navigationView->getCreateThreadNameValue();
        }
        $ret = "
        <header><a href='$url' class='button'>Back</a></header>
        <form method='post' action='?' class='mainDiv'>
            $this->message
            <p class='submit'>Thread name: <input type='text' name='name' value='$summary' class='ThreadName'></p>
            <p>$textArea</p>
            <input type='submit' value='$buttonValue' name='$name' class='submit'>
            $inputField
        </form>

        ";

        return $ret;
    }

    /**
     * @param $posts <List>
     * @param $loginUrl string
     * @param $indexUrl string
     * @param $authenticated bool
     * @param $username string
     * @return string
     */
    public function showThreadPosts($posts, $loginUrl, $indexUrl, $authenticated, $username){
        $html = "";
        $id = 0;

        foreach($posts as $post){
            $id = $post->getThreadId();
            $name = $post->getUser();
            if($username === $name || $username === "Admin"){
                $delete = "<a href='?delete_post=". $post->getPostId() ."' class='editButton'>Delete post</a>";
                $update = "<a href='?edit_post=". $post->getPostId() ."' class='editButton'>Edit</a>";
            }
            else {
                $delete = "";
                $update = "";
            }
            $html .= "<div class='PostDiv'>". $post->getContent() ." <div><div class='buttons'>$update $delete</div> <div class='UserDiv'>Created by: " . $post->getUser() . "</div> <div class='CreatedDiv'>Created: ". gmdate('Y-m-d\T H:i', $post->getTime()) ."</div></div></div>";
        }
        if($authenticated === true){
            $side = "<header>
                     <h3>User: $username</h3>
                     <a href='$loginUrl' class='button'>Back</a>
                     <a href='?create_post=$id' class='button'>New post</a></header>";
        }
        else{
            $side = "<a href='$indexUrl' class='button'>Back</a>";
        }
        $ret = "
        $side
        $this->message
        <div class='mainDiv'>$html</div>";

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

    /**
     * @param $threadName string
     * @param $content string
     */
    public function emptyFields($threadName, $content){
        if($threadName === "" & $content === ""){
            $this->message = "<p class='error'>Thread name and post can't be empty</p>";
        }
        elseif($threadName === ""){
            $this->message = "<p class='error'>Thread name can't be empty</p>";
        }
        else{
            $this->message = "<p class='error'>Post can't be empty</p>";
        }
    }

    public function createThreadMessage(){
        return "<p class='success'>New thread and post was created</p>";
    }

    public function threadAlterMessage(){
        return "<p class='success'>Thread titel was edited</p>";
    }

    public function removeThreadMessage(){
        return "<p class='success'>Thread was removed</p>";
    }

    public function ThreadNameToLongMessage(){
        $this->message = "<p class='error'>Thread name is to long</p>";
    }

    /**
     * @param $message string
     */
    public function setMessage($message){
        $this->message = $message;
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