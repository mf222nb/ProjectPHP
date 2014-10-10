<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-10
 * Time: 14:55
 */

class Thread{
    private $threadName;
    private $user;

    public function __construct($threadName, $user){
        $this->threadName = $threadName;
        $this->user = $user;
    }

    public function getThreadName(){
        return $this->threadName;
    }

    public function getUser(){
        return $this->user;
    }
}