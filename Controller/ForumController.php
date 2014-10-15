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
require_once('./Helpers/ServiceHelper.php');

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
    private $serviceHelper;

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
        $this->serviceHelper = new ServiceHelper();
    }

    public function doControl(){
        $userAgent = $this->serviceHelper->getUserAgent();
        $authenticated = $this->userModel->getAuthenticatedUser($userAgent);

        //Urler
        $signOutUrl = $this->navigationView->getLoggedOutUrl();
        $threadUrl = $this->navigationView->getThreadUrl();
        $loginUrl = $this->navigationView->getLoginUrl();
        $indexUrl = $this->navigationView->getIndexUrl();

        $username = $this->userModel->getUsername();

        //Om man ska logga in så kommer en annan vy där en annan contoller att göra det jobbet
        if($this->forumView->getLogin()){
            return $this->loginController->doControl();
        }

        //Om man ska regristrera sig så kommer man till en annan vy där en anna controller gör det jobbet
        if($this->forumView->getRegister()){
            return $this->registerController->doControl();
        }

        if($this->forumView->userPressedNewThread()){
            return $this->threadView->newThreadView($loginUrl);
        }

        //Användare trycker på create new thread så ska tråden sparas i en databas i en tabell och det man skriver in i
        //textrutan ska sparas till en annan tabell.
        if($this->threadView->userPressedCreate()){
            $this->threadView->getThreadInfromation();
            $threadName = $this->threadView->getThreadName();
            $thread = new Thread($threadName, $username);
            $this->threadRepository->addThread($thread);

            $id = $this->threadRepository->getThread($username);
            $content = $this->threadView->getContent();
            $post = new Post($content, $id, $username);
            $this->postRepository->addPost($post);

            $threads = $this->threadRepository->getAllThreads();

            return $this->forumView->forumView($signOutUrl, $username, $threads, $threadUrl, $authenticated);
        }

        //Om användaren vill redigera sin trådtitel så kommer man till samma vy som när man skapar en ny tråd
        if($this->forumView->userPressedEditThread()){
            $urlPath = $this->forumView->getUrl();
            $threadId = $this->forumView->getThreadId($urlPath);
            $thread = $this->threadRepository->getSingleThread($threadId);

            return $this->threadView->newThreadView($loginUrl, $threadId, $thread);
        }

        //Redigerar trådtiteln
        if($this->threadView->userPressedAlter()){
            $this->threadView->getThreadInfromation();
            $threadName = $this->threadView->getThreadName();
            $threadId = $this->threadView->getThreadId();
            $this->threadRepository->updateThread($threadName, $threadId);

            $threads = $this->threadRepository->getAllThreads();

            return $this->forumView->forumView($signOutUrl, $username, $threads, $threadUrl, $authenticated);
        }

        if($this->navigationView->userPressedThread()){
            $urlPath = $this->forumView->getUrl();
            $id = $this->forumView->getThreadId($urlPath);
            $posts = $this->postRepository->getThreadPost($id);

            return $this->threadView->showThreadPosts($posts, $loginUrl, $indexUrl, $authenticated, $username);
        }

        if($this->threadView->userPressedCreatePost()){
            $urlPath = $this->forumView->getUrl();
            $id = $this->forumView->getThreadId($urlPath);

            return $this->postView->newPostView($threadUrl, $id, $loginUrl);
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

            return $this->threadView->showThreadPosts($posts, $loginUrl, $indexUrl, $authenticated, $username);
        }

        //Frågar om man är säker att man vill ta bort en post
        if($this->threadView->userPressedDeletePost()){
            $url = $this->forumView->getUrl();
            $id = $this->forumView->getThreadId($url);
            return $this->forumView->confirmView($loginUrl, $id);
        }

        //Tar bort en post i en tråd från databasen
        if($this->forumView->userPressedYes()){
            $this->forumView->getPostId();
            $id = $this->forumView->getId();
            $this->postRepository->deletePost($id);

            $threads = $this->threadRepository->getAllThreads();

            return $this->forumView->forumView($signOutUrl, $username, $threads, $threadUrl, $authenticated);
        }

        if($this->forumView->adminPressedRemoveThread()){
            $urlPath = $this->forumView->getUrl();
            $threadId = $this->forumView->getThreadId($urlPath);
            $this->postRepository->deleteAllPostsFromThread($threadId);
            $this->threadRepository->deleteThread($threadId);

            $threads = $this->threadRepository->getAllThreads();

            return $this->forumView->forumView($signOutUrl, $username, $threads, $threadUrl, $authenticated);
        }

        //När man vill redigera en post kommer man till samma vy som man använder när man skapar en post
        if($this->threadView->userPressedEditPost()){
            $urlPath = $this->forumView->getUrl();
            $id = $this->forumView->getThreadId($urlPath);
            $post = $this->postRepository->getSinglePost($id);

            return $this->postView->newPostView($threadUrl, $id, $loginUrl, $post);
        }

        //Redigerar en post
        if($this->postView->userPressedAlterPost()){
            $this->postView->getPostInformation();
            $content = $this->postView->getContent();
            $id = $this->postView->getThreadId();
            $this->postRepository->updatePost($content, $id);

            $threads = $this->threadRepository->getAllThreads();

            return $this->forumView->forumView($signOutUrl, $username, $threads, $threadUrl, $authenticated);
        }

        $threads = $this->threadRepository->getAllThreads();
        return $this->forumView->forumView($signOutUrl, $username, $threads, $threadUrl, $authenticated);
    }
}