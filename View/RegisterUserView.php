<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-09-25
 * Time: 15:06
 */

class RegisterUserView {
    private $username;
    private $password;
    private $repeatPass;
    private $message;
    private $message2;

    public function registerUserView(){
        $ret = "
                <header><h3>Register user</h3>
                <a href='?' class='button'>Back</a></header>
                <form method='POST' class='mainDiv'>
                    <fieldset>
                    $this->message
                    $this->message2
                    <legend>Register new user - write username and password</legend>
                            <label for='usernamename'>Username</label>
                            <input type='text' id='username' name='username' value='$this->username'>
                            <label for='pass'>Password</label>
                            <input type='password' id='pass' name='password'>
                            <label for='repeatpass'>Repeat password</label>
                            <input type='password' id='repeatpass' name='repeatpass'>
                    </fieldset>
                    <input type='submit' value='Register' name='submit' class='registerButton'>
                </form>
                ";

        return $ret;
    }

    public function getRegisterInformation(){
        if(isset($_POST["username"])){
            $this->username = $_POST["username"];
        }

        if(isset($_POST["password"])){
            $this->password = $_POST["password"];
        }

        if(isset($_POST["repeatpass"])){
            $this->repeatPass = $_POST["repeatpass"];
        }
    }

    public function didUserPressRegister(){
        if(isset($_POST["submit"])){
            return true;
        }
        return false;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getRepeatPass(){
        return $this->repeatPass;
    }

    public function usernameToShortMessage(){
        $this->message = "<p class='error'>Username is to short. Least 3 characters</p>";
    }

    public function passwordToShortMessage(){
        $this->message2 = "<p class='error'>Password is to short. Least 6 characters</p>";
    }

    public function passwordDontMatchMessage(){
        $this->message2 = "<p class='error'>Password don't match</p>";
    }

    public function usernameAlreadyExistMessage(){
        $this->message = "<p class='error'>Username is already taken</p>";
    }

    public function usernameContainInvalidCharacterMessage($e){
        $this->username = $e;
        $this->message = "<p class='error'>Username contains invalid characters</p>";
    }

    public function usernameAndPasswordToShortMessage(){
        $this->message = "<p class='error'>Username is to short. Least 3 characters</p>";
        $this->message2 = "<p class='error'>Password is to short. Least 6 characters</p>";
    }

    public function usernametoLongMessage(){
        $this->message = "<p class='error'>Username is to long. Max 40 characters</p>";
    }
}