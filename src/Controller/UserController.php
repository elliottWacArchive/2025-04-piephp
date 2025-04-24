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
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            
            if (empty($email) || empty($password)) {
                $error = "Tous les champs sont obligatoires.";
            } else {
                try {
                    $userModel = new \Model\UserModel();
                    $userModel->setEmail($email);
                    
                    $userModel->setPassword($password );
                    $user = $userModel->login();
                    // var_dump($userModel->getEmail());
                    // var_dump($userModel->getPassword());
                    // var_dump($user);
                    if ($user) {
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        header('Location: /PiePHP/user/show');
                        exit;
                    } else {
                        var_dump($user);
                        $error = "Identifiants invalides. $user";
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
        session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /PiePHP/user/login');
            exit;
        }
        
        try {
            $db = new \PDO('mysql:host=localhost;dbname=PiePHP', 'root', 'root');
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($user) {
                $this->render('show', ['user' => $user]);
            } else {
                session_destroy();
                header('Location: /PiePHP/user/login?error=session_invalid');
                exit;
            }
        } catch (\PDOException $e) {
            $this->render('show', [
                'error' => "Erreur de base de données: " . $e->getMessage()
            ]);
        }
    }

    public function accueilAction()
    {
        $this->render('accueil');
    }
    public function addAction()
    {
        $this->render('register');
    }

    public function logoutAction()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        session_destroy();
        
        header('Location: /PiePHP/user/login');
        exit;
    }

    public function registerAction()
    {
        $error = null;
        $email = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
            
            if (empty($email) || empty($password) || empty($confirmPassword)) {
                $error = "Tous les champs sont obligatoires.";
            } elseif ($password !== $confirmPassword) {
                $error = "Les mots de passe ne correspondent pas.";
            } else {
                try {
                    $hashedPassword = $password;
                    
                    $userModel = new \Model\UserModel($email, $hashedPassword);
                    $userModel->save();
                    
                    $user = $userModel->login();
                    if ($user) {
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        header('Location: /PiePHP/user/show');
                        exit;
                    } else {
                        $error = "Erreur lors de l'inscription.";
                    }
                } catch (\PDOException $e) {
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
}
?>