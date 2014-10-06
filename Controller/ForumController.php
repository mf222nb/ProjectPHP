<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-06
 * Time: 13:16
 */
require_once("./View/ForumView.php");
require_once("./Controller/LoginController.php");
require_once('./Controller/RegisterController.php');
require_once('./Model/UserModel.php');

class ForumController{
    private $forumView;
    private $loginController;
    private $registerController;
    private $userModel;

    public function __Construct(){
        $this->userModel = new UserModel();
        $this->forumView = new ForumView();
        $this->loginController = new LoginController($this->userModel);
        $this->registerController = new RegisterController($this->userModel);
    }

    public function doControl(){
        if($this->forumView->getLogin()){
            return $this->loginController->doControl();
        }
        if($this->forumView->getRegister()){
            return $this->registerController->doControl();
        }

        return $this->forumView->forumView();

    }
}