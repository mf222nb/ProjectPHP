<?php

class LoginView{
    private $username;
    private $password;
    private $message;

    public function ViewLogin($indexUrl, $loggedInUrl){
        $ret = "<header><h3>Not logged in</h3>
        <a href='$indexUrl' class='button'>Back</a>
        </header>
        <form method='post' action='$loggedInUrl' class='mainDiv'>
            $this->message
            Username: <input type='text' name='username' value='$this->username'>
            Password: <input type='password' name='password'>
            Remember me <input type='checkbox' name='check'>
            <input type='submit' value='Log in' name='submit'>
        </form>";

        return $ret;
    }

    public function getInformation(){
        if(isset($_POST['username'])){
            $this->username = $_POST['username'];
        }
        if(isset($_POST['password'])){
            $this->password = $_POST['password'];
        }
    }

    public function getSubmit(){
        if(isset($_POST['submit'])){
            return true;
        }
        else{
            return false;
        }
    }

    public function getUsername(){
        return $this->username;
    }

    public function getPassword(){
        return $this->password;
    }

    public function failedLogIn($username, $password){
        if($username === ""){
            $this->message = "<p class='error'>Username is missing</p>";
        }
        else if($password === ""){
            $this->message = "<p class='error'>Password is missing</p>";
        }
        else{
            $this->message = "<p class='error'>Username and/or password is wrong</p>";
        }
    }

    public function LogInSuccessMessage(){
        return $this->message = "<p class='success'>Log in was successful</p>";
    }

    public function logOutSuccessMessage(){
        return $this->message = "<p class='success'>You have now logged out</p>";
    }

    public function setMessage($message){
        $this->message = $message;
    }

    public function wantCookie(){
        if(isset($_POST['check'])){
            return true;
        }
        else{
            return false;
        }
    }
}