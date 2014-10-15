<?php

class LoginView{
    private $username;
    private $password;
    private $message;

    public function ViewLogin($indexUrl, $loggedInUrl){
        $ret = "<a href='$indexUrl'>Back</a>
        <h3>Not Logged in</h3>
        <p>$this->message</p>
        <form method='post' action='$loggedInUrl'>
            Username: <input type='text' name='username' value=$this->username>
            Password: <input type='password' name='password'>
            Remember me <input type='checkbox' name='check'>
            <input type='submit' value='Logga in' name='submit'>
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
            $this->message = "Username is missing";
        }
        else if($password === ""){
            $this->message = "Password is missing";
        }
        else{
            $this->message = "Username and/or password is wrong";
        }
    }

    public function LogInSuccessMessage(){
        return $this->message = "Log in was successful";
    }

    public function logOutSuccessMessage(){
        return $this->message = "You have now logged out";
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