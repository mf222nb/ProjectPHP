<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-09
 * Time: 13:27
 */

class ThreadView{

    public function newThreadView(){
        $ret = "
        <form method='post' action='?' id='threadForm'>
            Thread name: <input type='text' name='name'>
            <input type='submit' value='create' name='submit'>
        </form>

        <textarea name='content' form='threadForm'>Enter text here...</textarea>";

        return $ret;
    }
}