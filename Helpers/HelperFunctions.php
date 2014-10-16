<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-16
 * Time: 14:38
 */

class HelperFunctions{

    public function removeHtmlTags($string){
        $string = str_replace("<", "&lt;", $string);
        $string = str_replace(">", "&gt;", $string);
        return $string;
    }
}