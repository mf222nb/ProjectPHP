<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-06
 * Time: 13:25
 */
require_once("NavigationView.php");

class ForumView{
    private $navigationView;
    private $message;
    private $id;

    public function __Construct(){
        $this->navigationView = new NavigationView();
    }

    /**
     * @param $signOutUrl string
     * @param $username string
     * @param $threads <List>
     * @param $threadUrl string
     * @param $authenticated bool
     * @return string
     */
    public function forumView($signOutUrl, $username, $threads, $threadUrl, $authenticated){
        $html = "";

        foreach($threads as $thread){
            $name = $thread->getUser();
            if($username === "Admin"){
                $update = "<a href='?edit_thread=". $thread->getThreadId() ."' class='editButton'>Edit</a>";
                $delete = "<a href='?remove_thread=". $thread->getThreadId() ."' class='editButton'>Delete</a>";
            }
            elseif($username === $name){
                $update = "<a href='?edit_thread=". $thread->getThreadId() ."' class='editButton'>Edit</a>";
                $delete = "";
            }
            else{
                $update = "";
                $delete = "";
            }
            $html .= "<div class='ThreadsDiv'><div class='threadLink'><a href='$threadUrl=". $thread->getThreadId()."' class='link'>". $thread->getThreadName()."</a></div><div class='space'>$update $delete</div><div class='UserDiv'>Created by: ". $thread->getUser() ."</div> </div>";
        }

        if($authenticated === true){
            $side = "<header>
                     <h3>User online: $username</h3>
                     <div class='headerDiv'><a href='?thread' class='button'>New thread</a></div>
                     <div><a href='$signOutUrl' class='button'>Log Out</a></div>
                     </header>";
        }
        else{
            $side = "<header>
                     <h3>Log in/Register</h3>
                     <div><a href='?login' class='button'>Log in</a>
                     <a href='?register' class='button'>Register User</a></div></header>";
        }

        $ret = "
        $side
        $this->message
        <div class='mainDiv'>$html</div>
        ";

        return $ret;
    }

    /**
     * @param $loginUrl string
     * @param $id string
     * @return string
     */
    public function confirmView($loginUrl, $id){
        $ret = "<header><a href='$loginUrl' class='button'>Back</a></header>
        <form method='post' action='?' class='mainDiv'>
            <p>Are you sure you want to delete?</p>
            <input type='hidden' name='id' value='$id'>
            <input type='submit' value='Yes' name='remove'>
        </form>";

        return $ret;
    }

    public function getLogin(){
        if(isset($_GET['login'])){
            return true;
        }
        return false;
    }

    public function getRegister(){
        if(isset($_GET['register'])){
            return true;
        }
        return false;
    }

    public function userPressedNewThread(){
        if(isset($_GET['thread'])){
            return true;
        }
        return false;
    }

    public function userPressedEditThread(){
        if(isset($_GET['edit_thread'])){
            return true;
        }
        return false;
    }

    public function adminPressedRemoveThread(){
        if(isset($_GET['remove_thread'])){
            return true;
        }
        return false;
    }

    public function userPressedYes(){
        if(isset($_POST['remove'])){
            return true;
        }
        return false;
    }

    public function getPostId(){
        if(isset($_POST['id'])){
            $this->id = $_POST['id'];
        }
    }

    public function userPressedLogOut(){
        if($this->navigationView->isSignedOut()){
            return true;
        }
        return false;
    }

    public function userAddedToDataBaseMessage(){
        $this->message = "<p class='success'>Register of new user was successfull</p>";
    }

    /**
     * @param $content string
     */
    public function emptyField($content){
        if($content === ""){
            $this->message = "<p class='error'>Post can't be empty</p>";
        }
    }

    /**
     * @param $threadName string
     */
    public function emptyThreadNameField($threadName){
        if($threadName === ""){
            $this->message = "<p class='error'>Thread name can't be empty</p>";
        }
    }

    public function setMessage($message){
        $this->message = $message;
    }

    public function getUrl(){
        $request_path = $_SERVER['REQUEST_URI'];
        $path = explode("/", $request_path); // splitting the path
        $last = end($path);
        return $last;
    }

    /**
     * @param $string
     * @return mixed
     */
    public function getThreadId($string){
        $end = preg_replace("/[^0-9]/", "", $string);
        return $end;
    }

    public function getId(){
        return $this->id;
    }
}