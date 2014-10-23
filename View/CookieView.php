<?php

class CookieStorage{
    private $cookieName = "Username";
    private $cookieName2 = "Password";
    private $message;
    private $cookieTime;

    //Sparar ner värdet från $randomString till kakan.
    public function save($string, $password){
        $this->cookieTime = time()+3600*24*7;
        setcookie($this->cookieName, $string, $this->cookieTime);
        setcookie($this->cookieName2, $password, $this->cookieTime);
        return $this->cookieTime;
    }

    //Tar bort kakan när man loggar ut eller när tiden har gått ut.
    public function deleteCookie(){
        setcookie($this->cookieName, "", time() - 1);
        setcookie($this->cookieName2, "", time() - 1);
    }

    //Tittar om kakan finns.
    public function loadCookie(){
        if(isset($_COOKIE[$this->cookieName]) && isset($_COOKIE[$this->cookieName2])){
            return true;
        }
        return false;
    }

    //Tittar om kakan redan existerar och i sådana fall så returnerar vi kakans värde.
    public function cookieExist(){
        if(isset($_COOKIE[$this->cookieName]) && isset($_COOKIE[$this->cookieName2])){
            return array('Username' => $_COOKIE[$this->cookieName], 'Password' => $_COOKIE[$this->cookieName2]);
        }
        return false;
    }

    //Meddelanden
    public function cookieSaveMessage(){
        return $this->message = "<p class='success'>Log in was successfull and we are going to remember you next time</p>";
    }

    public function cookieLoadMessage(){
        return $this->message = "<p class='success'>Log in through cookies was sucessfull</p>";
    }

    public function cookieModifiedMessage(){
        return $this->message = "<p class='error'>Wrong information in cookies</p>";
    }
}

/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-09-15
 * Time: 10:36
 */ 