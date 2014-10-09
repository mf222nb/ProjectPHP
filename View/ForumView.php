<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-06
 * Time: 13:25
 */
require_once('NavigationView.php');

class ForumView{
    private $navigationView;
    private $message;

    public function __Construct(){
        $this->navigationView = new NavigationView();
    }

    public function forumView(){
        $ret = "
        <p>$this->message</p>
        <a href='?login'>Login</a>
        <a href='?register'>Register User</a>";

        return $ret;
    }

    public function loggedInForumView($signOutUrl, $username){
        $ret = "
        <h3>$username is logged in</h3>
        <p>$this->message</p>
        <a href='?thread'>New thread</a>
        <a href='$signOutUrl'>Log Out</a>";

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
}