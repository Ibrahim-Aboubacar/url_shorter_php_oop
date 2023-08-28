<?php

namespace Controllers;

use DateTime;
use Models\Link;
use Source\App;
use Models\User;
use Router\Router;
use Source\Constant;
use Source\Renderer;

class UserController
{
    public function login(): Renderer
    {
        $user = (new User())->where('email = :email AND (deleted_at + INTERVAL :mois DAY) <=  (CURRENT_DATE())')->setParam('mois', Constant::NBR_MOIS_DELETE_ACCOUNT * 30)->setParam('email', 'adminn@gmail.com')->fetch();


        $vars = [
            'pageName' => 'login',
            'old_email' => ''
        ];
        return Renderer::make('users/login', $vars);
    }

    public function loginAction(): Renderer
    {
        /**
         * @var User $user Une instance de la classe User.
         */
        // $user = new User();
        $old_email = '';
        $error = [];
        $message = '';

        // Traitement de l'email
        if (filter_var(trim($_POST['email'] ?? false), FILTER_VALIDATE_EMAIL)) {
            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
            // created_at >= (CURRENT_DATE() - INTERVAL 30 DAY)
            // $user = (new User())->where('email = :email AND (deleted_at + INTERVAL :mois DAY) <=  (CURRENT_DATE())')->setParam('mois', Constant::NBR_MOIS_DELETE_ACCOUNT * 30)->setParam('email', 'adminn@gmail.com')->fetch();
            $user = (new User($email));
            if (!$user->getInitialized()) {
                $message = "Identifiant ou mot de passe incorrect!";
                $error['email'] = $message;
            }
            if (!$user->checkValidity()) {
                $message = "Ce compte a été supprimé, veuillez contacter le centre d'assistance pour le réactiver!";
                $error['email'] = $message;
            }
            $old_email = $email;
        } else {;
            $error['email'] = "Le champs Email est requis!";
            $message = "Veuillez renseigner des information correct!";
        }

        // Traitement de password
        if (strlen(trim($_POST['password'] ?? false))) {
            $password = trim($_POST['password']);
            if (($user ?? false) && $user->getInitialized() && $user->checkValidity()) {
                if ($user->verify_password($password)) {
                    $user->log();
                    return header('location: ' . App::getRouter()->url('links.show'));
                } else {
                    $message = "Identifiant ou mot de passe incorrect!";
                }
            }
        } else {
            $error['password'] = "Le champs Mot de passe est requis!";
            $message = "Veuillez renseigner des information correct!";
        }

        $vars = [
            'pageName' => 'login',
            'old_email' => $old_email,
            'error' => $error,
            'message' => $message,
        ];

        //422 Unprocessable Content
        // The HyperText Transfer Protocol (HTTP) 422 Unprocessable Content 
        // response status code indicates that the server 
        // understands the content type of the request entity,
        // and the syntax of the request entity is correct,
        // but it was unable to process the contained instructions. 
        http_response_code(422);
        return Renderer::make('users/login', $vars);
    }

    public function register(): Renderer
    {
        $vars = [
            'pageName' => 'register',
            'old_username' => '',
            'old_email' => '',
            'old_password' => '',

        ];
        return Renderer::make('users/register', $vars);
    }

