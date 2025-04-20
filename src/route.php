<?php

use Core\Router;

// Ajouter cette ligne pour définir la route racine
Router::connect('/', [
    'controller' => 'App',
    'action' => 'index'
]);

Router::connect('/user/register', [
    'controller' => 'User',
    'action' => 'test'
]);

Router::connect('/user/login', [
    'controller' => 'User',
    'action' => 'test'
]);
?>