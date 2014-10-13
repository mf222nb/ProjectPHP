<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-06
 * Time: 15:08
 */

class NavigationView{

    public function getIndexUrl(){
        return $url = "?";
    }

    public function getLoggedInUrl(){
        return $url = "?login&loggedin";
    }

    public function getLoggedOutUrl(){
        return $url = "?login&signout";
    }

    public function getLoginUrl(){
        return $url = "?login";
    }

    public function getThreadUrl(){
        return $url = "?thread_number";
    }

    public function userPressedThread(){
        if(isset($_GET['thread_number'])){
            return true;
        }
        return false;
    }

    public function isSignedOut(){
        if(isset($_GET['signout'])){
            return true;
        }
        return false;
    }
}