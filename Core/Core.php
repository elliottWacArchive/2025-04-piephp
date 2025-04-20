<?php

// namespace Core;

// class Core
// {
//     public function run()
//     {
//         // Inclure le fichier des routes
//         require_once __DIR__ . '/../src/route.php';
        
//         // Obtenir l'URL demandée
//         $uri = $_SERVER['REQUEST_URI'];
//         // echo "<p>URI original: " . htmlspecialchars($uri) . "</p>";
        
//         // Utilisez la bonne casse pour PiePHP
//         $uri = str_replace('/PiePHP', '', $uri);
//         // echo "<p>URI après transformation: " . htmlspecialchars($uri) . "</p>";
        
//         // Si l'URI est vide, utilisez "/"
//         if ($uri === '') {
//             $uri = '/';
//         }
        
//         // Gérer les paramètres GET
//         $uriParts = explode('?', $uri);
//         $path = $uriParts[0];
//         // echo "<p>Chemin recherché: " . htmlspecialchars($path) . "</p>";
        
//         // Afficher toutes les routes disponibles
//         // echo "<p>Routes disponibles: </p><pre>";
//         // print_r(Router::getRoutes());
//         // echo "</pre>";
        
//         // Chercher la route dans les routes définies
//         $route = Router::get($path);
//         // echo "<p>Route trouvée: </p><pre>";
//         // print_r($route);
//         // echo "</pre>";
        
//         if ($route) {
//             $controller = 'Controller\\' . $route['controller'] . 'Controller';
//             $action = $route['action'];
            
//             // echo "<p>Contrôleur à instancier: " . htmlspecialchars($controller) . "</p>";
//             // echo "<p>Action à appeler: " . htmlspecialchars($action) . "</p>";
            
//             // Instancier le contrôleur et appeler l'action
//             if (class_exists($controller)) {
//                 $controllerInstance = new $controller();
//                 if (method_exists($controllerInstance, $action)) {
//                     $controllerInstance->$action();
//                     return;
//                 } else {
//                     echo "<p>Erreur: La méthode " . htmlspecialchars($action) . " n'existe pas dans " . htmlspecialchars($controller) . "</p>";
//                 }
//             } else {
//                 echo "<p>Erreur: Le contrôleur " . htmlspecialchars($controller) . " n'existe pas</p>";
//             }
//         } else {
//             // Si aucune route n'est trouvée
//             header("HTTP/1.0 404 Not Found");
//             include __DIR__ . '/../src/View/Error/404.php';
//         }
//     }
// }

namespace Core;

class Core
{
    public function run()
    {
        // Obtenir l'URL demandée
        $uri = $_SERVER['REQUEST_URI'];
        
        // Vérifier si on utilise le format de requête GET
        if (isset($_GET['c']) && isset($_GET['a'])) {
            $controller = $_GET['c'];
            $action = $_GET['a'];
        } else {
            // Utilisez la bonne casse pour PiePHP
            $uri = str_replace('/PiePHP/', '', $uri);
            $uri = trim($uri, '/');
            
            // Si l'URI est vide, utiliser les valeurs par défaut
            if ($uri === '') {
                $controller = 'app';
                $action = 'index';
            } else {
                // Découper l'URI pour extraire le controller et l'action
                $parts = explode('/', $uri);
                
                // Le premier segment est le controller
                $controller = $parts[0] ?? 'app';
                
                // Le deuxième segment est l'action, s'il existe
                $action = $parts[1] ?? 'index';
            }
        }
        
        // Formater le nom du controller
        $controllerName = ucfirst(strtolower($controller)) . 'Controller';
        $controllerClass = 'Controller\\' . $controllerName;
        
        // Formater le nom de l'action
        $actionName = strtolower($action) . 'Action';
        
        try {
            // Vérifier si le controller existe
            if (class_exists($controllerClass)) {
                $controllerInstance = new $controllerClass();
                if (method_exists($controllerInstance, $actionName)) {
                    $controllerInstance->$actionName();
                    return;
                } else {
                    // Si l'action n'existe pas, essayer indexAction
                    if (method_exists($controllerInstance, 'indexAction')) {
                        $controllerInstance->indexAction();
                        return;
                    }
                }
            } else {
                // Si le controller n'existe pas, essayer AppController
                $defaultClass = 'Controller\\AppController';
                if (class_exists($defaultClass)) {
                    $controllerInstance = new $defaultClass();
                    if (method_exists($controllerInstance, 'indexAction')) {
                        $controllerInstance->indexAction();
                        return;
                    }
                }
            }
            
            // Si on arrive ici, c'est que ni le controller/action, ni les valeurs par défaut n'existent
            throw new \Exception("Page non trouvée");
            
        } catch (\Exception $e) {
            // Afficher une erreur 404 propre
            header("HTTP/1.0 404 Not Found");
            if (file_exists(__DIR__ . '/../src/View/Error/404.php')) {
                include __DIR__ . '/../src/View/Error/404.php';
            } else {
                echo "<h1>404 - Page non trouvée</h1>";
                echo "<p>La page que vous recherchez n'existe pas.</p>";
            }
        }
    }
}