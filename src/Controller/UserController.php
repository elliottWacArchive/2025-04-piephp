<?php

namespace Controller;

use Core\Controller;

class UserController extends Controller
{

    public function loginAction()
    {
        $error = null;
        $email = null;
        $success = isset($_GET['success']) ? $_GET['success'] : null;
        
        // Si le formulaire est soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            
            // Validation des données
            if (empty($email) || empty($password)) {
                $error = "Tous les champs sont obligatoires.";
            } else {
                try {
                    // Recherche de l'utilisateur par email
                    $db = new \PDO('mysql:host=localhost;dbname=PiePHP', 'root', 'root');
                    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
                    $stmt->execute([$email]);
                    $user = $stmt->fetch(\PDO::FETCH_ASSOC);
                    
                    if ($user && password_verify($password, $user['password'])) {
                        // Authentification réussie, démarrer une session
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_email'] = $user['email'];
                        
                        // Redirection vers la page du profil
                        header('Location: /PiePHP/user/show');
                        exit;
                    } else {
                        $error = "Email ou mot de passe incorrect.";
                    }
                } catch (\PDOException $e) {
                    $error = "Erreur de base de données: " . $e->getMessage();
                }
            }
        }
        
        // Affichage du formulaire avec les éventuelles erreurs
        $this->render('login', [
            'error' => $error,
            'email' => $email,
            'success' => $success
        ]);
    }

    public function showAction()
    {
        // Démarrer ou reprendre la session
        session_start();
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            // Redirection vers la page de connexion si non connecté
            header('Location: /PiePHP/user/login');
            exit;
        }
        
        try {
            // Récupérer les informations de l'utilisateur
            $db = new \PDO('mysql:host=localhost;dbname=PiePHP', 'root', 'root');
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($user) {
                // Rendre la vue avec les données de l'utilisateur
                $this->render('show', ['user' => $user]);
            } else {
                // Session invalide, déconnexion
                session_destroy();
                header('Location: /PiePHP/user/login?error=session_invalid');
                exit;
            }
        } catch (\PDOException $e) {
            // En cas d'erreur, afficher un message
            $this->render('show', [
                'error' => "Erreur de base de données: " . $e->getMessage()
            ]);
        }
    }

    public function accueilAction()
    {
        $this->render('accueil'); // Va rendre la vue src/View/User/accueil.php
    }
    public function addAction()
    {
        $this->render('register'); // Va rendre la vue src/View/User/register.php
    }
    public function registerAction()
    {
        $error = null;
        $email = null;
        
        // Si le formulaire est soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
            
            // Validation des données
            if (empty($email) || empty($password) || empty($confirmPassword)) {
                $error = "Tous les champs sont obligatoires.";
            } elseif ($password !== $confirmPassword) {
                $error = "Les mots de passe ne correspondent pas.";
            } else {
                try {
                    // Hashage du mot de passe pour la sécurité
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Création et sauvegarde de l'utilisateur
                    $userModel = new \Model\UserModel($email, $hashedPassword);
                    $userId = $userModel->save();
                    
                    if ($userId) {
                        // Redirection en cas de succès
                        header('Location: /PiePHP/user/login?success=1');
                        exit;
                    } else {
                        $error = "Erreur lors de l'inscription.";
                    }
                } catch (\PDOException $e) {
                    // Gestion des erreurs de base de données
                    $error = "Erreur de base de données: " . $e->getMessage();
                }
            }
        }
        
        // Affichage du formulaire avec les éventuelles erreurs
        $this->render('register', [
            'error' => $error,
            'email' => $email
        ]);
    }
    // public function indexAction()
    // {
    //     // Simulation de données d'utilisateurs pour la démonstration
    //     $users = [
    //         ['id' => 1, 'email' => 'user1@example.com', 'created_at' => '2023-01-15 10:30:00'],
    //         ['id' => 2, 'email' => 'user2@example.com', 'created_at' => '2023-02-20 14:45:00']
    //     ];
        
    //     $this->render('index', ['users' => $users]);
    // }
    
    // public function showAction()
    // {
    //     // Simulation de données d'un utilisateur 
    //     $user = [
    //         'id' => 1,
    //         'email' => 'user1@example.com',
    //         'created_at' => '2023-01-15 10:30:00'
    //     ];
        
    //     $this->render('show', ['user' => $user]);
    // }
    
    // public function registerAction()
    // {
    //     $error = null;
    //     $email = null;
        
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $email = $_POST['email'] ?? null;
    //         $password = $_POST['password'] ?? null;
    //         $confirmPassword = $_POST['confirm_password'] ?? null;

    //         if (!$email || !$password || !$confirmPassword) {
    //             $error = 'Tous les champs sont obligatoires.';
    //         } elseif ($password !== $confirmPassword) {
    //             $error = 'Les mots de passe ne correspondent pas.';
    //         } else {
    //             // Tentative d'enregistrement
    //             $userModel = new UserModel();
    //             // Pour la démonstration, nous simulons juste un message de succès
    //             echo "<div style='padding: 10px; background-color: #dff0d8; color: #3c763d;'>
    //                     Utilisateur enregistré avec succès!
    //                   </div>";
    //             // Redirection ou autre traitement
    //         }
    //     }
        
    //     $this->render('register', ['error' => $error, 'email' => $email]);
    // }
    
    // public function loginAction()
    // {
    //     $error = null;
    //     $email = null;
        
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $email = $_POST['email'] ?? null;
    //         $password = $_POST['password'] ?? null;

    //         if (!$email || !$password) {
    //             $error = 'Veuillez fournir un email et un mot de passe.';
    //         } else {
    //             // Tentative de connexion
    //             // Pour la démonstration, nous acceptons n'importe quels identifiants
    //             echo "<div style='padding: 10px; background-color: #dff0d8; color: #3c763d;'>
    //                     Connexion réussie!
    //                 </div>";
    //             // Redirection ou autre traitement
    //         }
    //     }
        
    //     $this->render('login', ['error' => $error, 'email' => $email]);
    // }
}
?>