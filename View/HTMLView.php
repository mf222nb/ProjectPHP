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
                <meta charset = 'UTF-8'>
              </head>
              <body>
                $body
              </body>
              </html>";
    }
}