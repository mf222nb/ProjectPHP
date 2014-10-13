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

    public function __Construct(){
        $this->navigationView = new NavigationView();
    }

    public function forumView($threads, $threadUrl){
        $html = "";
        foreach($threads as $thread){
            $html .= "<div><a href='$threadUrl=". $thread->getThreadId()."'>". $thread->getThreadName()."</a> ". $thread->getUser() ."</div>";
        }
        $ret = "
        <p>$this->message</p>
        <a href='?login'>Login</a>
        <a href='?register'>Register User</a>
        <p>$html</p>
        ";

        return $ret;
    }

    public function loggedInForumView($signOutUrl, $username, $threads, $threadUrl){
        $html = "";
        foreach($threads as $thread){
            $html .= "<div><a href='$threadUrl=". $thread->getThreadId()."'>". $thread->getThreadName()."</a> ". $thread->getUser() ."</div>";
        }
        $ret = "
        <h3>$username is logged in</h3>
        <p>$this->message</p>
        <a href='?thread'>New thread</a>
        <a href='$signOutUrl'>Log Out</a>
        <p>$html</p>";

        return $ret;
    }

    public function showThreadPosts($posts, $url){
        $html = "";
        foreach($posts as $post){
            $id = $post->getThreadId();
            $html .= "<div>". $post->getContent() ." " . $post->getUser() . "</div>";
        }
        $ret = "
        <a href='$url'>Back</a>
        <a href='?create_post=$id'>Create new post</a>
        <p>$html</p>";

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

    public function UserPressedNewThread(){
        if(isset($_GET['thread'])){
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
}