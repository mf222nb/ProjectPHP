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
                <a href='?'>Back</a>
                <h3>Ej inloggad, Registrerar användare</h3>
                <form method='POST'>
                    <fieldset>
                    <p>$this->message</p>
                    <p>$this->message2</p>
                    <legend>Registrera ny användare - Skriv in användarnamn och lösenord</legend>
                            <label for='usernamename'>Namn</label>
                            <input type='text' id='username' name='username' value='$this->username'>
                            <label for='pass'>Lösenord</label>
                            <input type='password' id='pass' name='password'>
                            <label for='repeatpass'>Bekräfta lösenord</label>
                            <input type='password' id='repeatpass' name='repeatpass'>
                    </fieldset>
                    <input type='submit' value='Registrera' name='submit'>
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
        $this->message = "Username is to short. Least 3 characters";
    }

    public function passwordToShortMessage(){
        $this->message2 = "Password is to short. Least 6 characters";
    }

    public function passwordDontMatchMessage(){
        $this->message2 = "Password don't match.";
    }

    public function usernameAlreadyExistMessage(){
        $this->message = "Username is already taken.";
    }

    public function usernameContainInvalidCharacterMessage($e){
        $this->username = $e;
        $this->message = "Username contains invalid characters";
    }

    public function usernameAndPasswordToShortMessage(){
        $this->message = "Username is to short. Least 3 characters";
        $this->message2 = "Password is to short. Least 6 characters";
    }

    public function usernametoLongMessage(){
        $this->message = "Username is to long. Max 40 characters";
    }
}