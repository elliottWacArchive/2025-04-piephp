<?php

namespace Core;

class Controller
{
    protected static $_render;
    
    public function __construct()
    {
        // echo __CLASS__ . ' [OK] ' . PHP_EOL;
    }
    protected function render($view, $scope = [])
    {
        // Extraction des variables pour les rendre disponibles dans la vue
        extract($scope);
        
        // Détermination du nom du contrôleur actuel
        $className = get_class($this);
        $parts = explode('\\', $className);
        $controllerName = str_replace('Controller', '', end($parts));
        
        // Construction du chemin vers la vue spécifique en évitant la duplication de "src"
        $rootPath = dirname(dirname(__FILE__));
        $viewPath = $rootPath . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 
                'View' . DIRECTORY_SEPARATOR . $controllerName . DIRECTORY_SEPARATOR . 
                $view . '.php';
        
        // Vérification de l'existence du fichier de vue
        if (file_exists($viewPath)) {
            // Capture du contenu de la vue spécifique
            ob_start();
            include($viewPath);
            $view = ob_get_clean();
            
            // Construction du chemin vers le layout principal
            $layoutPath = $rootPath . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 
                        'View' . DIRECTORY_SEPARATOR . 'index.php';
            
            // Inclusion du layout principal avec la vue spécifique
            ob_start();
            include($layoutPath);
            self::$_render = ob_get_clean();
        } else {
            // Gestion d'erreur si la vue n'existe pas
            self::$_render = "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; margin: 10px;'>
                <h3>Erreur de rendu</h3>
                <p>La vue '$view' n'a pas été trouvée dans le dossier '$controllerName'.</p>
            </div>";
        }
    }
    
    public function __destruct()
    {
        echo self::$_render ?? '';
    }
}
?>