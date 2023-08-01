<?php

namespace Controllers;

use Models\User;
use Router\Router;
use Source\Renderer;

class UserController
{
    public function login(): Renderer
    {
        $vars = [
            'pageName' => 'login',
            'old_email' => ''

        ];
        return Renderer::make('users/login', $vars);
    }

    public function loginAction(Router $router): Renderer
    {
        /**
         * @var User $user Une instance de la classe User.
         */
        $user = new User();
        $old_email = '';
        $error = [];
        $message = '';

        // Traitement de l'email
        if (isset($_POST['email']) && filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

            $user = new User($email);

            if (!$user->getInitialized()) {
                $message = "Identifiant ou mot de passe incorrect!";
                $error['email'] = $message;
            }
            $old_email = $email;
        } else {;
            $error['email'] = "Le champs Email est requis!";
        }


        // Traitement de password
        if (isset($_POST['password']) && strlen(trim($_POST['password']))) {
            $password = trim($_POST['password']);

            if ($user->getInitialized()) {
                if ($user->verify_password($password)) {
                    $user->log();
                    return header('location: ' . $router->url('links.show'));
                } else {
                    $message = "Identifiant ou mot de passe incorrect!";
                }
            }
        } else {
            $error['password'] = "Le champs Mot de passe est requis!";
        }

        $vars = [
            'pageName' => 'login',
            'old_email' => $old_email,
            'error' => $error,
            'message' => $message,
        ];
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
            // 'message' => '',

        ];
        return Renderer::make('users/register', $vars);
    }

    public function registerAction(Router $router): Renderer
    {
        $error = [];
        $message = '';

        // Traitement de username
        if (isset($_POST['username']) && strlen(trim($_POST['username']))) {
            $username = htmlentities(trim($_POST['username']));
            $old_username = $username;
        } else {
            $error['username'] = "Le champs Nom d'utilisateur est requis!";
        }

        // Traitement de l'email
        if (isset($_POST['email']) && filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

            $userTest = new User($email);

            if ($userTest->getInitialized()) {
                $message = "Address Email alrady taken!";
                $error['email'] = $message;
            }

            $old_email = $email;
        } else {;
            $error['email'] = "Le champs Email est requis (Et doit être valide)!";
        }


        // Traitement de password
        if (isset($_POST['password']) && strlen(trim($_POST['password'])) > 4) {
            $password = trim($_POST['password']);
            $old_password = $password;
            if (isset($_POST['c_password']) && strlen(trim($_POST['c_password'])) > 4) {
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
                header('location: ' . $router->url('links.show'));
                die;
            } else {
                $message = "Une erreur s'est produite, veuillez réessayer s'il vous plait!";
            }
        }

        $vars = [
            'pageName' => 'register',
            'old_username' => $old_username ?? '',
            'old_email' => $old_email ?? '',
            'old_password' => $old_password ?? '',
            'error' => $error,
            'message' => $message,
        ];
        http_response_code(422);
        return Renderer::make('users/register', $vars);
    }

    public function logout(Router $router)
    {
        unset($_SESSION['auth']);
        return header('location: ' . $router->url('home'));
    }
}
