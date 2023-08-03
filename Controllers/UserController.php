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
        // $user = new User();
        $old_email = '';
        $error = [];
        $message = '';

        // Traitement de l'email
        if (filter_var(trim($_POST['email'] ?? false), FILTER_VALIDATE_EMAIL)) {
            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

            $user = new User($email);

            if (!$user->getInitialized()) {
                $message = "Identifiant ou mot de passe incorrect!";
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

            if ($user ?? false && $user?->getInitialized()) {
                if ($user->verify_password($password)) {
                    $user->log();
                    return header('location: ' . $router->url('links.show'));
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

    public function registerAction(Router $router): Renderer
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
                $message = "Address Email alrady taken! <a href='" . $router->url('user.login') . "'>Login insted</a>";
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
                header('location: ' . $router->url('links.show'));
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

    public function logout(Router $router)
    {
        unset($_SESSION['auth']);
        return header('location: ' . $router->url('home'));
    }
}
