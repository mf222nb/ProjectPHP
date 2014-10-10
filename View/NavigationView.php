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

    public function isSignedOut(){
        if(isset($_GET['signout'])){
            return true;
        }
        return false;
    }
}