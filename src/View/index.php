<!DOCTYPE html>
<html>

<head>
    <title>PiePHP Framework</title>
    <link rel="stylesheet" href="/PiePHP/webroot/css/reset.css">
    <link rel="stylesheet" href="/PiePHP/webroot/css/style.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="/PiePHP/user/accueil">Accueil</a></li>
                <li><a href="/PiePHP/user/register">Inscription</a></li>
                <li><a href="/PiePHP/user/login">Connexion</a></li>
                <li><a href="/PiePHP/user/show">Mon Compte</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- C'est ici que la vue spécifique sera insérée -->
        <?php echo $view; ?>
    </main>

    <footer>
        <!-- Pied de page -->
    </footer>
</body>

<style>

</style>

</html>