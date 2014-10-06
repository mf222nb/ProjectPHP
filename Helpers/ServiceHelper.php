<?php

class ServiceHelper{

    //Hämtar ut information om användaren så som vilken webbläsare de sitter i och på vilket operativsystem som de använder.
    public function getUserAgent(){
        return $_SERVER['HTTP_USER_AGENT'];
    }
}
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-09-16
 * Time: 12:40
 */ 