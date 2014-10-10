<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-06
 * Time: 13:16
 */
require_once("./View/ForumView.php");
require_once("./View/ThreadView.php");
require_once("./View/NavigationView.php");
require_once("./Controller/LoginController.php");
require_once("./Controller/RegisterController.php");
require_once("./Model/UserModel.php");
require_once("./Model/ThreadRepository.php");
require_once("./Model/PostRepository.php");
require_once("./Model/Thread.php");
require_once("./Model/Post.php");

class ForumController{
    private $forumView;
    private $threadView;
    private $navigationView;
    private $loginController;
    private $registerController;
    private $userModel;
    private $threadRepository;
    private $postRepository;

    public function __Construct(){
        $this->userModel = new UserModel();
        $this->threadRepository = new ThreadRepository();
        $this->postRepository = new PostRepository();
        $this->forumView = new ForumView();
        $this->threadView = new ThreadView();
        $this->navigationView = new NavigationView();
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

        if($this->forumView->UserPressedNewThread()){
            $url = $this->navigationView->getLoginUrl();
            return $this->threadView->newThreadView($url);
        }

        //Användare trycker på create new thread så ska tråden sparas i en databas i en tabell och det man skriver in i
        //textrutan ska sparas till en annan tabell.
        if($this->threadView->userPressedCreate()){
            $this->threadView->getThreadInfromation();
            $threadName = $this->threadView->getThreadName();
            $username = $this->userModel->getUsername();
            $thread = new Thread($threadName, $username);
            $this->threadRepository->addThread($thread);

            $id = $this->threadRepository->getThread($username);
            $content = $this->threadView->getContent();
            $post = new Post($content, $id, $username);
            $this->postRepository->addPost($post);

            $signOutUrl = $this->navigationView->getLoggedOutUrl();
            return $this->forumView->loggedInForumView($signOutUrl, $username);
        }

        return $this->forumView->forumView();

    }
}