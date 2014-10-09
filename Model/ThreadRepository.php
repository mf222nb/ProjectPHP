<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-09
 * Time: 14:08
 */

class ThreadRepository extends Repository{
    private $db;
    private static $name = 'ThreadName';
    private static $content = 'Content';
    private static $user = 'UserId';

    public function __construct(){
        $this->db = $this->connection();
        $this -> dbTable = 'thread';
    }
}