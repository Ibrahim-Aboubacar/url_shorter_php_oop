<?php

namespace Controllers;

use Models\Link;
use Models\User;
// use Router\Router;
use Source\App;
use Source\Renderer;

class LinkController
{
    public function index()
    {
        $vars = [
            'pageName' => 'links',
            'links' => (new link())->getUsersLinks($_SESSION['auth']['id'] ?? 0),
            'message' => '',
        ];

        return Renderer::make('links/index', $vars);
    }

    public function short($id)
    {
        $link = new Link($id);

        if ($link->getInitialized() && $link->getState()) {

            $link->incrementVisite();

            header('location: ' . $link->getOriginal_link());
            die;
        } else {
            return Renderer::error404('Page Not Found');
        }
    }

    public function new(): Renderer
    {
        $vars = [
            'pageName' => 'links',
            'old_name' => '',
            'old_link' => '',
            'old_state' => 1
        ];
        return Renderer::make('links/new', $vars);
    }

    public function edite(int $id): Renderer
    {
        $link = new link($id);
        // Throw a 404 error when we don't have a link or 
        // the link does not belong to the logedin user
        if (!$link->getInitialized() || !$link->belongsToUser(new User($_SESSION['auth']['id'] ?? 0))) return Renderer::error404('Link does not exit');

        $vars = [
            'pageName' => 'links',
            'link_id' => $link->getId(),
            'old_name' => $link->getName(),
            'old_link' => $link->getOriginal_link(),
            'old_state' => $link->getState(),
        ];

        return Renderer::make('links/edite', $vars);
    }

    public function delete(int $id): Renderer
    {
        $link = new link($id);
        // Throw a 404 error whwn we don't have a link or 
        // the link does not belong to the logedin user
        if (!$link->getInitialized() || !$link->belongsToUser(new User($_SESSION['auth']['id'] ?? 0))) return Renderer::error404('Link does not exit');

        $link->delete();

        http_response_code(302);
        return header('location: ' . App::getRouter()->url('links.show'));
    }

    public function store(): Renderer
    {
        $error = [];
        // Traitement de nom
        if (isset($_POST['name']) && strlen(trim($_POST['name']))) {
            $name = htmlentities(trim($_POST['name']));
            $old_name = $name;
        } else {
            $error['name'] = "Le Nom est requis!";
        }

        // Traitement de lien
        if (isset($_POST['link']) && filter_var(trim($_POST['link']), FILTER_VALIDATE_URL)) {
            $link = htmlentities(trim($_POST['link']));
            $old_link = $link;
        } else {
            $old_link = htmlentities(trim($_POST['link'] ?? ''));
            $error['link'] = "Le lien original est requis (un lien valide)!";
        }

        // Traitement de statu
        if (isset($_POST['state'])) {
            $state = trim($_POST['state']) == 'on' ? 1 : 0;
            $old_state = $state;
        } else {
            $state = 0;
            $old_state = $state;
        }

        if (!$error) {
            $linkModel = new Link();

            $res = $linkModel->save(
                [
                    'name',
                    'original_link',
                    'state',
                    'user',
                ],
                [
                    $name,
                    $link,
                    $state,
                    $_SESSION['auth']['id']
                ]
            );

            if ($res) {
                http_response_code(200);
                header('location: ' . App::getRouter()->url('links.show'));
                die;
            } else {
                $message = "Une erreur s'est produite, veuillez réessayer s'il vous plait!";
            }
        }

        $vars = [
            'pageName' => 'links',
            'old_name' => $old_name ?? '',
            'old_link' => $old_link ?? '',
            'old_state' => $old_state ?? 1,
            'message' => $message ?? null,
            'error' => $error ?? [],
        ];
        return Renderer::make('links/new', $vars);
    }

    public function update(int $id): Renderer
    {
        $link = new Link($id);
        // Throw a 404 error whwn we don't have a link or 
        // the link does not belong to the logedin user
        if (!$link->getInitialized() || !$link->belongsToUser(new User($_SESSION['auth']['id']))) return Renderer::error404('Link does not exit');


        $link_id = $link->getId();
        $old_name = $link->getName();
        $old_link = $link->getOriginal_link();
        $old_state = $link->getState();

        // Traitement de nom
        if (isset($_POST['name']) && strlen(trim($_POST['name']))) {
            $name = htmlentities(trim($_POST['name']));
            $old_name = $name;
        } else {
            $message = "Le Nom est requis!";
            $error['name'] = $message;
        }

        // Traitement de lien
        if (isset($_POST['link']) && filter_var(trim($_POST['link']), FILTER_VALIDATE_URL)) {
            $link_p = (trim($_POST['link']));

            $old_link = $link_p;
        } else {
            $message = "Le lien original est requis (un lien valide)!";
            $error['link'] = $message;
        }

        // Traitement de statu
        if (isset($_POST['state'])) {
            $state = trim($_POST['state']) == 'on' ? 1 : 0;
            $old_state = $state;
        } else {
            $state = 0;
            $old_state = $state;
        }

        if (!$error ?? true) {


            $link->setName($old_name);
            $link->setOriginal_link($old_link);
            $link->setState($old_state);

            $res = $link->update();

            if ($res) {
                http_response_code(202);
                header('location: ' . App::getRouter()->url('links.show'));
                die;
            } else {
                http_response_code(422);
                $message = "Une erreur s'est produite, veuillez réessayer s'il vous plait!";
            }
        }

        $vars = [
            'pageName' => 'links',
            'link_id' => $link_id,
            'old_name' => $old_name,
            'old_link' => $old_link,
            'old_state' => $old_state,
            'message' => $message ?? '',
            'error' => $error ?? [],
        ];

        http_response_code(422);
        return Renderer::make('links/edite', $vars);
    }
}
