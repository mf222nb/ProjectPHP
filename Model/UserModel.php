<?php

require_once("Repository.php");

class UserModel extends Repository{
    private $username;
    private $onlyPass;
    private $authenticatedUser = false;
    //Eftersom det bara finns 1 användare så har jag en sträng som jag placerar i kakan som jag jämför med men
    //den ändras inte utan den har ett satt värde.
    private $randomString = "dsdididjsadladacm";
    private $regExp = '/[^a-z0-9\-_\.]/i';

    //Databasvariabler
    private $db;
    private static $name = 'Username';
    private static $pass = 'Password';
    private static $cookieTime = 'Cookietime';

    public function __construct(){
        session_start();
        $this->db = $this->connection();
        $this -> dbTable = 'user';
    }

    //Tittar om användaren är inloggad redan med sessions eller inte.
    public function getAuthenticatedUser($userAgent){
        if(isset($_SESSION["UserAgent"]) && $_SESSION["UserAgent"] === $userAgent){
            if(isset($_SESSION["ValidLogin"])){
                $this->authenticatedUser = true;
            }
        }
        return $this->authenticatedUser;
    }

    //Om användaren väljer att logga ut så tas sessionen bort.
    public function LogOut(){
        if(isset($_SESSION["ValidLogin"])){
            unset($_SESSION["ValidLogin"]);
        }
        return $this->authenticatedUser = false;
    }

    //Hämtar ut strängen vars värde ska in i kakan.
    public function getRandomString(){
        return $this->randomString;
    }

    //Kontrollerar om kakans värde stämmer överens med randomsStrings värde.
    public function controlCookieValue($cookieValue, $userAgent){
        $time = file_get_contents("exist.txt");
        if($time > time()){
            if($this->randomString === $cookieValue){
                $_SESSION["ValidLogin"] = $this->username;
                $_SESSION["UserAgent"] = $userAgent;
                return $this->authenticatedUser = true;
            }
            else{
                return $this->authenticatedUser = false;
            }
        }
    }

    //Sparar tiden när kakan skapades i en fil.
    public function saveCookieTime($time){
        file_put_contents("exist.txt", $time);
    }

    public function getUsername(){
        if(isset($_SESSION['ValidLogin'])){
            return $_SESSION['ValidLogin'];
        }
        return false;
    }

    public function getPassword(){
        return $this->onlyPass;
    }

    public function cryptPass($passwordToCrypt, $rounds = 9){
        //krypterar lösenordet med blowfish, har följt denna guide https://www.youtube.com/watch?v=wIRtl8CwgIc
        //returnerar det krypterade lösenordet
        //OBS: blowfish verkar kräva nyare version av php. version.5.2.12 verkar ej funka...
        $salt = "";

        //skapar en lång array med alla (typ) tecken från alfabetet + siffrorna 0-9
        $saltChars = array_merge(range("A","Z"), range("a","z"), range(0,9));

        for($i=0;$i < 22;$i++){ // for loop som ska utföras 22 gånger, Blowfish behöver 22 blandade tecken..
            $salt .= $saltChars[array_rand($saltChars)];
            //För varje "varv" så läggs en slumpad karaktär från arrayen in i strängen $salt (22blandade tecken)
        }

        //Nu ska vi kryptera och returnera det krypterade lösenordet!
        return crypt($passwordToCrypt, sprintf("$2y$%02d$", $rounds) . $salt);//
        // "$2y$%02d$" är den delen som gör att vi krypterar genom blowfish, endast "$2y$" är nödvändigt
        // det andra är "extra saker" som killen i videon sa var bra (lite svårare att kryptera...)
    }

    public function checkIfPasswordIstrue($inputFromUser, $usersHashedPassword){
        //vi krypterar det inmatade lösenordet, testar om det är samma som användarens lösenord
        //om så är fallet så stämde lösenordet...

        $shouldBeSameAsHashed = crypt($inputFromUser, $usersHashedPassword);

        if($shouldBeSameAsHashed == $usersHashedPassword){
            return $this->authenticatedUser = true;
        }else{
            return $this->authenticatedUser = false;
        }
    }

    public function registerAuthentication($password, $repeatPass, $username){
        if(mb_strlen($username) < 3 && mb_strlen($password) < 6 && mb_strlen($repeatPass) <6){
            throw new UsernameAndPasswordToShortException();
        }
        if(mb_strlen($username) < 3){
            throw new UsernameToShortException();
        }
        if(preg_match($this->regExp, $username)){
            $username = preg_replace($this->regExp, "", $username);
            throw new UsernameContainsInvalidCharactersException($username);
        }
        if(mb_strlen($password) < 6 || mb_strlen($repeatPass) <6){
            throw new PasswordToShortException();
        }
        if($password != $repeatPass){
            throw new PasswordDontMatchException();
        }
        return true;
    }

    public function validateLogin($username, $password, $userAgent){
        $sql = "SELECT * FROM $this->dbTable WHERE " . self::$name . " = ?";
        $params = array($username);

        $query = $this->db->prepare($sql);
        $query->execute($params);

        //$result är en array som innehåller både användarnamn och lösenord.
        $result = $query->fetch();

        $this->username = $result["Username"];
        $this->onlyPass = $result["Password"];

        if($this->checkIfPasswordIstrue($password,$this->onlyPass)){
            if($this->authenticatedUser){
                $_SESSION["ValidLogin"] = $this->username;
                $_SESSION["UserAgent"] = $userAgent;
            }
        }
        return $this->authenticatedUser;
    }

    public function addUser(User $user){
        try{
            $sql = "INSERT INTO $this->dbTable (" . self::$name . ", " . self::$pass . ") VALUES (?, ?)";
            $params = array($user->getUsername(), $user->getPassword());

            $query = $this->db->prepare($sql);
            $query->execute($params);
        }
        catch(PDOException $e){
            die("Ett oväntat fel har uppstått");
        }
    }

    public function UserAlreadyExist($username){
        try{
            $sql = "SELECT * FROM $this->dbTable WHERE " . self::$name . " = ?";
            $params = array($username);

            $query = $this->db->prepare($sql);
            $query->execute($params);

            $result = $query->fetch();

            if($result["Username"] == $username){
                return true;
            }
        }
        catch(PDOException $e){
            die("Ett oväntat fel har uppstått");
        }
    }
}