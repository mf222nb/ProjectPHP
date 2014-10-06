<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-06
 * Time: 13:09
 */

require_once("Controller/ForumController.php");
require_once("View/HTMLView.php");

$view = new HTMLView();
$controll = new ForumController();

$forumView = $controll->doControl();
$view->echoHTML($forumView);