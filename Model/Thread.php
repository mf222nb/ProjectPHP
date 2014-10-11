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
    private $threadId;

    public function __construct($threadName, $user, $threadId = null){
        $this->threadName = $threadName;
        $this->user = $user;
        $this->threadId = $threadId;
    }

    public function getThreadName(){
        return $this->threadName;
    }

    public function getUser(){
        return $this->user;
    }

    public function getThreadId(){
        return $this->threadId;
    }
}