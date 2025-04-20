<?php
// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Charger l'autoloader
require_once 'Core/autoload.php';

// Démarrer l'application
$app = new Core\Core();
$app->run();
?>