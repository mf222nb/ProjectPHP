<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-06
 * Time: 13:18
 */

class HTMLView{

    public function echoHTML($body){
        echo "<!DOCTYPE html>
              <html>
              <head>
                <link rel='stylesheet' type='text/css' href='./Style/main.css'>
                <meta charset = 'UTF-8'>
                <title>Forum</title>
              </head>
              <body>
                $body
              </body>
              </html>";
    }
}