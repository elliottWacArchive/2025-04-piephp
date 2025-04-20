<?php
spl_autoload_register(function ($class) {
    // Convertir les namespaces à des chemins de fichiers
    $classPath = str_replace('\\', '/', $class) . '.php';
    
    // Vérifier si c'est une classe du Core
    $corePath = __DIR__ . '/' . basename($classPath);
    if (file_exists($corePath)) {
        require_once $corePath;
        return;
    }
    
    // Sinon, chercher dans le répertoire src
    $srcPath = __DIR__ . '/../src/' . $classPath;
    if (file_exists($srcPath)) {
        require_once $srcPath;
        return;
    }
    
    // Si la classe n'est pas trouvée, afficher un message d'erreur
    throw new \Exception("Classe introuvable: $class (Chemins recherchés: $corePath, $srcPath)");
});
?>