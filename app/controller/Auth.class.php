<?php

class AuthController extends Controller {

    const DEPENDENCIES = ['Router', 'Flash', 'Auth', 'Session'];

    public function index() {
        // If user go to auth/
        $this->router->go('auth/login');
    }

    public function loginPost($request, $post) {
        // Attempt to login
        if ($this->auth->login($post['username'], $post['password'])) {

            // Add a message
            $this->flash->set(true, 'Connexion réussie.');

            // Go either to panel or to calendar
            $this->router->go();

        // Error
        } else {

            // Add a message
            $this->flash->set(false, 'Mauvais nom d\'utilisateur ou mot de passe.');

        }
    }

    public function loginGet($request) {
        // User is logged in
        if ($this->auth->isLogged()) {

            // Go either to calendar or panel
            $this->router->go();

        }
    }

    public function logout($request) {

        // User is logged in
        if ($this->auth->isLogged()) {

            // Logout user
            $this->auth->logout();

            // Add a message
            $this->flash->set(true, 'Vous vous êtes déconnecté.');

        } else {

            // Logout to delete session
            $this->auth->logout();

            // Add a message
            $this->flash->set(false, 'Vous n\'étiez pas connecté.');

        }

        $this->router->go('auth/login');

    }

    public function settingsGet($request) {}

    public function settingsPost($request, $post) {
        if ($post['password'] === $post['confirm']) {
            try {
                $user = $this->auth->current();
                $user->setPassword($post['password']);
                $this->auth->setUser($user);
                $this->flash->set(true, 'Mot de passe mis à jour !');
            } catch (Exception $e) {
                $this->flash->set(false, $e->getMessage());
            }
            return; 
        }
        $this->flash->set(false, 'Les mots de passes sont différents.');
    }

}