    public function registerAction(): Renderer
    {
        // $error = [];
        $message = '';

        // Traitement de username
        if (strlen(trim($_POST['username'] ?? false))) {
            $username = htmlentities(trim($_POST['username']));
            $old_username = $username;
        } else {
            $error['username'] = "Le champs Nom d'utilisateur est requis!";
        }

        // Traitement de l'email
        if (filter_var(trim($_POST['email'] ?? false), FILTER_VALIDATE_EMAIL)) {
            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

            $userTest = new User($email);

            if ($userTest->getInitialized()) {
                $message = "Address Email alrady taken! <a href='" . App::getRouter()->url('user.login') . "'>Login insted</a>";
                $error['email'] = $message;
            }

            $old_email = $email;
        } else {;
            $error['email'] = "Le champs Email est requis (Et doit être valide)!";
        }

        // Traitement de password
        if (strlen(trim($_POST['password'] ?? false)) > 4) {
            $password = trim($_POST['password']);
            $old_password = $password;
            if (strlen(trim($_POST['c_password'] ?? false)) > 4) {
                if ($password !== trim($_POST['c_password'])) {
                    $error['password'] = "Les Mots de passe ne correspondent pas!";
                    $error['c_password'] = "Les Mots de passe ne correspondent pas!";
                }
            } else {
                $error['c_password'] = "Le champs Mot de passe de confirmation est requis (min:4)!";
            }
        } else {
            $error['password'] = "Le champs Mot de passe est requis (min:4)!";
            $error['c_password'] = "   ";
        }

        if (!$error) {
            $user = new User();

            $user->save(
                [
                    'email',
                    'username',
                    'password',
                ],
                [
                    $email,
                    $username,
                    User::hash_password($password)
                ]
            );

            if ($user->getInitialized()) {
                $user->log();
                header('location: ' . App::getRouter()->url('links.show'));
                die;
            } else {
                $message = "Une erreur s'est produite, veuillez réessayer s'il vous plait!";
            }
        }

        if (!$message) $message = "Une erreur s'est produite, veuillez renseigner des information valide!!!";

        $vars = [
            'pageName' => 'register',
            'old_username' => $old_username ?? '',
            'old_email' => $old_email ?? '',
            'old_password' => $old_password ?? '',
            'error' => $error ?? [],
            'message' => $message,
        ];

        //422 Unprocessable Content
        // The HyperText Transfer Protocol (HTTP) 422 Unprocessable Content 
        // response status code indicates that the server 
        // understands the content type of the request entity,
        // and the syntax of the request entity is correct,
        // but it was unable to process the contained instructions. 
        http_response_code(422);
        return Renderer::make('users/register', $vars);
    }

    public function logout()
    {
        unset($_SESSION['auth']);
        return header('location: ' . App::getRouter()->url('home'));
    }

    public function profile()
    {
        $user = new User($_SESSION['auth']['id']);
        if (!$user->getInitialized()) {
            Renderer::error404('Compte non trouvé!!!');
        }

        $vars = [
            'pageName' => 'suprimer mon compte',
            'old_username' => $user->getUsername(),
            'old_email' => $user->getEmail(),
            'old_anc_password' => '',
            'message' => '',
            'error' => [],
        ];
        return Renderer::make('users/profile', $vars);
    }

    public function profileInfoAction()
    {

        $error = [];
        $message = '';

        // Traitement de username
        if (strlen(trim($_POST['username'] ?? false))) {
            $username = htmlentities(trim($_POST['username']));
            $old_username = $username;
        } else {
            $error['username'] = "Le champs Nom d'utilisateur est requis!";
        }

        // Traitement de l'email
        if (filter_var(trim($_POST['email'] ?? false), FILTER_VALIDATE_EMAIL)) {
            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

            $userTest = new User($email);

            if ($userTest->getInitialized() && $userTest->getEmail() !== $_SESSION['auth']['email']) {
                $message = "Address Email alrady taken!";
                $error['email'] = $message;
            }

            $old_email = $email;
        } else {;
            $error['email'] = "Le champs Email est requis (Et doit être valide)!";
        }

        if (!$error) {
            $user = new User($_SESSION['auth']['id']);

            if ($user->getInitialized()) {
                $user->setUsername($username);
                $user->setEmail($email);
                $user->update();
                $user->log();
                header('location: ' . App::getRouter()->url('user.profile'));
                die;
            } else {
                $message = "Une erreur s'est produite, veuillez réessayer s'il vous plait!";
            }
        }

        if (!$message) $message = "Une erreur s'est produite, veuillez renseigner des information valide!!!";

        $vars = [
            'pageName' => 'profile',
            'old_username' => $old_username ?? '',
            'old_email' => $old_email ?? '',
            'old_anc_password' => '',
            'message' => $message ?? '',
            'error' => $error ?? [],
        ];
        return Renderer::make('users/profile', $vars);
    }

