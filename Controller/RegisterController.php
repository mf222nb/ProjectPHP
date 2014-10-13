<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 2014-09-25
 * Time: 16:22
 */
require_once("./View/RegisterUserView.php");
require_once("./Model/UserModel.php");
require_once("./Model/ThreadRepository.php");
require_once("./Helpers/UsernameToShortException.php");
require_once("./Helpers/PasswordToShortException.php");
require_once("./Helpers/PasswordDontMatchException.php");
require_once("./Helpers/UsernameContainsInvalidCharactersException.php");
require_once("./Helpers/UsernameAndPasswordToShortException.php");
require_once("./Model/User.php");
require_once("./View/ForumView.php");
require_once("./View/NavigationView.php");
require_once("./Controller/LoginController.php");

class RegisterController {
    private $registerUserView;
    private $userModel;
    private $threadRepository;
    private $view;
    private $navigationView;
    private $loginController;

    public function __construct(UserModel $userModel){
        $this->registerUserView = new RegisterUserView();
        $this->userModel = $userModel;
        $this->threadRepository = new ThreadRepository();
        $this->view = new ForumView();
        $this->navigationView = new NavigationView();
        $this->loginController = new LoginController($this->userModel);
    }

    public function doControl(){
        /*Om användaren trycker på Register knappen så hämtar vi ut information om vad användaren skrev in i fälten
        och sedan kollar vi så att de uppfyller alla kraven så som längd etc, och om de gör det så tittar vi om
        användarnamnet är ledigt. Är användarnamnet ledigt så krypterar vi lösenordet och sedan lägger till det i
        databasen och man kommer tillbaka till loginviewn.

        Om något går fel så får användaren ett felmeddelande av något slag.*/
        if($this->registerUserView->didUserPressRegister()){
            $this->registerUserView->getRegisterInformation();
            $username = $this->registerUserView->getUsername();
            $password = $this->registerUserView->getPassword();
            $repeatPass = $this->registerUserView->getRepeatPass();
            try{
                if($this->userModel->registerAuthentication($password, $repeatPass, $username)){
                    if($this->userModel->UserAlreadyExist($username)){
                        $this->registerUserView->usernameAlreadyExistMessage();
                    }
                    else{
                        $cryptPass = $this->userModel->cryptPass($password);
                        $user = new User($username, $cryptPass);
                        $this->userModel->addUser($user);
                        $this->view->userAddedToDataBaseMessage();
                        $threads = $this->threadRepository->getAllThreads();
                        $threadUrl = $this->navigationView->getThreadUrl();
                        return $this->view->forumView($threads, $threadUrl);
                    }
                }
            }
            catch(UsernameToShortException $e){
                $this->registerUserView->usernameToShortMessage();
            }
            catch(PasswordToShortException $e){
                $this->registerUserView->passwordToShortMessage();
            }
            catch(PasswordDontMatchException $e){
                $this->registerUserView->passwordDontMatchMessage();
            }
            catch(UsernameContainsInvalidCharactersException $e){
                $this->registerUserView->usernameContainInvalidCharacterMessage($e->getMessage());
            }
            catch(UsernameAndPasswordToShortException $e){
                $this->registerUserView->usernameAndPasswordToShortMessage();
            }
        }
        $ret = $this->registerUserView->registerUserView();

        return $ret;
    }
}