<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-09
 * Time: 13:27
 */

class ThreadView{
    private $threadName;
    private $content;

    public function newThreadView($url){
        $ret = "
        <a href='$url'>Back</a>
        <form method='post' action='?' id='threadForm'>
            Thread name: <input type='text' name='name'>
            <input type='submit' value='Create new thread' name='createThread'>
        </form>

        <textarea name='content' form='threadForm'></textarea>";

        return $ret;
    }

    public function getThreadInfromation(){
        if(isset($_POST['name'])){
            $this->threadName = $_POST['name'];
        }

        if(isset($_POST['content'])){
            $this->content = $_POST['content'];
        }
    }

    public function userPressedCreate(){
        if(isset($_POST['createThread'])){
            return true;
        }
        return false;
    }

    public function getThreadName(){
        return $this->threadName;
    }

    public function getContent(){
        return $this->content;
    }
}