    public function profilePasswordAction()
    {

        // $error = [];
        $message = '';
        $user = new User($_SESSION['auth']['id']);

        // Traitement de password
        if (strlen(trim($_POST['anc_password'] ?? false))) {
            $anc_password = trim($_POST['anc_password']);
            if (($user ?? false) && $user->getInitialized()) {
                if ($user->verify_password($anc_password)) {
                    // $user->log();
                    // return header('location: ' . App::getRouter()->url('links.show'));
                } else {
                    $message = "Mot de passe actuel incorrect!";
                    $error['anc_password'] = $message;
                }
            }
        } else {
            $error['anc_password'] = "Le Mot de passe actuel est requis!";
            $message = "Veuillez renseigner des information correct!";
        }

        // Traitement de password
        if (strlen(trim($_POST['password'] ?? false)) > 4) {
            $password = trim($_POST['password']);
            // $old_password = $password;
            if (strlen(trim($_POST['c_password'] ?? false)) > 4) {
                if ($password !== trim($_POST['c_password'])) {
                    $error['password'] = "Les Mots de passe ne correspondent pas!";
                    $error['c_password'] = "Les Mots de passe ne correspondent pas!";
                }
            } else {
                $error['c_password'] = "Le champs Mot de passe de confirmation est requis (min:4)!";
            }
        } else {
            $error['password'] = "Le champs Mot de passe est requis (min:4)!";
            $error['c_password'] = "   ";
        }

        if (!$error) {
            if ($user->getInitialized()) {
                $user->setPassword(User::hash_password($password));
                $user->update();
                // $user->logout();
                unset($_SESSION['auth']);

                header('location: ' . App::getRouter()->url('user.login'));
                die;
            } else {
                $message = "Une erreur s'est produite, veuillez réessayer s'il vous plait!";
            }
        }

        if (!$message) $message = "Une erreur s'est produite, veuillez renseigner des information valide!!!";

        $vars = [
            'pageName' => 'profile',
            'old_username' => $user->getUsername() ?? '',
            'old_email' => $user->getEmail() ?? '',
            'old_anc_password' => htmlentities($anc_password ?? ''),
            'message' => $message ?? '',
            'error' => $error ?? [],
        ];
        return Renderer::make('users/profile', $vars);
    }

    public function profileDelete()
    {
        $vars = [
            'pageName' => 'profile',
            'old_username' => '',
            'old_email' => '',
            'old_password' => '',
            'message' => '',
            'error' => [],
        ];
        return Renderer::make('users/delete', $vars);
    }

    public function profileDeleteAction()
    {
        $user = new User($_SESSION['auth']['id']);
        if (!$user->getInitialized()) {
            Renderer::error404('Compte non trouvé!!!');
        }
        if (!$user->verify_password($_POST['password'] ?? '')) {
            $vars = [
                'pageName' => 'Profile',
                'old_username' => $user->getUsername(),
                'old_email' => $user->getEmail(),
                'old_anc_password' => '',
                'message' => 'Mot de passe incorrect. Confirmation echouée pour suprimer le compte!',
                'error' => [],
            ];
            return Renderer::make('users/profile', $vars);
        }

        $Userlinks = (new link())->getUsersLinks($user->getId());
        foreach ($Userlinks as $key => $link) {
            /**
             * @var Link $link
             */
            $link = new Link($link->id);
            $link->setState(0);
            $link->update();
        }
        $user->setDeleted_at((new DateTime())->format('Y-m-d H:i:s'));
        $user->setDelete_comment(htmlentities(trim($_POST['comment'] ?? '')));
        $user->update();

        unset($_SESSION['auth']);

        return header('location: ' . App::$router->url('home'));
        die;

        $vars = [
            'pageName' => 'suprimer mon compte',
            'old_username' => $user->getUsername(),
            'old_email' => $user->getEmail(),
            'old_anc_password' => '',
            'message' => '',
            'error' => [],
        ];
        return Renderer::make('users/profile', $vars);
    }
}
