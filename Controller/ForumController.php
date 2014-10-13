<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-10-06
 * Time: 13:16
 */
require_once("./View/ForumView.php");
require_once("./View/ThreadView.php");
require_once("./View/PostView.php");
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
    private $postView;
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
        $this->postView = new PostView();
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
        $threads = $this->threadRepository->getAllThreads();
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
            $threadUrl = $this->navigationView->getThreadUrl();
            return $this->forumView->loggedInForumView($signOutUrl, $username, $threads, $threadUrl);
        }

        if($this->navigationView->userPressedThread()){
            $urlPath = $this->forumView->getUrl();
            $id = $this->forumView->getThreadId($urlPath);
            $posts = $this->postRepository->getThreadPost($id);
            $url = $this->navigationView->getLoginUrl();

            return $this->forumView->showThreadPosts($posts, $url);
        }

        if($this->forumView->userPressedCreatePost()){
            $url = $this->navigationView->getThreadUrl();
            $urlPath = $this->forumView->getUrl();
            $id = $this->forumView->getThreadId($urlPath);
            return $this->postView->newPostView($url, $id);
        }

        //Lägger till en post i en tråd
        if($this->postView->userPressedCreatePost()){
            $this->postView->getPostInformation();
            $id = $this->postView->getThreadId();
            $content = $this->postView->getContent();
            $user = $this->userModel->getUsername();

            $post = new Post($content, $id, $user);
            $this->postRepository->addPost($post);

            $posts = $this->postRepository->getThreadPost($id);
            $url = $this->navigationView->getLoginUrl();
            return $this->forumView->showThreadPosts($posts, $url);
        }

        $threadUrl = $this->navigationView->getThreadUrl();
        return $this->forumView->forumView($threads, $threadUrl);
    }
}