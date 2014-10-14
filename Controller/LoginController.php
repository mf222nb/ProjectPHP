<?php

require_once('./View/LoginView.php');
require_once('./Model/UserModel.php');
require_once('./Model/ThreadRepository.php');
require_once('./View/ForumView.php');
require_once('./View/CookieView.php');
require_once('./Helpers/ServiceHelper.php');
require_once('./View/NavigationView.php');

class LoginController{
    private $loginView;
    private $userModel;
    private $threadRepository;
    private $cookieView;
    private $serviceHelper;
    private $navigationView;
    private $forumView;

    public function __construct(UserModel $userModel){
        $this->loginView = new LoginView();
        $this->userModel = $userModel;
        $this->threadRepository = new ThreadRepository();
        $this->cookieView = new CookieStorage();
        $this->serviceHelper = new ServiceHelper();
        $this->navigationView = new NavigationView();
        $this->forumView = new ForumView();
    }

    public function doControl(){
        $userAgent = $this->serviceHelper->getUserAgent();

        /*När användaren trycker på logga in knappen så hämtas information ut om vad användaren skrev in för lösenord och
        användarnamn och skickar den vidare för att kontrollera om dem är korrekta och i sådana fall loggas man in annars
        visas ett felmeddelande.
        Vill användaren att man ska komma ihåg den så skapas en cookie och sparar en identifierare i cookien så att man vet
        vilken användare som är inloggad. */
        if($this->loginView->getSubmit()){
            $this->loginView->getInformation();
            $username = $this->loginView->getUsername();
            $password = $this->loginView->getPassword();
            $realAgent = $this->serviceHelper->getUserAgent();
            if(!$this->userModel->validateLogin($username, $password, $realAgent)){
                $this->loginView->failedLogIn($username, $password);
            }
            else {
                if($this->loginView->wantCookie()){
                    $username = $this->userModel->getUsername();
                    $password = $this->userModel->getPassword();
                    $time = $this->cookieView->save($username, $password);
                    $this->userModel->saveCookieTime($time, $username);
                    $message = $this->cookieView->cookieSaveMessage();
                    $this->forumView->setMessage($message);
                }
                else{
                    $message = $this->loginView->LogInSuccessMessage();
                    $this->forumView->setMessage($message);
                }
            }
        }

        /*Tittar om användaren redan är inloggad med sessioner och om den inte är det så laddas cookie in och kollar om
        det finns och om det inte gör det så visas ett felmeddelande, men om cookie skulle finnas på klienten så jämförs
        dem och tittar så att identifieraren i cookien stämmer överens med den på servern och om de gör det så loggas man
        in annars visas ett felmeddelande.*/
        $authenticated = $this->userModel->getAuthenticatedUser($userAgent);
        if($authenticated === false){
            if($this->cookieView->loadCookie()){
                $cookieValue = $this->cookieView->cookieExist();
                $username = $cookieValue['Username'];
                $password = $cookieValue['Password'];
                if($this->userModel->controlCookieValue($username, $password, $userAgent)){
                    $message = $this->cookieView->cookieLoadMessage();
                    $this->forumView->setMessage($message);
                }
                else{
                    $this->cookieView->deleteCookie();
                    $message = $this->cookieView->cookieModifiedMessage();
                    $this->loginView->setMessage($message);
                }
            }
        }

        /*Om användaren är inloggad ska anvämdaren kunna logga ut och om den trycker på logga ut så tas cookien bort om de
        finns på klienten tillsammans med sessionen och man får ett meddelande att man har loggat ut. */
        $authenticated = $this->userModel->getAuthenticatedUser($userAgent);
        if($authenticated === true){
            $userLogOut = $this->forumView->userPressedLogOut();
            if($userLogOut === true){
                $this->cookieView->deleteCookie();
                $message = $this->loginView->logOutSuccessMessage();
                $this->loginView->setMessage($message);
                $this->userModel->LogOut();
            }
        }
        $indexUrl = $this->navigationView->getIndexUrl();
        $loggedInUrl = $this->navigationView->getLoggedInUrl();
        $loginView = $this->loginView->ViewLogin($indexUrl, $loggedInUrl);

        $signOutUrl = $this->navigationView->getLoggedOutUrl();
        $username = $this->userModel->getUsername();
        $threads = $this->threadRepository->getAllThreads();
        $threadUrl = $this->navigationView->getThreadUrl();
        $forumView = $this->forumView->forumView($signOutUrl, $username, $threads, $threadUrl, $authenticated);

        $authenticated = $this->userModel->getAuthenticatedUser($userAgent);

        if($authenticated === true){
            //Returnerar den inloggade vyn.
            return $forumView;
        }
        else{
            //Returnerar den icke inloggade vyn.
            return $loginView;
        }
    }
}