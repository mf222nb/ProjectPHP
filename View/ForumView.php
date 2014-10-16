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

    public function forumView($signOutUrl, $username, $threads, $threadUrl, $authenticated){
        $html = "";

        foreach($threads as $thread){
            $name = $thread->getUser();
            if($username === "Admin"){
                $update = "<a href='?edit_thread=". $thread->getThreadId() ."'>Edit</a>";
                $delete = "<a href='?remove_thread=". $thread->getThreadId() ."'>Delete</a>";
            }
            elseif($username === $name){
                $update = "<a href='?edit_thread=". $thread->getThreadId() ."'>Edit</a>";
                $delete = "";
            }
            else{
                $update = "";
                $delete = "";
            }
            $html .= "<div><a href='$threadUrl=". $thread->getThreadId()."'>". $thread->getThreadName()."</a> ". $thread->getUser() ." $update $delete</div>";
        }

        if($authenticated === true){
            $side = "<h3>$username is logged in</h3>
                     <a href='?thread'>New thread</a>
                     <a href='$signOutUrl'>Log Out</a>";
        }
        else{
            $side = "<a href='?login'>Login</a>
                    <a href='?register'>Register User</a>";
        }

        $ret = "
        <p>$this->message</p>
        $side
        <p>$html</p>
        ";

        return $ret;
    }

    public function confirmView($loginUrl, $id){
        $ret = "<a href='$loginUrl'>Back</a>
        <p>Are you sure you want to delete?</p>
        <form method='post' action='?'>
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
        $this->message = "Register of new user was successfull";
    }

    public function setMessage($message){
        $this->message = $message;
    }

    public function emptyField($content){
        if($content === ""){
            $this->message = "Post can't be empty";
        }
    }

    public function emptyThreadNameField($threadName){
        if($threadName === ""){
            $this->message = "Thread name can't be empty";
        }
    }

    public function getUrl(){
        $request_path = $_SERVER['REQUEST_URI'];
        $path = explode("/", $request_path); // splitting the path
        $last = end($path);
        return $last;
    }

    public function getThreadId($string){
        $end = preg_replace("/[^0-9]/", "", $string);
        return $end;
    }

    public function getId(){
        return $this->id;
    }
}