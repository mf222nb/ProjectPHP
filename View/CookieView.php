<?php

class CookieStorage{
    private $cookieNameUserName = "Username";
    private $cookieNamePassword = "Password";
    private $message;
    private $cookieTime;

    //Sparar ner användarnamn och krypterat lösenord i två olika kakor
    public function save($string, $password){
        $this->cookieTime = time()+3600*24*7;
        setcookie($this->cookieNameUserName, $string, $this->cookieTime);
        setcookie($this->cookieNamePassword, $password, $this->cookieTime);
        return $this->cookieTime;
    }

    //Tar bort kakan när man loggar ut eller när tiden har gått ut.
    public function deleteCookie(){
        setcookie($this->cookieNameUserName, "", time() - 1);
        setcookie($this->cookieNamePassword, "", time() - 1);
    }

    //Tittar om kakan finns.
    public function loadCookie(){
        if(isset($_COOKIE[$this->cookieNameUserName]) && isset($_COOKIE[$this->cookieNamePassword])){
            return true;
        }
        return false;
    }

    //Tittar om kakan redan existerar och i sådana fall så returnerar vi kakans värde.
    public function cookieExist(){
        if(isset($_COOKIE[$this->cookieNameUserName]) && isset($_COOKIE[$this->cookieNamePassword])){
            return array('Username' => $_COOKIE[$this->cookieNameUserName], 'Password' => $_COOKIE[$this->cookieNamePassword]);
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