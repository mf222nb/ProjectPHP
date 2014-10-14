<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-10
 * Time: 15:48
 */

class Post{
    private $content;
    private $threadId;
    private $user;
    private $postId;

    public function __construct($content, $threadId, $user, $postId = null){
        $this->content = $content;
        $this->threadId = $threadId;
        $this->user = $user;
        $this->postId = $postId;
    }

    public function getContent(){
        return $this->content;
    }

    public function getThreadId(){
        return $this->threadId;
    }

    public function getUser(){
        return $this->user;
    }

    public function getPostId(){
        return $this->postId;
    }
}